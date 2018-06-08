<?php

return [
    'class' => \mikemadisonweb\rabbitmq\Configuration::class,
    'connections' => [
        [
            // You can pass these parameters as a single `url` option: https://www.rabbitmq.com/uri-spec.html
            'host' => getenv('RABBIT_HOSTNAME'),
            'port' => getenv('RABBIT_PORT'),
            'user' => getenv('RABBIT_USERNAME'),
            'password' => getenv('RABBIT_PASSWORD'),
            'vhost' => getenv('RABBIT_VHOST'),
        ]
    ],
    'exchanges' => [
        [
            'name' => 'ORCHID',
            'type' => 'direct'
        ],[
            'name' => 'ORCHID_RETRY',
            'type' => 'direct'
        ],
    ],
    'queues' => [
        //KUBERNET
        [
            'name' => 'KUBERNET.CLUSTER.REQUEST', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'KUBERNET.CLUSTER.REQUEST.RETRY',
            ]),
        ],[
            'name' => 'KUBERNET.CLUSTER.REQUEST.RETRY', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'KUBERNET.CLUSTER.REQUEST',
                'x-message-ttl' => 30000
            ])
        ],[
            'name' => 'KUBERNET.CLUSTER.PROCESS', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'KUBERNET.CLUSTER.PROCESS.RETRY',
            ]),
        ],[
            'name' => 'KUBERNET.CLUSTER.PROCESS.RETRY', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'KUBERNET.CLUSTER.PROCESS',
                'x-message-ttl' => 10000
            ])
        ],
        
        //MONGO ATLAS
        [
            'name' => 'MONGODBATLAS.CLUSTER.REQUEST', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'MONGODBATLAS.CLUSTER.REQUEST.RETRY',
            ]),
        ],[
            'name' => 'MONGODBATLAS.CLUSTER.REQUEST.RETRY', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'MONGODBATLAS.CLUSTER.REQUEST',
                'x-message-ttl' => 30000
            ])
        ],[
            'name' => 'MONGODBATLAS.CLUSTER.PROCESS', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'MONGODBATLAS.CLUSTER.PROCESS.RETRY',
            ]),
        ],[
            'name' => 'MONGODBATLAS.CLUSTER.PROCESS.RETRY', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'MONGODBATLAS.CLUSTER.PROCESS',
                'x-message-ttl' => 10000
            ])
        ],

        //ELASTIC
        [
            'name' => 'ELASTIC.CLUSTER.REQUEST', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'ELASTIC.CLUSTER.REQUEST.RETRY',
            ]),
        ],[
            'name' => 'ELASTIC.CLUSTER.REQUEST.RETRY', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'ELASTIC.CLUSTER.REQUEST',
                'x-message-ttl' => 30000
            ])
        ],[
            'name' => 'ELASTIC.CLUSTER.PROCESS', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'ELASTIC.CLUSTER.PROCESS.RETRY',
            ]),
        ],[
            'name' => 'ELASTIC.CLUSTER.PROCESS.RETRY', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'ELASTIC.CLUSTER.PROCESS',
                'x-message-ttl' => 10000
            ])
        ],

        //AMQP
        [
            'name' => 'AMQP.CLUSTER.REQUEST', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'AMQP.CLUSTER.REQUEST.RETRY',
            ]),
        ],[
            'name' => 'AMQP.CLUSTER.REQUEST.RETRY', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'AMQP.CLUSTER.REQUEST',
                'x-message-ttl' => 30000
            ])
        ],[
            'name' => 'AMQP.CLUSTER.PROCESS', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'AMQP.CLUSTER.PROCESS.RETRY',
            ]),
        ],[
            'name' => 'AMQP.CLUSTER.PROCESS.RETRY', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'AMQP.CLUSTER.PROCESS',
                'x-message-ttl' => 10000
            ])
        ],

        //KARAFKA
        [
            'name' => 'KARAFKA.CLUSTER.REQUEST', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'KARAFKA.CLUSTER.REQUEST.RETRY',
            ]),
        ],[
            'name' => 'KARAFKA.CLUSTER.REQUEST.RETRY', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'KARAFKA.CLUSTER.REQUEST',
                'x-message-ttl' => 30000
            ])
        ],[
            'name' => 'KARAFKA.CLUSTER.PROCESS', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'KARAFKA.CLUSTER.PROCESS.RETRY',
            ]),
        ],[
            'name' => 'KARAFKA.CLUSTER.PROCESS.RETRY', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'KARAFKA.CLUSTER.PROCESS',
                'x-message-ttl' => 10000
            ])
        ],
        //COMPONENTS        
        [
            'name' => 'COMPONENTS.REQUEST', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'COMPONENTS.REQUEST.RETRY',
            ]),
        ],[
            'name' => 'COMPONENTS.REQUEST.RETRY', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'COMPONENTS.REQUEST',
                'x-message-ttl' => 30000
            ])
        ],[
            'name' => 'COMPONENTS.PROCESS', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'COMPONENTS.PROCESS.RETRY',
            ]),
        ],[
        'name' => 'COMPONENTS.PROCESS.RETRY', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'COMPONENTS.PROCESS',
                'x-message-ttl' => 10000
            ])
        ],
        //JENKINS
        [
            'name' => 'JENKINS.CREATE.JOB', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'JENKINS.CREATE.JOB.RETRY',
            ]),
        ],[
            'name' => 'JENKINS.CREATE.JOB.RETRY', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'JENKINS.CREATE.JOB',
                'x-message-ttl' => 30000
            ])
        ],[
            'name' => 'JENKINS.BUILD', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'JENKINS.BUILD.RETRY',
            ]),
        ],[
            'name' => 'JENKINS.BUILD.RETRY', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'JENKINS.BUILD',
                'x-message-ttl' => 30000
            ])
        ],[
            'name' => 'JENKINS.BUILD.PROCESS', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'JENKINS.BUILD.PROCESS.RETRY',
            ]),
        ],[
            'name' => 'JENKINS.BUILD.PROCESS.RETRY', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'JENKINS.BUILD.PROCESS',
                'x-message-ttl' => 10000
            ])
        ]
    ],
    'bindings' => [
        ['queue' => 'KUBERNET.CLUSTER.REQUEST',       'exchange' => 'ORCHID',       'routing_keys' => ['KUBERNET.CLUSTER.REQUEST']],
        ['queue' => 'KUBERNET.CLUSTER.REQUEST.RETRY', 'exchange' => 'ORCHID_RETRY', 'routing_keys' => ['KUBERNET.CLUSTER.REQUEST.RETRY']],
        ['queue' => 'KUBERNET.CLUSTER.PROCESS',       'exchange' => 'ORCHID',       'routing_keys' => ['KUBERNET.CLUSTER.PROCESS']],
        ['queue' => 'KUBERNET.CLUSTER.PROCESS.RETRY', 'exchange' => 'ORCHID_RETRY', 'routing_keys' => ['KUBERNET.CLUSTER.PROCESS.RETRY']],
        
        ['queue' => 'MONGODBATLAS.CLUSTER.REQUEST',       'exchange' => 'ORCHID',       'routing_keys' => ['MONGODBATLAS.CLUSTER.REQUEST']],
        ['queue' => 'MONGODBATLAS.CLUSTER.REQUEST.RETRY', 'exchange' => 'ORCHID_RETRY', 'routing_keys' => ['MONGODBATLAS.CLUSTER.REQUEST.RETRY']],
        ['queue' => 'MONGODBATLAS.CLUSTER.PROCESS',       'exchange' => 'ORCHID',       'routing_keys' => ['MONGODBATLAS.CLUSTER.PROCESS']],
        ['queue' => 'MONGODBATLAS.CLUSTER.PROCESS.RETRY', 'exchange' => 'ORCHID_RETRY', 'routing_keys' => ['MONGODBATLAS.CLUSTER.PROCESS.RETRY']],
        
        ['queue' => 'AMQP.CLUSTER.REQUEST',       'exchange' => 'ORCHID',       'routing_keys' => ['AMQP.CLUSTER.REQUEST']],
        ['queue' => 'AMQP.CLUSTER.REQUEST.RETRY', 'exchange' => 'ORCHID_RETRY', 'routing_keys' => ['AMQP.CLUSTER.REQUEST.RETRY']],
        ['queue' => 'AMQP.CLUSTER.PROCESS',       'exchange' => 'ORCHID',       'routing_keys' => ['AMQP.CLUSTER.PROCESS']],
        ['queue' => 'AMQP.CLUSTER.PROCESS.RETRY', 'exchange' => 'ORCHID_RETRY', 'routing_keys' => ['AMQP.CLUSTER.PROCESS.RETRY']],
        
        ['queue' => 'KARAFKA.CLUSTER.REQUEST',       'exchange' => 'ORCHID',       'routing_keys' => ['KARAFKA.CLUSTER.REQUEST']],
        ['queue' => 'KARAFKA.CLUSTER.REQUEST.RETRY', 'exchange' => 'ORCHID_RETRY', 'routing_keys' => ['KARAFKA.CLUSTER.REQUEST.RETRY']],
        ['queue' => 'KARAFKA.CLUSTER.PROCESS',       'exchange' => 'ORCHID',       'routing_keys' => ['KARAFKA.CLUSTER.PROCESS']],
        ['queue' => 'KARAFKA.CLUSTER.PROCESS.RETRY', 'exchange' => 'ORCHID_RETRY', 'routing_keys' => ['KARAFKA.CLUSTER.PROCESS.RETRY']],
        
        ['queue' => 'ELASTIC.CLUSTER.REQUEST',       'exchange' => 'ORCHID',       'routing_keys' => ['ELASTIC.CLUSTER.REQUEST']],
        ['queue' => 'ELASTIC.CLUSTER.REQUEST.RETRY', 'exchange' => 'ORCHID_RETRY', 'routing_keys' => ['ELASTIC.CLUSTER.REQUEST.RETRY']],
        ['queue' => 'ELASTIC.CLUSTER.PROCESS',       'exchange' => 'ORCHID',       'routing_keys' => ['ELASTIC.CLUSTER.PROCESS']],
        ['queue' => 'ELASTIC.CLUSTER.PROCESS.RETRY', 'exchange' => 'ORCHID_RETRY', 'routing_keys' => ['ELASTIC.CLUSTER.PROCESS.RETRY']],
        
        ['queue' => 'COMPONENTS.REQUEST',             'exchange' => 'ORCHID',       'routing_keys' => ['COMPONENTS.REQUEST']],
        ['queue' => 'COMPONENTS.REQUEST.RETRY',       'exchange' => 'ORCHID_RETRY', 'routing_keys' => ['COMPONENTS.REQUEST.RETRY']],
        ['queue' => 'COMPONENTS.PROCESS',             'exchange' => 'ORCHID',       'routing_keys' => ['COMPONENTS.PROCESS']],
        ['queue' => 'COMPONENTS.PROCESS.RETRY',       'exchange' => 'ORCHID_RETRY', 'routing_keys' => ['COMPONENTS.PROCESS.RETRY']],
        
        ['queue' => 'JENKINS.CREATE.JOB',             'exchange' => 'ORCHID',       'routing_keys' => ['JENKINS.CREATE.JOB']],
        ['queue' => 'JENKINS.CREATE.JOB.RETRY',       'exchange' => 'ORCHID_RETRY', 'routing_keys' => ['JENKINS.CREATE.JOB.RETRY']],
        
        ['queue' => 'JENKINS.BUILD',                  'exchange' => 'ORCHID',       'routing_keys' => ['JENKINS.BUILD']],
        ['queue' => 'JENKINS.BUILD.RETRY',            'exchange' => 'ORCHID_RETRY', 'routing_keys' => ['JENKINS.BUILD.RETRY']],
        ['queue' => 'JENKINS.BUILD.PROCESS',          'exchange' => 'ORCHID',       'routing_keys' => ['JENKINS.BUILD.PROCESS']],
        ['queue' => 'JENKINS.BUILD.PROCESS.RETRY',    'exchange' => 'ORCHID_RETRY', 'routing_keys' => ['JENKINS.BUILD.PROCESS.RETRY']]
    ],
    'producers' => [
        [ 'name' => 'KUBERNET.CLUSTER.REQUEST.PRODUCER' ],
        [ 'name' => 'KUBERNET.CLUSTER.PROCESS.PRODUCER' ],
        [ 'name' => 'MONGODBATLAS.CLUSTER.REQUEST.PRODUCER' ],
        [ 'name' => 'MONGODBATLAS.CLUSTER.PROCESS.PRODUCER' ],
        [ 'name' => 'AMQP.CLUSTER.REQUEST.PRODUCER' ],
        [ 'name' => 'AMQP.CLUSTER.PROCESS.PRODUCER' ],
        [ 'name' => 'KARAFKA.CLUSTER.REQUEST.PRODUCER' ],
        [ 'name' => 'KARAFKA.CLUSTER.PROCESS.PRODUCER' ],
        [ 'name' => 'ELASTIC.CLUSTER.REQUEST.PRODUCER' ],
        [ 'name' => 'ELASTIC.CLUSTER.PROCESS.PRODUCER' ],
        [ 'name' => 'COMPONENTS.REQUEST.PRODUCER' ],
        [ 'name' => 'COMPONENTS.PROCESS.PRODUCER' ],
        [ 'name' => 'JENKINS.CREATE.JOB.PRODUCER' ],
        [ 'name' => 'JENKINS.BUILD.PRODUCER' ],
        [ 'name' => 'JENKINS.BUILD.PROCESS.PRODUCER' ],
    ],
    'consumers' => [
        
        ['name' => 'KUBERNET.CLUSTER.REQUEST.CONSUMER','callbacks' => ['KUBERNET.CLUSTER.REQUEST' => \app\consumers\KubernetClusterRequestConsumer::class]], 
        ['name' => 'KUBERNET.CLUSTER.PROCESS.CONSUMER','callbacks' => ['KUBERNET.CLUSTER.PROCESS' => \app\consumers\KubernetClusterProcessConsumer::class]], 
     
        ['name' => 'MONGODBATLAS.CLUSTER.REQUEST.CONSUMER','callbacks' => ['MONGODBATLAS.CLUSTER.REQUEST' => \app\consumers\MongoDBAtlasClusterRequestConsumer::class]], 
        ['name' => 'MONGODBATLAS.CLUSTER.PROCESS.CONSUMER','callbacks' => ['MONGODBATLAS.CLUSTER.PROCESS' => \app\consumers\MongoDBAtlasClusterProcessConsumer::class]], 
     
        ['name' => 'AMQP.CLUSTER.REQUEST.CONSUMER','callbacks' => ['AMQP.CLUSTER.REQUEST' => \app\consumers\AMQPClusterRequestConsumer::class]], 
        ['name' => 'AMQP.CLUSTER.PROCESS.CONSUMER','callbacks' => ['AMQP.CLUSTER.PROCESS' => \app\consumers\AMQPClusterProcessConsumer::class]], 
     
        ['name' => 'ELASTIC.CLUSTER.REQUEST.CONSUMER','callbacks' => ['ELASTIC.CLUSTER.REQUEST' => \app\consumers\ElasticClusterRequestConsumer::class]], 
        ['name' => 'ELASTIC.CLUSTER.PROCESS.CONSUMER','callbacks' => ['ELASTIC.CLUSTER.PROCESS' => \app\consumers\ElasticClusterProcessConsumer::class]], 
     
        ['name' => 'KARAFKA.CLUSTER.REQUEST.CONSUMER','callbacks' => ['KARAFKA.CLUSTER.REQUEST' => \app\consumers\KarafkaClusterRequestConsumer::class]], 
        ['name' => 'KARAFKA.CLUSTER.PROCESS.CONSUMER','callbacks' => ['KARAFKA.CLUSTER.PROCESS' => \app\consumers\KarafkaClusterProcessConsumer::class]], 
     
        ['name' => 'COMPONENTS.REQUEST.CONSUMER', 'callbacks' => ['COMPONENTS.REQUEST' => \app\consumers\ComponentsRequestConsumer::class]],
        ['name' => 'COMPONENTS.PROCESS.CONSUMER', 'callbacks' => ['COMPONENTS.PROCESS' => \app\consumers\ComponentsProcessConsumer::class]], 
        
        ['name' => 'JENKINS.CREATE.JOB.CONSUMER','callbacks' => ['JENKINS.CREATE.JOB' => \app\consumers\JenkinsCreateJobConsumer::class]],
        ['name' => 'JENKINS.BUILD.CONSUMER','callbacks' => ['JENKINS.BUILD' => \app\consumers\JenkinsBuildConsumer::class]],
        ['name' => 'JENKINS.BUILD.PROCESS.CONSUMER', 'callbacks' => ['JENKINS.BUILD.PROCESS' => \app\consumers\JenkinsBuildProcessConsumer::class]]
    
    ],
    'on before_consume' => function ($event) {
        if (isset(\Yii::$app->db)) {
            $db = \Yii::$app->db;
            if ($db->getIsActive()) {
                $db->close();
            }
            $db->open();
        }
    },
    'logger' => [
        'category' => 'application',
        'print_console' => false,
        'system_memory' => false
    ]
];