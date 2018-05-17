<?php
    namespace app\drivers;

   
    Class Driver {
        
        private $_driver;

        public function __construct($driver, $name, $params){
            $this->_driver = new $driver($name,$params);
        }

        public function provision(){
            return $this->_driver->provision();
        }

        public function available(){
            return $this->_driver->available();
        }

    }