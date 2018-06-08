<?php
namespace app\modules\apiv1\controllers;
use yii\web\Controller;

class HealthController extends Controller
{
    public $layout = false;
    public $health = true;
    public $healthChecks = []; 
    public $_error;
    public $_info;
    
    public function init(){
        $config = \Yii::$app->getModule('apiv1')->healthchecks;
        foreach ($config as $key=>$val) {
            if (!is_callable($val)) {
                $key = $val;
                $val = [$this, lcfirst($key) . "Check"];
            }
            $this->addHealthCheck($key, $val);
        }
    }
    
    public function actionIndex() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $result = $this->doHealthChecks();
        if (!$this->getHealth()) {
            \Yii::$app->response->setStatusCode(424);
        }
        return $result;
    }

    public function addHealthCheck($name, $callback) {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('Health check must be callable');
        }
        $this->healthChecks[$name] = $callback;
    }

    public function getHealth() {
        return $this->health;
    }

    public function doHealthChecks() {
        $this->health = true;
        $result = [];
        foreach ($this->healthChecks as $name=>$callback) {
            
            list($usec, $sec) = explode(' ', microtime());
            $script_start = (float) $sec + (float) $usec;
            
            $check = call_user_func($callback, $this);

            list($usec, $sec) = explode(' ', microtime());
            $script_end = (float) $sec + (float) $usec;
            $elapsed_time = round($script_end - $script_start, 5);
            
            $model = new \stdClass;
            $model->name = $name;
            $model->passed = $check['status'];
            $model->elapsed = $elapsed_time;
            $model->info = (object) $check['info'];
            $model->error = (string) $check['error'];
            $result[] = $model;
        }
        return $result;
    }

    public function dbCheck() {
        try {
            $connection = \Yii::$app->db;
            $connection->open();
            if ($connection->pdo !== null) {
                return [
                    'status' => true,
                    'info' => (object) [],
                    'error' => ''   
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => false,
                'info' => (object) [],
                'error' => $e->getMessage()   
            ];
        }
    }

    public function cacheCheck() {
        try {
            $cache = \Yii::$app->cache;
            $cache->set('healthcheck', 1);
            return [
                'status' => true,
                'info' => (object) [],
                'error' => ''   
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'info' => (object) [],
                'error' => $e->getMessage()   
            ];
        }
    }

    public function rabbitmqCheck() {
        try {
            $r = \Yii::$app->rabbitmq->getConsumer('CLUSTER.PROCESS.CONSUMER')->getQueues();
            return [
                'status' => true,
                'info' => (object) [],
                'error' => ''   
            ];        
        } catch (\Exception $e) {
            return [
                'status' => false,
                'info' => (object) [],
                'error' => $e->getMessage()   
            ];
        }
    }

    public function jenkinsCheck() {
        try {
            $jenkins = new \app\jenkins\Jenkins(getenv('JENKINS_HOSTNAME'),getenv('JENKINS_USERNAME'),getenv('JENKINS_API_KEY'));
            if($jenkins->isAvailable()){
                return [
                    'status' => true,
                    'info' => (object) [],
                    'error' => ''   
                ];
            } else {
                return [
                    'status' => false,
                    'info' => (object) [],
                    'error' => 'Unavailable'   
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => false,
                'info' => (object) [],
                'error' => $e->getMessage()   
            ];
        }
    }

    public function applicationCheck() {
        try {

            $info = [
                "version" => \Yii::$app->params["version"],
                "environment" => [
                    "phpVersion" => (version_compare(PHP_VERSION, '7.0', '>=')),
                    "mbstring" => extension_loaded('mbstring'),
                    "gnupg" => extension_loaded('gnupg'),
                    "intl" => extension_loaded('intl'),
                    "image" => (extension_loaded('gd') || extension_loaded('imagick')),
                    "app_runtime_writable" => self::_checkRecursiveDirectoryWritable(\Yii::getAlias('@runtime')),
                    "app_assets_writable" => self::_checkRecursiveDirectoryWritable(\Yii::getAlias('@app/assets')),
               ],
            ];

            return [
                'status' => true,
                'info' => (object) $info,
                'error' => ''   
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'info' => (object) [],
                'error' => $e->getMessage()   
            ];
        }
    }


    private static function _checkRecursiveDirectoryWritable($path) {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($iterator as $name => $fileInfo) {
            if (in_array($fileInfo->getFilename(), ['.', '..', 'empty'])) {
                continue;
            }
            if (!is_writable($name)) {
                return false;
            }
        }
        return true;
    }

    /*
    private static function _checkSSL($url){
       $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        var_dump($response);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_CAINFO, getcwd() . "/CAcerts/BuiltinObjectToken-EquifaxSecureCA.crt");


        curl_close($ch);
    
    }*/
    
}