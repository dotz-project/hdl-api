<?php

namespace app\consumers;

use mikemadisonweb\rabbitmq\components\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire;

use app\drivers\Driver;
use app\models\Components;
use app\models\Environments;
use app\jenkins\Jenkins;

class JenkinsBuildProcessConsumer implements ConsumerInterface
{
    public function execute(AMQPMessage $msg)
    {
        $data = json_decode($msg->body,true);
        try{
            try{
                $jenkins = new Jenkins(getenv('JENKINS_HOSTNAME'),getenv('JENKINS_USERNAME'),getenv('JENKINS_API_KEY'));
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