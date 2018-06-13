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
            'avatar' => 'data:image/png;base64,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',
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
