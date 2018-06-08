<?php

namespace app\consumers;

use mikemadisonweb\rabbitmq\components\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire;

use app\drivers\Driver;
use app\models\Components;
use app\models\Environments;
use app\models\EnvironmentComponents;

use app\drivers\amqp\CloudAmqp;

class AMQPClusterRequestConsumer implements ConsumerInterface
{
    public function execute(AMQPMessage $msg) {
        $data = json_decode($msg->body,true);
        try{
            try{
                $_comp =  Components::findOne(['name'=>'AMQP', 'id' => $data['component_id']]);
                echo "Iniciando criação de cluster para o componente {$_comp->name}", PHP_EOL;
            }catch(\Exception $e){
                error_log(__CLASS__ . ": Informações sobre o componente não foram encontradas.");
                return ConsumerInterface::MSG_REJECT;
            }
            
            if(empty($_comp)){
                error_log(__CLASS__ . ": Informações sobre o componente não foram encontradas.");
                return ConsumerInterface::MSG_REJECT;
            }
            
            try{
                $_env = Environments::findOne(['id'=>$data['environment_id']]);
                echo "Iniciando ambiente {$_env->alias}", PHP_EOL;
            } catch(\Exception $e){
                error_log(__CLASS__ . ": " . $e->getMessage());
                return ConsumerInterface::MSG_REJECT;
            }

            try{
                $amqp = new CloudAmqp($_env->alias,[
                    "conf" => $_comp->driver_params,
                    "body" => $data
                ]);
                $result = $amqp->createCluster();
                if ($result['status'] == 200) {
                    $data['_request_'] = json_decode($result['body'], true);
                    \Yii::$app->rabbitmq->getProducer('AMQP.CLUSTER.PROCESS.PRODUCER')->publish(json_encode($data), 'ORCHID', 'AMQP.CLUSTER.PROCESS');
                    return ConsumerInterface::MSG_ACK;
                } else {
                    error_log(__CLASS__ . ": Status inexperado {$result['status']}");
                    return ConsumerInterface::MSG_REJECT;
                }
            } catch(\Exception $e) {
                error_log(__CLASS__ . ": " . $e->getMessage());
                return ConsumerInterface::MSG_REJECT;
            }
        } catch(\Exception $e) {
            error_log(__CLASS__ . ": " . $e->getMessage());
            return ConsumerInterface::MSG_REJECT;
        }     
    }
}