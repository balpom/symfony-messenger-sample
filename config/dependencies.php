<?php

use Balpom\SymfonyMessengerSample\SmsNotification;
use Balpom\SymfonyMessengerSample\SmsNotificationHandler;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Messenger\Bridge\Doctrine\Transport\Connection;
use Symfony\Component\Messenger\Bridge\Doctrine\Transport\DoctrineReceiver;
use Symfony\Component\Messenger\Bridge\Doctrine\Transport\DoctrineTransport;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Messenger\Middleware\SendMessageMiddleware;
use Symfony\Component\Messenger\Transport\Sender\SendersLocator;
use Symfony\Component\Messenger\Transport\Serialization\PhpSerializer;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

return [
    CacheItemPoolInterface::class => function () {
        return new FilesystemAdapter('test_namespace', 10, __DIR__ . '/../var/cache');
    },
    // Need for DoctrineTransport autowiring.
    SerializerInterface::class => function () {
        return new PhpSerializer;
    },
    Connection::class => function () {
        $dsnParser = new DsnParser();
        $connectionParams = $dsnParser
                ->parse('pdo-sqlite:////var/www/symfony/messenger/data/queue.sqlite');
        $connection = DriverManager::getConnection($connectionParams);

        $configuration = []; // See DEFAULT_OPTIONS in DoctrineConnection.

        return new Connection($configuration, $connection);
    },
    'doctrine-async' => function (ContainerInterface $container) {
        return new DoctrineReceiver($container->get(Connection::class));
    },
    'message-bus' => function (ContainerInterface $container) {
        $handler = new SmsNotificationHandler($container);
        return new MessageBus([
    new SendMessageMiddleware(
            new SendersLocator([
                SmsNotification::class => [DoctrineTransport::class]
                    ], $container)
    ),
    new HandleMessageMiddleware(
            new HandlersLocator([
                SmsNotification::class => [$handler],
                    ])
    )
        ]);
    }
];
