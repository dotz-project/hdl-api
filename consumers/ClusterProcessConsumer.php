<?php

namespace app\consumers;

use mikemadisonweb\rabbitmq\components\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire;

use app\drivers\Driver;
use app\models\Components;
use app\models\Environments;


class ClusterProcessConsumer implements ConsumerInterface
{
    public function execute(AMQPMessage $msg)
    {
        
        $data = json_decode($msg->body,true);
        
        try{
            $_comp =  Components::findOne(['name'=>'Kubernetes']);
        }catch(\Exception $e){
            error_log(__CLASS__ . ": Informações sobre o componente não foram encontradas.");
            return ConsumerInterface::MSG_REJECT;
        }
        if(empty($_comp)){
            error_log(__CLASS__ . ": Informações sobre o componente não foram encontradas.");
            return ConsumerInterface::MSG_REJECT;
        }
        try{
            $driver = new Driver($_comp->driver,$data['name'],["conf"=>json_decode($_comp->driver_params,true),"body"=>$data]);
            if($driver->available()){
                $env = Environments::findOne(['id'=>$data['id']]);
                $env->status = 1;
                $env->gcStatus = 1;
                $env->save();
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