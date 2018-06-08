<?php

use yii\db\Migration;

/**
 * Class m180510_233255_seed_add_ampq_component_
 */
class m180510_233255_seed_add_amqp_component_ extends Migration
{
    public function up()
    {
        $this->insert('components', [
            'id' => 4,
            'name' => 'AMQP',
            'avatar' => 'ampq.png',
            'type' => '3',
            'description' => "....",
            'keys' => "AMQP_HOSTNAME,AMQP_USERNAME,AMQP_PASSWORD,AMQP_PORT",
            'driver' => 'app\drivers\amqp\CloudAmqp',
            'driver_params' => json_encode([
                'endpoint' => 'https://customer.cloudamqp.com/api',
                'api_key' => 'df335215-5d45-4bae-bbf1-83462cd0a5e0'
            ]),
            'parameters' => json_encode([
                'plan' => [
                    'type' => 'string',
                    'default' => 'lemur'
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
        echo "m180510_233255_seed_add_ampq_component_ cannot be reverted.\n";

        return false;
    }
    
}
