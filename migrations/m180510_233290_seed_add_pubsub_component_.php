<?php

use yii\db\Migration;
use Ramsey\Uuid\Uuid;

/**
 * Class m180510_233290_seed_add_ampq_component_
 */
class m180510_233290_seed_add_pubsub_component_ extends Migration
{
    public function up()
    {
        $this->insert('components', [
            'id' => Uuid::uuid4(),
            'name' => 'PUBSUB',
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
        echo "m180510_233290_seed_add_pubsub_component_ cannot be reverted.\n";

        return false;
    }
    
}
