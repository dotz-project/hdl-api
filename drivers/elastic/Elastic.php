<?php
    namespace app\drivers\elastic;

    class Elastic {
       
        public $name;
        public $params;
        public $token;

        public function __construct($name, $params) {
            $this->name = $name;
            $this->params = $params;
            $this->token = static::LOGIN();
        }

        public function provision() {
        }

        public function available() {
            $this->params['body']['_request_']['elasticsearch_cluster_id'];
            $this->params['body']['_request_']['kibana_cluster_id'];
            $this->params['body']['_request_']['credentials'];
            $this->params['body']['_request_']['credentials'];
            $this->params['conf']['region'];

            $response = static::GET("https://cloud.elastic.co/api/v0/v1-regions/{$this->params['conf']['region']}/clusters/elasticsearch/{$this->params['body']['_request_']['elasticsearch_cluster_id']}?show_security=true&show_metadata=true&show_plans=true&show_plan_logs=true&show_plan_defaults=true&convert_legacy_plans=false&show_system_alerts=3&show_settings=true");
            

            var_dump($response);

            if($response['status'] != 200){
                throw new \Exception($response['body']);
            }
            $data = json_decode($response["body"], true);
            if (empty($data['status'])) {
                return false;
            } else {
                switch($data['status']){
                    case "started":
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

        public function createCluster(){
            $data = [
                "cluster_name"=>$this->name,
                "plan"=>[
                    "zone_count"=>1,
                    "cluster_topology"=>[
                        [
                            "node_configuration"=>"highio.legacy",
                            "memory_per_node"=>1024
                        ]
                    ],
                    "elasticsearch"=>[
                        "include_default_plugins"=>true,
                        "enabled_built_in_plugins"=>[],
                        "user_bundles"=>[],
                        "user_plugins"=>[],
                        "system_settings"=>[
                            "auto_create_index"=>true,
                            "destructive_requires_name"=>false,
                            "scripting"=>[
                                "inline"=>["enabled"=>true],
                                "stored"=>["enabled"=>true]
                            ]
                        ],
                        "version"=>"6.2.4"
                    ],
                    "transient"=>["strategy"=>["grow_and_shrink"=>(object)[]]]
                ],
                "settings"=>[
                    "snapshot"=>["enabled"=>true]
                ],
                "kibana"=>[
                    "plan"=>[
                        "zone_count"=>1,
                        "kibana"=>(object)[],
                        "cluster_topology"=>[["memory_per_node"=>1024]]
                    ]
                ]
            ];
           
            return static::POST(
                "https://cloud.elastic.co/api/v0/v1-regions/{$this->params['conf']['region']}/clusters/elasticsearch?validate_only=false",
                json_encode($data)
            );
        }

        

        public static function LOGIN(){

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://cloud.elastic.co/api/v1/users/_login");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["email"=>"infra@dotz.com.br","password"=>"Host.cbsm@dotz53378!"]));
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
            $parsed = json_decode($body,true);
            if(!empty($parsed['token'])){
                echo $parsed['token'];
                return "Authorization: Bearer {$parsed['token']}";
            }
            return false;
            
        }

        public function POST($url,$data){
            echo $url, PHP_EOL;
            echo $data, PHP_EOL;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $header = [ 
                'Content-Type: application/json'
            ];
            if(!empty($this->token)){
                $header[] = $this->token;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            //curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            $response = curl_exec ($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $header = substr($response, 0, $header_size);
            $body = substr($response, $header_size);
            curl_close($ch);
            var_dump($header);
            var_dump($body);
            return ['status' => $http_status, 'body' => $body, 'data' => json_decode($body)];
        }

        public function GET($url){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $header = [ 
                'Content-Type: application/json'
            ];
            if(!empty($this->token)){
                $header[] = $this->token;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            //curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            $response = curl_exec ($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $header = substr($response, 0, $header_size);
            $body = substr($response, $header_size);
            curl_close($ch);
            return ['status' => $http_status, 'body' => $body, 'data' => json_decode($body)];
        }
    }

    /**
     * LOGIN
     * curl -k -X POST https://cloud.elastic.co/api/v1/users/_login -H 'content-type: application/json' -H 'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjo2MjY4MzkxNCwib2t0YV9zZXNzaW9uX2lkIjoiMjAxMTEwVU8xcWFvTTVMTDhIZGd1TjdJTFY2SHJwNEhzMlRHZ3lsWWhnTkFYQ0R4eXQyb2VqWCIsInNleHAiOjE1MjgzOTE3ODAuNTM2MTM3LCJleHAiOjE1MjgzMTI1ODAuNTM2MTM3LCJzaWQiOiJhMGU2YWRhMjVlZTM5NzgxYTc5NTA3NjY2OGZlMjQ0ZTgwNmVmOGU1MjZiMzcxMWRmNjYzNDAyYTNmMzM5ZWUwIiwiaWF0IjoxNTI4MzA1MzgwLjUzNjEzN30.dQfVW_AmI6QIrZMhjx7D1wsn5sAvI2fYm8-WIeDu_aI' -d '{"email":"infra@dotz.com.br","password":"Host.cbsm@dotz53378!"}' 
     * {
     *   "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjo2MjY4MzkxNCwib2t0YV9zZXNzaW9uX2lkIjoiMjAxMTE5LVNQWWJjXzZIbmYtVUVSQXJwUHRWcHNQMWhWTjg0Tlgza2JBWUVNdS1wR1Nfb2tfUCIsInNleHAiOjE1MjgzOTQ0NzcuNDkzMDY4LCJleHAiOjE1MjgzMTUyNzcuNDkzMDY4LCJzaWQiOiI4YmQwZDUxMDEyNjBkMjQ2MjcxM2ZhN2FiNjZmZGYxYWMzMjljMWI5Y2E0ZTU0YzAxYzdiYjY4ZjdiZGJjNzk0IiwiaWF0IjoxNTI4MzA4MDc3LjQ5MzA2OH0.hBSfrz7Vazfx2EUbBXeqsZ5V7VyiN4__5_HF03g6Phg", 
     *   "ok": true, 
     *   "user": {
     *       "allow_arbitrary_code": false, 
     *       "aws_subscribed": false, 
     *       "domain": "found", 
     *       "wants_informational_emails": true, 
     *       "accepted_tos": "2018-03-05T18:41:32Z", 
     *       "user_id": 62683914, 
     *       "trials": [
     *           {
     *               "end": "2018-03-19T18:50:21Z", 
     *               "restartable": false, 
     *               "data": {}, 
     *               "txid": 123661786, 
     *               "start": "2018-03-05T18:50:21Z", 
     *               "user_id": 62683914, 
     *               "type": "elasticsearch"
     *           }
     *       ], 
     *       "txid": 228034169, 
     *       "prepaid_products": [
     *           {
     *               "start": "2018-03-05T18:50:21Z", 
     *               "product": {
     *                   "product_id": 2906414563, 
     *                   "nsid": null, 
     *                   "created": "2017-08-03T12:36:10Z", 
     *                   "txid": 100883805, 
     *                   "last_modified": "2017-08-03T12:36:10Z", 
     *                   "identifier": "trial", 
     *                   "data": {
     *                       "pretty_name": "Cloud Std. Trial AWS 4GB HA (2 Zones) SSD", 
     *                       "availability_zones": 2, 
     *                       "capacity": 4096, 
     *                       "level": "standard", 
     *                       "pretty_capacity": "4GB", 
     *                       "region": "ap-northeast-1"
     *                   }
     *               }, 
     *               "end": "2018-03-19T18:50:21Z", 
     *               "data": null
     *           }
     *       ], 
     *       "is_paying": true, 
     *       "email": "infra@dotz.com.br", 
     *       "allow_provisioning_without_payment_established": false, 
     *       "last_modified": "2018-05-29T13:26:43Z", 
     *       "aws_customer_id": null, 
     *       "data": {
     *           "disk_usage_warning": {
     *               "enabled": true
     *           }, 
     *           "city": "Sao Paulo", 
     *           "first_name": "Nestor", 
     *           "last_name": "T Andrade", 
     *           "zip": "04834011", 
     *           "address1": "Rua Joaquim Floriano, 533", 
     *           "signup": {
     *               "page": "https://www.elastic.co/cloud/as-a-service/signup"
     *           }, 
     *           "state": "Sao Paulo", 
     *           "company_name": "Nestor T Andrade", 
     *           "country": "BR", 
     *           "address2": "", 
     *           "vat": ""
     *       }, 
     *       "invoicable": false, 
     *       "credit_limit": 50000000, 
     *       "nsid": null, 
     *       "created": "2018-03-05T18:41:32Z", 
     *       "email_verified": "2018-03-05T18:42:07Z", 
     *       "level": "standard", 
     *       "deny_arbitrary_code": false, 
     *       "recurly_billing_info": {
     *           "card_type": "Visa", 
     *           "year": 2021, 
     *           "last_four": "4240", 
     *           "month": 11
     *       }
     *   }
     *
     * 
     * 
     * 
     * CREATE DEPLOYMENT
     * curl -k -X POST https://cloud.elastic.co/api/v0/v1-regions/us-east-1/clusters/elasticsearch?validate_only=false -H 'content-type: application/json' -H 'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjo2MjY4MzkxNCwib2t0YV9zZXNzaW9uX2lkIjoiMjAxMTEwVU8xcWFvTTVMTDhIZGd1TjdJTFY2SHJwNEhzMlRHZ3lsWWhnTkFYQ0R4eXQyb2VqWCIsInNleHAiOjE1MjgzOTE3ODAuNTM2MTM3LCJleHAiOjE1MjgzMTI1ODAuNTM2MTM3LCJzaWQiOiJhMGU2YWRhMjVlZTM5NzgxYTc5NTA3NjY2OGZlMjQ0ZTgwNmVmOGU1MjZiMzcxMWRmNjYzNDAyYTNmMzM5ZWUwIiwiaWF0IjoxNTI4MzA1MzgwLjUzNjEzN30.dQfVW_AmI6QIrZMhjx7D1wsn5sAvI2fYm8-WIeDu_aI' -d '{"cluster_name":"TESTE","plan":{"zone_count":1,"cluster_topology":[{"node_configuration":"highio.legacy","memory_per_node":1024}],"elasticsearch":{"include_default_plugins":true,"enabled_built_in_plugins":[],"user_bundles":[],"user_plugins":[],"system_settings":{"auto_create_index":true,"destructive_requires_name":false,"scripting":{"inline":{"enabled":true},"stored":{"enabled":true}}},"version":"6.2.4"},"transient":{"strategy":{"grow_and_shrink":{}}}},"settings":{"snapshot":{"enabled":true}},"kibana":{"plan":{"zone_count":1,"kibana":{},"cluster_topology":[{"memory_per_node":1024}]}}}' 
     * {
     *        "kibana_cluster_id": "4994173410854b7f853e8c10f8821c68", 
     *        "credentials": {
     *            "username": "elastic", 
     *            "password": "Qo1iSsi79Ps7gjapTeVMfD4h"
     *        }, 
     *        "elasticsearch_cluster_id": "305129312500499d90715a8703eb2f33"
     *    }
     * 
     * 
     * LIST
     * 
     *  curl -k -X POST https://cloud.elastic.co/api/v0/v1-regions/clusters/elasticsearch/_search -H 'content-type: application/json' -H 'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjo2MjY4MzkxNCwib2t0YV9zZXNzaW9uX2lkIjoiMjAxMTEwVU8xcWFvTTVMTDhIZGd1TjdJTFY2SHJwNEhzMlRHZ3lsWWhnTkFYQ0R4eXQyb2VqWCIsInNleHAiOjE1MjgzOTE3ODAuNTM2MTM3LCJleHAiOjE1MjgzMTI1ODAuNTM2MTM3LCJzaWQiOiJhMGU2YWRhMjVlZTM5NzgxYTc5NTA3NjY2OGZlMjQ0ZTgwNmVmOGU1MjZiMzcxMWRmNjYzNDAyYTNmMzM5ZWUwIiwiaWF0IjoxNTI4MzA1MzgwLjUzNjEzN30.dQfVW_AmI6QIrZMhjx7D1wsn5sAvI2fYm8-WIeDu_aI'
     * 
     * 
     * DETAIL elastic
     * 
     *  curl -k  "https://cloud.elastic.co/api/v0/v1-regions/sa-east-1/clusters/elasticsearch/f2ffd5781fb197cb18fde78e5b7042de?show_security=true&show_metadata=true&show_plans=true&show_plan_logs=true&show_plan_defaults=true&convert_legacy_plans=false&show_system_alerts=3&show_settings=true" -H 'content-type: application/json' -H 'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjo2MjY4MzkxNCwib2t0YV9zZXNzaW9uX2lkIjoiMjAxMTEwVU8xcWFvTTVMTDhIZGd1TjdJTFY2SHJwNEhzMlRHZ3lsWWhnTkFYQ0R4eXQyb2VqWCIsInNleHAiOjE1MjgzOTE3ODAuNTM2MTM3LCJleHAiOjE1MjgzMTI1ODAuNTM2MTM3LCJzaWQiOiJhMGU2YWRhMjVlZTM5NzgxYTc5NTA3NjY2OGZlMjQ0ZTgwNmVmOGU1MjZiMzcxMWRmNjYzNDAyYTNmMzM5ZWUwIiwiaWF0IjoxNTI4MzA1MzgwLjUzNjEzN30.dQfVW_AmI6QIrZMhjx7D1wsn5sAvI2fYm8-WIeDu_aI'
     * 
     * DETAIL kibana
     * 
     *  curl -k  "https://cloud.elastic.co/api/v0/v1-regions/sa-east-1/clusters/kibana/f893966cbb8cab709806b736a0fbd342?show_metadata=true&show_plans=true&show_plan_logs=true&show_plan_defaults=false"  -H 'content-type: application/json' -H 'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjo2MjY4MzkxNCwib2t0YV9zZXNzaW9uX2lkIjoiMjAxMTEwVU8xcWFvTTVMTDhIZGd1TjdJTFY2SHJwNEhzMlRHZ3lsWWhnTkFYQ0R4eXQyb2VqWCIsInNleHAiOjE1MjgzOTE3ODAuNTM2MTM3LCJleHAiOjE1MjgzMTI1ODAuNTM2MTM3LCJzaWQiOiJhMGU2YWRhMjVlZTM5NzgxYTc5NTA3NjY2OGZlMjQ0ZTgwNmVmOGU1MjZiMzcxMWRmNjYzNDAyYTNmMzM5ZWUwIiwiaWF0IjoxNTI4MzA1MzgwLjUzNjEzN30.dQfVW_AmI6QIrZMhjx7D1wsn5sAvI2fYm8-WIeDu_aI'
     */