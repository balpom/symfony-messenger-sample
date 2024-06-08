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

$message = 'Simple message';
$total = 1000;
echo 'Start ' . $total . ' messages adding...' . PHP_EOL;
$start = microtime(true);
for ($i = 1; $i <= $total; $i++) {
    $sms = new SmsNotification($message);
    $bus->dispatch($sms, [new BusNameStamp($busName)]);
}
echo (microtime(true) - $start) . ' sec' . PHP_EOL;
