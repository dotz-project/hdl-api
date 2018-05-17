<?php
    namespace app\drivers\karafka;
    //https://customer.cloudkarafka.com/team/api
    class CloudKarafka {
       
        public $apikey = "85c146d6-2322-4e69-9019-cb51d43ce2da";
        public $endpoint = "https://customer.cloudkarafka.com/api/instances";
        public $name;
        public $params;
       
        public function __construct($name, $params) {
            
            $this->name = $name;
            $this->params = $params;
        }

        public function provision() {
            $cluster = $this->_createCluster();
            return $cluster;
        }

        public function available() {
            $data = json_decode($this->params['_request_'],true);
            if(!empty($data['id'])){
                return $data;
            } else {
                return false;
            }
        } 

        private function _createCluster(){
            $response = static::POST(
                "{$this->params['conf']['endpoint']}/instances", 
                [
                    "name"=>$this->name,
                    "plan"=>$this->params['body']['data']['plan'], //"tiger"
                    "region"=>$this->params['body']['data']['region'] //"amazon-web-services::us-east-1"
                ],
                $this->params['conf']['api_key']
            );
            if($response['status'] != 200){
                throw new \Exception($response['body']);
            }
            return $response;
        }

        public static function POST($url,$data,$apikey){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_USERPWD,  ':'.$apikey);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [ 
                'Content-Type: application/x-www-form-urlencoded'
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

        public static function GET($url,$apikey){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_USERPWD,  ':'.$apikey);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [ 
                'Content-Type: application/x-www-form-urlencoded'
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

