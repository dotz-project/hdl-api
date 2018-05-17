<?php
    namespace app\drivers\mongodb;

    class MongoDbAtlas {
       
        public $name;
        public $params;

        public function __construct($name, $params) {
            $this->name = $name;
            $this->params = $params;
        }

        public function provision() {
            $this->_createCluster();
            $this->_createUser();
        }

        public function available() {
            $response = static::GET(
                "{$this->params['conf']['endpoint']}/atlas/v1.0/groups/{$this->params['conf']['group']}/clusters/{$this->name}", 
                $this->params['conf']['username'],
                $this->params['conf']['api_key']          
            );
            if($response['status'] != 200){
                throw new \Exception($response['body']);
            }
            $data = json_decode($response["body"], true);
            if (empty($data['stateName'])) {
                return false;
            } else {
                switch($data['stateName']){
                    case "IDLE":
                        return $data;
                        break;
                    case "CREATING":
                    case "UPDATING" :
                    case "DELETING" :
                    case "DELETED" :
                    case "REPAIRING" :
                        return false;
                        break;
                }
            }
        } 

        private function _createCluster(){
            $response = static::POST(
                "{$this->params['conf']['endpoint']}/atlas/v1.0/groups/{$this->params['conf']['group']}/clusters",
                json_encode([
                    "name" => $this->name,
                    "mongoDBMajorVersion" => $this->params['body']['data']['mongoDBMajorVersion'],
                    "numShards" => $this->params['body']['data']['numShards'],
                    "replicationFactor" => $this->params['body']['data']['replicationFactor'],
                    "providerSettings" => [
                        "providerName" => $this->params['body']['data']['providerSettings']['providerName'],
                        "regionName" => $this->params['body']['data']['providerSettings']['regionName'],
                        "instanceSizeName" => $this->params['body']['data']['providerSettings']['instanceSizeName'],
                    ],
                    "diskSizeGB" => $this->params['body']['data']['diskSizeGB'],
                    "backupEnabled" => $this->params['body']['data']['backupEnabled'],
                    "password" => $this->params['body']['data']['password']
                ]),
                $this->params['conf']['username'],
                $this->params['conf']['api_key']
            );
            return $response;
        }

        private function _createUser(){
             $response = static::POST(
                "{$this->params['conf']['endpoint']}/atlas/v1.0/groups/{$this->params['conf']['group']}/databaseUsers", 
                json_encode([
                    "databaseName" => "admin",
                    "groupId" => $this->params['conf']['group'],
                    "username" => "us_{$this->name}",
                    "password" => $this->params['body']['data']['password'],
                    "roles" => [
                        ["roleName" => "readWrite", "databaseName" => $this->name]
                    ]
                ]),
                $this->params['conf']['username'],
                $this->params['conf']['api_key']
            );
            if($response['status'] != 201){
                throw new \Exception($response['body']);
            }
            return $response;
        }

        public static function POST($url,$data,$username,$apikey){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
            curl_setopt($ch, CURLOPT_USERPWD,  $username.':'.$apikey);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [ 
                'Content-Type: application/json'
            ]);
            //curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            $response = curl_exec ($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $header = substr($response, 0, $header_size);
            $body = substr($response, $header_size);
            curl_close($ch);
            return ['status' => $http_status, 'body' => $body];
        }

        public static function GET($url,$username,$apikey){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
            curl_setopt($ch, CURLOPT_USERPWD,  $username.':'.$apikey);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [ 
                'Content-Type: application/json'
            ]);
            //curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            $response = curl_exec ($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $header = substr($response, 0, $header_size);
            $body = substr($response, $header_size);
            curl_close($ch);
            return ['status' => $http_status, 'body' => $body];
        }
    }