<?php

namespace app\consumers;

use mikemadisonweb\rabbitmq\components\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire;

use app\drivers\Driver;
use app\models\Components;
use app\models\Environments;
use app\models\EnvironmentComponents;

use app\drivers\kubernetes\KubernetesCluster;

class KubernetClusterRequestConsumer implements ConsumerInterface
{
    public function execute(AMQPMessage $msg) {
        
        $data = json_decode($msg->body,true);
        
        try{
            $_comp =  Components::findOne(['name'=>'Kubernetes', 'id' => $data['component_id']]);
            echo "Iniciando processo de conclusão do cluster para o componente {$_comp->name}", PHP_EOL;
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
            $kubernetes = new KubernetesCluster(
                $_env->alias,
                [
                    "conf"=>$_comp->driver_params,
                    "body"=>$data
                ]
            );
            $result = $kubernetes->createCluster();
            $data['_request_'] = $result;
            \Yii::$app->rabbitmq->getProducer('KUBERNET.CLUSTER.PROCESS.PRODUCER')->publish(json_encode($data), 'ORCHID', 'KUBERNET.CLUSTER.PROCESS');
            return ConsumerInterface::MSG_ACK;
        } catch(\Exception $e){
            $_data = json_decode($e->getMessage(), true);
            if(!empty($_data['code']) && $_data['code'] == 6){
                $data['_request_'] = $_data;
                \Yii::$app->rabbitmq->getProducer('KUBERNET.CLUSTER.PROCESS.PRODUCER')->publish(json_encode($data), 'ORCHID', 'KUBERNET.CLUSTER.PROCESS');
                return ConsumerInterface::MSG_ACK;
            }
            error_log(__CLASS__ . ": " . $e->getMessage());
            return ConsumerInterface::MSG_REJECT;
        }
    }
}