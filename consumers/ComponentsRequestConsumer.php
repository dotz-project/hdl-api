<?php

namespace app\consumers;

use mikemadisonweb\rabbitmq\components\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

use app\drivers\Driver;
use app\models\Components;


class ComponentsRequestConsumer implements ConsumerInterface
{
   
    public function execute(AMQPMessage $msg)
    {
        $data = json_decode($msg->body,true);
        try{
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
                $result = $driver->provision();
                $data['_request_'] = $result;
                \Yii::$app->rabbitmq->getProducer('COMPONENTS.PROCESS.PRODUCER')->publish(json_encode($data), 'ORCHESTRATOR', 'ORCHESTRATOR.COMPONENTS.PROCESS');
                return ConsumerInterface::MSG_ACK;
            } catch(\Exception $e){
                error_log(__CLASS__ . ": " . $e->getMessage());
                return ConsumerInterface::MSG_REJECT;
            }
        } catch(\Exception $e){
            error_log(__CLASS__ . ": " . $e->getMessage());
            return ConsumerInterface::MSG_REJECT;
        }
    }
}