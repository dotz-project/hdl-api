<?php
namespace app\consumers;

use mikemadisonweb\rabbitmq\components\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire;

use app\drivers\Driver;
use app\models\Components;

class ClusterRequestConsumer implements ConsumerInterface
{
   
    public function execute(AMQPMessage $msg) {
        $data = json_decode($msg->body,true);
        //$headers = $msg->get('application_headers')->getNativeData();
        try{
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
                $result = $driver->provision();
                $data['_request_'] = $result;
                \Yii::$app->rabbitmq->getProducer('CLUSTER.PROCESS.PRODUCER')->publish(json_encode($data), 'ORCHESTRATOR', 'ORCHESTRATOR.CLUSTER.PROCESS');
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