<?php

require 'vendor/autoload.php';

$keys = \Minishlink\WebPush\VAPID::createVapidKeys();

echo 'Public: ' . $keys['publicKey'] . PHP_EOL;
echo 'Private: ' . $keys['privateKey'] . PHP_EOL;