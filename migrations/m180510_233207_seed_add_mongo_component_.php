<?php

use yii\db\Migration;
use Ramsey\Uuid\Uuid;
/**
 * Class m180510_233207_seed_add_mongo_component_
 */
class m180510_233207_seed_add_mongo_component_ extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->insert('components', [
            'id' => Uuid::uuid4(),
            'name' => 'MongoDB',
            'avatar' => 'data:image/png;base64,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',
            'type' => '3',
            'description' => "MongoDB é um software de banco de dados orientado a documentos livre, de código aberto e multiplataforma.",
            'keys' => "MONGO_DB_CONNECT_STRING",
            'driver' => 'app\drivers\mongodb\MongoDbAtlas',
            'driver_params' => json_encode([
                'endpoint' => 'https://cloud.mongodb.com/api',
                'username' => "marcos.borges@dotz.com",
                'api_key' => "9f0ef12f-cd9a-4306-a611-7292db0cece3",
                "group" => "59d7dffc3b34b9115c0d06f5"
            ]),
            'parameters' => json_encode([
                'mongoDBMajorVersion' => [
                    'type' => 'options',
                    'data' => [
                        [
                            'key' => '3.4',
                            'val' => '3.4'
                        ],[
                            'key' => '3.6',
                            'val' => '3.6'
                        ],
                    ],
                    'default' => '3.4'
                ],
                'numShards' => [
                    'type' => 'integer',
                    'default' => '1'
                ],
                'replicationFactor' => [
                    'type' => 'integer',
                    'default' => '1'
                ],
                'providerSettings.providerName' => [
                    'type' => 'string',
                    'default' => 'GCP'
                ],
                'providerSettings.regionName' => [
                    'type' => 'string',
                    'default' => 'EASTERN_US'
                ],
                'providerSettings.instanceSizeName' => [
                    'type' => 'string',
                    'default' => 'M10'
                ],
                'diskSizeGB' => [
                    'type' => 'integer',
                    'default' => '40'
                ],
                'backupEnabled' => [
                    'type' => 'boolean',
                    'default' => true
                ],
            ]),
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function down()
    {
        echo "m180510_233207_seed_add_mongo_component_ cannot be reverted.\n";

        return false;
    }
    
}
