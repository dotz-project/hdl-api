<?php
    namespace app\drivers\sqlserver;

    class SqlServer {

        public $name;
        public $params;

        public function __constructor($name, $params) {
            $this->name = $name;
            $this->params = $params;
        }

        public function provision() {
            //CREATE USER Mary WITH PASSWORD = '********';
            //CREATE SCHEMA schema_name [ AUTHORIZATION owner_name ] [;] 
            
        }

        public function available() {

        }

    }

