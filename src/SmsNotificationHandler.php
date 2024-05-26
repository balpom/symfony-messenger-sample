<?php

namespace Balpom\SymfonyMessengerSample;

use Balpom\SymfonyMessengerSample\SmsNotification;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SmsNotificationHandler implements MessageHandlerInterface
{
    public function __invoke(SmsNotification $message)
    {
        $sms = $message->getContent();
        $log = '';
        $log .= $out = time() . ' Sending SMS:"' . $sms . '"' . PHP_EOL;
        echo $out;
        sleep(rand(1, 4));
        $log .= $out = time() . ' SMS "' . $sms . '" sended!' . PHP_EOL;
        echo $out;

        //file_put_contents('log.txt', $log, FILE_APPEND);
    }
}
