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

class AMQPClusterProcessConsumer implements ConsumerInterface
{
    public function execute(AMQPMessage $msg)
    {
        $data = json_decode($msg->body,true);
        try{
            $_comp =  Components::findOne(['name'=>'AMQP', 'id' => $data['component_id']]);
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
            $karafka = new CloudAmqp($_env->alias,[
                "conf" => $_comp->driver_params,
                "body" => $data
            ]);
            $available = $karafka->available();
            if($available !== false){
                $_ec = EnvironmentComponents::findOne(['id'=>$data['id']]);
                $_ec->status = 1;
                $_ec->feedback = $available;
                $_ec->updated_at = date('Y-m-d H:i:s');
                $_ec->save();
                echo "Está pronto" , PHP_EOL;
                echo "##################", PHP_EOL;
                return ConsumerInterface::MSG_ACK;
            } else {
                echo "Ainda não está pronto" , PHP_EOL;
                echo "##################", PHP_EOL;
                return ConsumerInterface::MSG_REJECT;
            }
        } catch(\Exception $e){
            error_log(__CLASS__ . ": " . $e->getMessage());
            return ConsumerInterface::MSG_REJECT;
        } 
    }
}