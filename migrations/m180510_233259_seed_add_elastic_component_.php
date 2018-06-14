<?php

use yii\db\Migration;
use Ramsey\Uuid\Uuid;

/**
 * Class m180510_233255_seed_add_ampq_component_
 */
class m180510_233259_seed_add_elastic_component_ extends Migration
{
    public function up()
    {
        //https://www.elastic.co/blog/exploring-the-api-for-elastic-cloud-enterprise
        $this->insert('components', [
            'id' => Uuid::uuid4(),
            'name' => 'ELASTIC',
            'avatar' => 'elastic.png',
            'type' => '3',
            'description' => "....",
            'keys' => "ELASTIC_HOSTNAME,ELASTIC_USERNAME,ELASTIC_PASSWORD,ELASTIC_PORT,ELASTIC_SSL",
            'driver' => 'app\drivers\elastic\Elastic',
            'driver_params' => json_encode([
                'endpoint' => 'https://www.elastic.co/api/v1/',
                'username' => "infra@dotz.com.br",
                'api_key' => "Host.cbsm@dotz53378!",
                'region' => "sa-east-1"
            ]),
            'parameters' => json_encode([
                'version' => [
                    'type' => 'string',
                    'default' => '5.3.2'
                ]
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
