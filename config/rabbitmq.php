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
            'name' => 'ORCHESTRATOR',
            'type' => 'direct'
        ],[
            'name' => 'ORCHESTRATOR_RETRY',
            'type' => 'direct'
        ],
    ],
    'queues' => [
        [
            'name' => 'CLUSTER.REQUEST', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'CLUSTER.REQUEST.RETRY',
            ]),
        ],[
            'name' => 'CLUSTER.REQUEST.RETRY', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'CLUSTER.REQUEST',
                'x-message-ttl' => 30000
            ])
        ],[
            'name' => 'CLUSTER.PROCESS', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'CLUSTER.PROCESS.RETRY',
            ]),
        ],[
        'name' => 'CLUSTER.PROCESS.RETRY', 
            'durable' => true, 
            'auto_delete' => false,
            'arguments' => new \PhpAmqpLib\Wire\AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => 'CLUSTER.PROCESS',
                'x-message-ttl' => 10000
            ])
        ],[
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

    ],
    'bindings' => [
        [
            'queue' => 'CLUSTER.REQUEST',
            'exchange' => 'ORCHESTRATOR',
            'routing_keys' => ['ORCHESTRATOR.CLUSTER.REQUEST'],
        ],[
            'queue' => 'CLUSTER.REQUEST.RETRY',
            'exchange' => 'ORCHESTRATOR_RETRY',
            'routing_keys' => ['ORCHESTRATOR.CLUSTER.REQUEST.RETRY'],
        ],[
            'queue' => 'CLUSTER.PROCESS',
            'exchange' => 'ORCHESTRATOR',
            'routing_keys' => ['ORCHESTRATOR.CLUSTER.PROCESS'],
        ],[
            'queue' => 'CLUSTER.PROCESS.RETRY',
            'exchange' => 'ORCHESTRATOR_RETRY',
            'routing_keys' => ['ORCHESTRATOR.CLUSTER.PROCESS.RETRY'],
        ],[
            'queue' => 'COMPONENTS.REQUEST',
            'exchange' => 'ORCHESTRATOR',
            'routing_keys' => ['ORCHESTRATOR.COMPONENTS.REQUEST'],
        ],[
            'queue' => 'COMPONENTS.REQUEST.RETRY',
            'exchange' => 'ORCHESTRATOR_RETRY',
            'routing_keys' => ['ORCHESTRATOR.COMPONENTS.REQUEST.RETRY'],
        ],[
            'queue' => 'COMPONENTS.PROCESS',
            'exchange' => 'ORCHESTRATOR',
            'routing_keys' => ['ORCHESTRATOR.COMPONENTS.PROCESS'],
        ],[
            'queue' => 'COMPONENTS.PROCESS.RETRY',
            'exchange' => 'ORCHESTRATOR_RETRY',
            'routing_keys' => ['ORCHESTRATOR.COMPONENTS.PROCESS.RETRY'],
        ]

    ],
    'producers' => [
        [ 'name' => 'CLUSTER.REQUEST.PRODUCER' ],
        [ 'name' => 'CLUSTER.PROCESS.PRODUCER' ],
        [ 'name' => 'COMPONENTS.REQUEST.PRODUCER' ],
        [ 'name' => 'COMPONENTS.PROCESS.PRODUCER' ]
    ],
    'consumers' => [
        [
            'name' => 'COMPONENTS.REQUEST.CONSUMER',
            'callbacks' => [
                'COMPONENTS.REQUEST' => \app\consumers\ComponentsRequestConsumer::class,
            ]
        ],[
            'name' => 'COMPONENTS.PROCESS.CONSUMER',
            'callbacks' => [
                'COMPONENTS.PROCESS' => \app\consumers\ComponentsProcessConsumer::class,
            ]
        ], [
            'name' => 'CLUSTER.REQUEST.CONSUMER',
            'callbacks' => [
                'CLUSTER.REQUEST' => \app\consumers\ClusterRequestConsumer::class,
            ]
        ], [
            'name' => 'CLUSTER.PROCESS.CONSUMER',
            'callbacks' => [
                'CLUSTER.PROCESS' => \app\consumers\ClusterProcessConsumer::class,
            ]
        ],
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