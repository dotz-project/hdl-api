<?php

use yii\db\Migration;
use Ramsey\Uuid\Uuid;

/**
 * Class m180510_233290_seed_add_ampq_component_
 */
class m180510_233295_seed_add_datastore_component_ extends Migration
{
    public function up()
    {
        $this->insert('components', [
            'id' => Uuid::uuid4(),
            'name' => 'DATASTORE',
            'avatar' => 'data:image/png;base64,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',
            'type' => '3',
            'description' => "....",
            'keys' => "",
            'driver' => 'app\drivers\gc\PubSUb',
            'driver_params' => json_encode([
                "type" => "service_account",
                "project_id" => "dotzcloud-orchestrator",
                "private_key_id" => "08021a10a4f022bce965262c419682deb9a5965b",
                "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQDGSmAEr2WciT16\nlKBCtLY+XW7nZjFEyeBGRuqw6IvjlLCnqM0J9KORyYKceorzDmVbgXf0TFqJM7s7\neFw42d1bqQMLbU6zfNkfh8r+lxqWMTpmp4rg4GInlm8AgAq0j6rO7jwdLDIfWqmh\nxw5Ht1pS6OHCiLkWln1/ICE7SnF06e1HCoDjSS+/njrBXLZ/SkZH8XSn3XLBlKp/\nVI7LLBKfWO5k1SEWs6SYPDg1lZAvwOcZIrImHs2xs/IzOUv5+vzLqchO4Ghuzqol\noZ/Hr5bw6/+BqblRaiIiGOtgr4fF1f/JDacdbqwfy5VYZHhlF/MRg/yYGn2GIEw+\n2j9SvafDAgMBAAECggEAFkOalVwWRiW4s+9FMUg2IDmAbtXwGT+rgWiMCHIL5vE2\nhpSy9Pe1d/SOyEAQIutOYSBf1hvOnJIk5zAD9tQrgROwhlNvH0MY4+vs1Hol35BG\ndOlB9C7thnFjJES8LvrknFhugn4IYLHe0w3VIiNJrn58fsm4MnuSOrhiUtRwecZp\norW1sS2zKBINLgwPQ6mJ/79420Z49PC0G7aymGIytForYne0ubLzV1dKICdMTXwJ\nHK0Rl4+bWKkMCm1xWjqDDbuyUFi5TWv1cMjKakG81fO8iXJahuvASpm9tcbFN9sn\nw6ve3L4GXfveVWfQ9sRgSbZhYz3d0XVxTPpU5PVvOQKBgQDn2ij3sKDN0AOxffYO\n3WahlupfodkzE0QZKrIHjWgCITfQsFDAGzHrLblq0DIl6PQkuHow3AcGTDt3vmrt\nMgaxdo5uK1VyszA2shClP32cQtrToLbnP7Uwrzsec1I3fvtR7lz3vD+adegxbpOb\nm84JzGBdma2a+sH0sQJZMtGgmwKBgQDa8V2U/tedhXP7ATj/+R0lJHpw7eVY8PdZ\nCzNY2KyYgFNTpE1MzsdI7kou6d7Dd69bz2eqRGXZptOnt/NpWebbCkhrp2lvUVy0\nB7FcZM8NLnS49kLSBlsOfGFY0OPLGO+rprE5ICGMpRixx9I01gtxw65KEWTu8/jZ\n903A473j+QKBgFmVF4MsgdVD7ElmK+5uaTtCPmiY9n0fko5Oyt7UKj24bAJ8PZcc\nats9nYjBzL6NFdgiV0QNn8E1Lz0DG/lt/NwQvNOgdrGMvrJDdt8CexwnULm4PTN4\nkB+xdRw83qWGsMBlYxJtb/3UVNTUrr/PIXrztsAzorpg7RQIWKnyUsDFAoGAcJ0H\nYnS2GJqk7i1R0S+kEw+xPtbOSbIft5gwP/mqxQH0Wig/bktJa7TNSVYXs3ijJNkI\nXCa0MIBDi5ftRnnsaSvf1ieU7qHtydfPHVPov8HuXZilA/wq58eIFrPTBwr7c9HY\nhCaHuxzMF91MvMp/tnNM+WH7BYFNtWcjghxh9pkCgYA8e7ss0gVTPhzjvC8WS5zP\nD9q4Z5WV0QctK+UNXyUrPiWUVseUD94eeJ9mOJujERL3BR6aYD/1SOe3VZs9O7rp\nR0qSmZ8V5SQosO3d6ZyxsWNrG4h5P7GsvMmFhApmjsm6RCHbMJy0gz4TtNpehJ1K\nVrOAhQdjW4F6FxVHzAhQeg==\n-----END PRIVATE KEY-----\n",
                "client_email" => "orchestrator@dotzcloud-orchestrator.iam.gserviceaccount.com",
                "client_id" => "112154624872420579903",
                "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
                "token_uri" => "https://accounts.google.com/o/oauth2/token",
                "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
                "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/orchestrator%40dotzcloud-orchestrator.iam.gserviceaccount."
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
        echo "m180510_233295_seed_add_datastore_component_ cannot be reverted.\n";

        return false;
    }
    
}
