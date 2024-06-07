<?php

namespace Balpom\SymfonyMessengerSample;

require __DIR__ . "/../vendor/autoload.php";

use DI\ContainerBuilder;
use Symfony\Component\Messenger\Stamp\BusNameStamp;

$containerBuilder = new ContainerBuilder;
$containerBuilder->addDefinitions(__DIR__ . '/../config/dependencies.php');
$container = $containerBuilder->build();

$busName = 'message-bus';
$bus = $container->get($busName);

$messages = ['First message', 'Second message', 'Third message', 'Fourth message', 'LAST message'];
foreach ($messages as $message) {
    $message = date("Y-m-d H:i:s") . ' ' . $message;
    $sms = new SmsNotification($message);
    sleep(rand(1, 2));
    $bus->dispatch($sms, [new BusNameStamp($busName)]);
    //$bus->dispatch($sms);
    echo 'Added to query SMS: ' . $message . PHP_EOL;
}
