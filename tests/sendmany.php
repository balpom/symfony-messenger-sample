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

for ($i = 1; $i <= 2000; $i++) {
    foreach ($messages as $message) {
        $message = $i . ' --- ' . date("Y-m-d H:i:s") . ' ' . $message;
        $sms = new SmsNotification($message);
        //sleep(rand(1, 2));
        //usleep(rand(1, 10));
        $bus->dispatch($sms, [new BusNameStamp($busName)]);
        echo 'Added to query SMS: ' . $message . PHP_EOL;
    }
}
