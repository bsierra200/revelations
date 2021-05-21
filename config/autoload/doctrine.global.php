<?php
return [
    'doctrine' => [
        'driver' => [
            'orm_logger' => [
                'class' => \Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain::class,
                'drivers' => [
                    'Logger\Entity' => 'logger_entity',
                ],
            ],
            'logger_entity' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => __DIR__ . '/doctrine',
            ],
        ],
    ],
];