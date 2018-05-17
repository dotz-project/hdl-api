<?php

use yii\db\Migration;

/**
 * Class m180510_230420_seed_add_kubernetes_component_
 */
class m180510_230420_seed_add_kubernetes_component_ extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
           
        $this->insert('components', [
            'id' => 1,
            'name' => 'Kubernetes',
            'avatar' => 'kubernetes.png',
            'type' => '3',
            'description' => "O Kubernetes é um sistema de código aberto para automatizar a implantação, o dimensionamento e o gerenciamento de aplicativos em contêiner.",
            'keys' => "",
            'driver' => 'app\drivers\kubernetes\KubernetesCluster',
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
            'status' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function down()
    {
        echo "m180510_230420_seed_add_kubernetes_component_ cannot be reverted.\n";

        return false;
    }
    
}
