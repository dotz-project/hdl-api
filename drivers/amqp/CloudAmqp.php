<?php
    namespace app\drivers\amqp;
    // https://www.cloudamqp.com/docs/api-instances.html
    //
    class CloudAmqp {
       
        public $apikey = "";
        public $endpoint = "https://customer.cloudkarafka.com/api/instances";
        public $name;
        public $params;
       
        public function __construct($name, $params) {
            
            $this->name = $name;
            $this->params = $params;
        }

        public function provision() {
            $this->_createCluster();
            
        }

        public function available() {
            return true;
        } 

        private function _createCluster(){
            
        }
    }

