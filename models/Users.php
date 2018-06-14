<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;
use \Firebase\JWT\JWT;
use Ramsey\Uuid\Uuid;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $firstname
 * @property string $lastname
 * @property string $cellphone
 * @property string $email
 * @property string $username
 * @property string $avatar
 * @property string $password
 * @property int $ddd_group_id
 * @property int $status
 * @property string $created_at
 */

class Users extends \yii\db\ActiveRecord implements IdentityInterface
{
    const SCENARIO_REGISTER = 'register';
   
    
    private static $_id = null;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    public function afterSave($insert, $changedAttributes) {
        if($insert){
            $auth = \Yii::$app->authManager;
            $role = $auth->getRole('vendedor');
            $auth->assign($role, $this->id);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['avatar'],        'filter',  'filter' => function($value){ return "..."; }],
            [['created_at'],    'filter',  'filter' => function($value){ return date('Y-m-d H:i:s'); }],
            [['status'],        'filter',  'filter' => function($value){ return '1'; }, 'on' => static::SCENARIO_REGISTER ],
            [['password'],      'filter',  'filter' => function($value){ return crypt( $value, "$2a$08$".Yii::$app->params['SALT']."$"); }, 'on' => static::SCENARIO_REGISTER ],

            [['firstname'],     'required', 'message' => 'Nome é requerido.'],
            [['lastname'],      'required', 'message' => 'Sobrenome é requerido.'],
            [['cellphone'],     'required', 'message' => 'Celular é requerido.', 'on' => static::SCENARIO_REGISTER],
            [['email'],         'required', 'message' => 'E-mail é requerido.'],
            [['username'],      'required', 'message' => 'Usuário é requerido.'],
            [['password'],      'required', 'message' => 'Senha é requerido.'],
            [['created_at'],    'required', 'message' => 'Criado em é requerido.'],
            
            [['status'], 'integer'],
            [['created_at',     'avatar'], 'safe'],
            
            [['firstname', 'lastname', 'cellphone', 'email', 'username', 'password'], 'string', 'max' => 255],
            
            [['cellphone'],     'unique'],
            [['email'],         'unique'],
            [['username'],      'unique'],

            
        ];
    }

    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios[static::SCENARIO_REGISTER] = ['firstname','lastname','cellphone','email','username','cellphone','password','ddd_group_id','status','avatar','created_at'];
	    return $scenarios;
    }

    public function validateDddGroup($attribute, $params){
        $dddGroup = $this->findDddGroupByDddNumber($this->cellphone);
        if(empty($dddGroup)){
            $this->addError($attribute, 'Não foi possível associar seu perfil a nehuma regional. Por favor verifique se no campo celular está informando um número com ddd válido.');
        }
    }
    
    public function findDddGroupByDddNumber($cellphone){
        $cellphone = str_replace("(","",$cellphone);
        $cellphone = str_replace(")","",$cellphone);
        $cellphone = str_replace(" ","",$cellphone);
        $ddd = substr($cellphone,0,2);
        return DddGroups::find()->select('id')->where(['LIKE', 'ddds', $ddd])->one();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'cellphone' => 'Cellphone',
            'email' => 'Email',
            'username' => 'Username',
            'avatar' => 'Avatar',
            'password' => 'Password',
            'ddd_group_id' => 'Ddd Group ID',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        $secretKey = base64_decode(yii::$app->params['SECRETKEY']);
        $iv = base64_decode(yii::$app->params['SECRETIV']);
        try{
            $decoded = JWT::decode($token, $secretKey, array('HS512'));
            $decrypted_data = json_decode(openssl_decrypt($decoded->data, 'aes-256-cbc',$secretKey,0,$iv));
            if(!empty($decrypted_data->id)){
                $username = $decrypted_data->username;
                $user = static::findOne(['id' => $decrypted_data->id]);
                if($user->status != 1){
                    throw new \yii\web\UnauthorizedHttpException("A conta do usuário foi desativada");
                } else {
                    static::$_id = $decrypted_data->id;
                    //var_dump((array) $user->attributes);
                    return $user;
                }
            } else {
                throw new \yii\web\UnauthorizedHttpException("Token inválido");
            }
        } catch(ExpiredException $e){
            throw new \yii\web\UnauthorizedHttpException("Token inválido");
        }
    }

    public function getAuthKey(){
         return $this->authKey;
    }

    public function getId(){
        return $this->id;
    }

    public static function findIdentity($id){
        return static::findOne($id);
    }

    public function validateAuthKey($authKey){
        return $this->authKey === $authKey;
    }


    public static function findByIp($ip, $rateLimit, $timePeriod)
	{
		$user = new static;
		$user->ip = $ip;
		$user->rateLimit = $rateLimit;
		$user->timePeriod = $timePeriod;

		return $user;
	}

    public $ip;
    public $timePeriod;
    public $rateLimit;

    
    public function getRateLimit($request, $action)
	{
		return [$this->rateLimit, $this->timePeriod];
    }
    
    public function loadAllowance($request, $action)
	{
		$cache = Yii::$app->cache;
		return [
			$cache->get('user.ratelimit.ip.allowance.' . $this->ip),
			$cache->get('user.ratelimit.ip.allowance_updated_at.' . $this->ip),
		];
    }
    
    public function saveAllowance($request, $action, $allowance, $timestamp)
	{
		$cache = Yii::$app->cache;
		$cache->set('user.ratelimit.ip.allowance.' . $this->ip, $allowance);
		$cache->set('user.ratelimit.ip.allowance_updated_at.' . $this->ip, $timestamp);
	}

   
}