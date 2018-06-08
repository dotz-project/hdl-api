<?php
    namespace app\drivers;

   
    Class Driver {
        
        public $_;

        public function __construct($driver, $name, $params){
            $this->_ = new $driver($name,$params);
        }

    }