<?php

use yii\db\Migration;

/**
 * Class m180510_233250_seed_add_kafka_component_
 */
class m180510_233250_seed_add_kafka_component_ extends Migration
{
    public function up()
    {
        $this->insert('components', [
            'id' => 3,
            'name' => 'Karafka',
            'avatar' => 'karafka.png',
            'type' => '3',
            'description' => "Apache Kafka é uma plataforma distribuída de mensagens e streaming.",
            'keys' => "KAFKA_HOSTNAME,KAFKA_USERNAME,KAFKA_PASSWORD,KAFKA_VPC,KAFKA_VPC_IP,KAFKA_VPC_PORT,KAFKA_SASL_SCRAM_USERNAME,KAFKA_SASL_SCRAM_PASSWORD,KAFKA_SASL_SCRAM_SERVER,KAFKA_SASL_SCRAM_PORT,KAFKA_SSL_SERVER,KAFKA_SSL_PORT,KAFKA_SSL_CERT",
            'driver' => 'app\drivers\karafka\CloudKarafka',
            'driver_params' => json_encode([
                'endpoint' => 'https://customer.cloudkarafka.com/api',
                'api_key' => '85c146d6-2322-4e69-9019-cb51d43ce2da'
            ]),
            'parameters' => json_encode([
                'plan' => [
                    'type' => 'string',
                    'default' => 'duck'
                ],
                'region' => [
                    'type' => 'string',
                    'default' => 'amazon-web-services::us-east-1'
                ],
            ]),
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function down()
    {
        echo "m180510_233250_seed_add_kafka_component_ cannot be reverted.\n";

        return false;
    }
    
}
