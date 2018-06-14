<?php

namespace app\modules\apiv1\controllers;

use yii;
use app\models\Users;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;
use \Firebase\JWT\JWT;

use yii\filters\auth\HttpBearerAuth;

use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

/**
 * Default controller for the `apiv1` module
 */
class UsersController extends ActiveController
{
    public $modelClass = 'app\models\Users';
   
    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'except' => ['login','register','options'],
        ]; 
        $behaviors['rateLimiter'] = [
            'class' => \highweb\ratelimiter\RateLimiter::className(),
            'rateLimit' => 200,
            'timePeriod' => 1,
            'separateRates' => false,
            'enableRateLimitHeaders' => true,
        ];
        return $behaviors;
    }

    public function checkAccess($action, $model = null, $params = []){
        
        if (!Yii::$app->user->can("api.users.{$action}") && !empty(yii::$app->user->id)) 
            throw new \yii\web\ForbiddenHttpException(sprintf('Você não tem permissão para acessar este recurso.', $action));
        return true;
    }

    public function beforeAction($action){
        switch($action->id){
            case "view":
            case "create":
            case "update":
            case "delete":
            default:
                break;
        }
        return parent::beforeAction($action);
    }
    public function afterAction($action, $result){
        $result = parent::afterAction($action, $result);
        switch($action->id){
            case "index":
                foreach($result as $k => $v){
                    unset($result[$k]['password']);
                }    
                break;
            case "view":
            case "register":
            case "create":
                unset($result['password']);
                break;
            case "update":
            case "delete":
            default:
                break;
        }
        return $result;
    }
  
    public function actionLogin(){
        $data = Yii::$app->request->bodyParams;
        $userHost = Yii::$app->request->userHost;
        $userIP = Yii::$app->request->userIP;
        if(Yii::$app->request->isPost){
            $user = Users::find()->where(["username"=>$data['username']])->one();
            if(!empty($user)){
                if(trim($user->password) == trim(crypt($data['password'],"$2a$08$".Yii::$app->params['SALT']."$"))){
                    $token = $this->_generateToken($user);
                    if(!empty($token)){
                       return $token; 
                    } else {
                        throw new \yii\web\UnauthorizedHttpException("Problema ao gerar o token");
                    }
                } else {
                    throw new \yii\web\UnauthorizedHttpException("Usuario ou senha inválidos");
                }
            } else {
                throw new \yii\web\UnauthorizedHttpException("Usuario não encontrado");
            }
        }
    }

    public function actionMe(){
       
        $_user = Users::find()->select(['id', 'firstname','lastname','status','created_at','email','avatar','cellphone'])->where(['id'=>Yii::$app->user->id])->one();
        $user = $_user->attributes;
        $user['roles'] =  array_keys(\Yii::$app->authManager->getRolesByUser($_user->id));
        $user['permissions'] =  array_keys(\Yii::$app->authManager->getPermissionsByUser($_user->id));
        return $user;
    }

    public function actionRegister(){

        $model = new Users(['scenario' => 'register']);
        $model->attributes = Yii::$app->request->bodyParams;
        if($model->save()){
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($model->getPrimaryKey(true)));
            $response->getHeaders()->set('Location', Url::toRoute([$this->action->id, 'id' => $id], true));
        } 
        return $model; 
    }

    public function actionForgotPassword(){ }

    public function actionRedefinePassword(){ }

    private function _generateToken($user) {
        if(!empty($user)){
            $secretKey = base64_decode(Yii::$app->params['SECRETKEY']);
            $iv = base64_decode(Yii::$app->params['SECRETIV']);
            $now = time();
            $data = json_encode(['id' => $user->id, 'username' => $user->username]);
            $data = openssl_encrypt($data, 'aes-256-cbc',$secretKey,0,$iv);
            $token = array(
                "jti" => base64_encode(random_bytes(32)),
                "iat" => $now, // criado em 
                "iss" => Yii::$app->params['SERVER_NAME'],
                "aud" => Yii::$app->params['SERVER_NAME'],
                "nbf" => $now, // não valido antes de
                "exp" => $now + ((3600 * 24) * 30), // válido até
                "data" => $data
            );
            $jwt = JWT::encode($token, $secretKey, 'HS512');
            $decoded = JWT::decode($jwt, $secretKey, array('HS512'));
            $decrypted_data = openssl_decrypt($decoded->data, 'aes-256-cbc',$secretKey,0,$iv);
            return "Bearer " . $jwt;
        } else {
            return false;
        }
    }

    public function actionMeu2(){


        $cache = Yii::$app->cache;
		$cache->set('user.ratelimit.ip.allowance',uniqid());
		$cache->set('user.ratelimit.ip.allowance_updated_at',uniqid());
        
        $a = $cache->get('user.ratelimit.ip.allowance');
        $b = $cache->get('user.ratelimit.ip.allowance_updated_at');
    
        return [$a,$b];
        $data = Yii::$app->request->bodyParams;
        return ['status' => $data['password'],"$2a$08$".Yii::$app->params['SALT']."$"];
    }
}
 
/*
public function actionMeu(){
        $key = "example_key";
        $token = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" => 1356999524,
            "nbf" => 1357000000
        );

    
        $jwt = JWT::encode($token, $key);
        $decoded = JWT::decode($jwt, $key, array('HS256'));

        print_r($decoded);
        
        
        $decoded_array = (array) $decoded;
        
    
        JWT::$leeway = 60; // $leeway in seconds
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        
        print_r($decoded);



            $privateKey = <<<EOD
    -----BEGIN RSA PRIVATE KEY-----
    MIICXAIBAAKBgQC8kGa1pSjbSYZVebtTRBLxBz5H4i2p/llLCrEeQhta5kaQu/Rn
    vuER4W8oDH3+3iuIYW4VQAzyqFpwuzjkDI+17t5t0tyazyZ8JXw+KgXTxldMPEL9
    5+qVhgXvwtihXC1c5oGbRlEDvDF6Sa53rcFVsYJ4ehde/zUxo6UvS7UrBQIDAQAB
    AoGAb/MXV46XxCFRxNuB8LyAtmLDgi/xRnTAlMHjSACddwkyKem8//8eZtw9fzxz
    bWZ/1/doQOuHBGYZU8aDzzj59FZ78dyzNFoF91hbvZKkg+6wGyd/LrGVEB+Xre0J
    Nil0GReM2AHDNZUYRv+HYJPIOrB0CRczLQsgFJ8K6aAD6F0CQQDzbpjYdx10qgK1
    cP59UHiHjPZYC0loEsk7s+hUmT3QHerAQJMZWC11Qrn2N+ybwwNblDKv+s5qgMQ5
    5tNoQ9IfAkEAxkyffU6ythpg/H0Ixe1I2rd0GbF05biIzO/i77Det3n4YsJVlDck
    ZkcvY3SK2iRIL4c9yY6hlIhs+K9wXTtGWwJBAO9Dskl48mO7woPR9uD22jDpNSwe
    k90OMepTjzSvlhjbfuPN1IdhqvSJTDychRwn1kIJ7LQZgQ8fVz9OCFZ/6qMCQGOb
    qaGwHmUK6xzpUbbacnYrIM6nLSkXgOAwv7XXCojvY614ILTK3iXiLBOxPu5Eu13k
    eUz9sHyD6vkgZzjtxXECQAkp4Xerf5TGfQXGXhxIX52yH+N2LtujCdkQZjXAsGdm
    B2zNzvrlgRmgBrklMTrMYgm1NPcW+bRLGcwgW2PTvNM=
    -----END RSA PRIVATE KEY-----
    EOD;

    $publicKey = <<<EOD
    -----BEGIN PUBLIC KEY-----
    MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC8kGa1pSjbSYZVebtTRBLxBz5H
    4i2p/llLCrEeQhta5kaQu/RnvuER4W8oDH3+3iuIYW4VQAzyqFpwuzjkDI+17t5t
    0tyazyZ8JXw+KgXTxldMPEL95+qVhgXvwtihXC1c5oGbRlEDvDF6Sa53rcFVsYJ4
    ehde/zUxo6UvS7UrBQIDAQAB
    -----END PUBLIC KEY-----
    EOD;



    $token = array(
        "iss" => "example.org",
        "aud" => "example.com",
        "iat" => 1356999524,
        "nbf" => 1357000000
    );

    $jwt = JWT::encode($token, $privateKey, 'RS256');
    echo "Encode:\n" . print_r($jwt, true) . "\n";

    $decoded = JWT::decode($jwt, $publicKey, array('RS256'));



    $decoded_array = (array) $decoded;
    echo "Decode:\n" . print_r($decoded_array, true) . "\n";
    
    
    
    die("MB");
}
*/

    