<?php

use yii\db\Migration;

/**
 * Class m180510_233207_seed_add_mongo_component_
 */
class m180510_233207_seed_add_mongo_component_ extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->insert('components', [
            'id' => 2,
            'name' => 'MongoDB',
            'avatar' => 'mongo.png',
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
