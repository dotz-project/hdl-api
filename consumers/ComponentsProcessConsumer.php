<?php

namespace app\consumers;

use mikemadisonweb\rabbitmq\components\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

use app\drivers\Driver;
use app\models\Components;
use app\models\DeploymentEnvironmentComponents;



class ComponentsProcessConsumer implements ConsumerInterface
{
   
    public function execute(AMQPMessage $msg)
    {
        $data = json_decode($msg->body,true);
        try{
            $_comp =  Components::findOne($data['component_id']);
        }catch(\Exception $e){
            error_log(__CLASS__ . ": Informações sobre o componente não foram encontradas.");
            return ConsumerInterface::MSG_REJECT;
        }
        if(empty($_comp)){
            error_log(__CLASS__ . ": Informações sobre o componente não foram encontradas.");
            return ConsumerInterface::MSG_REJECT;
        }
        try{
            $driver = new Driver($_comp->driver,$data['data']['name'],["conf"=>$_comp->driver_params,"body"=>$data]);
            $result = $driver->available();
            if($result !== false){
                $dc = DeploymentEnvironmentComponents::findOne(['id'=>$data['id']]);
                $dc->status = 1;
                $dc->feedback = $result;
                $dc->save();
                return ConsumerInterface::MSG_ACK;
            } else {
                return ConsumerInterface::MSG_REJECT;
            }
        } catch(\Exception $e){
            error_log(__CLASS__ . ": " . $e->getMessage());
            return ConsumerInterface::MSG_REJECT;
        }
    }
}