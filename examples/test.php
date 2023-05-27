<?php

use Jundayw\Frames\WebSocket;

include './../../../autoload.php';

$encode = 'hello';
$encode = '从服务器发往客户端的数据也是同样的数据帧，但是从服务器发送到客户端的数据帧不需要掩码的。我们自己需要去生成数据帧，解析数据帧的时候我们需要分片。';

$client = new WebSocket();
// Client pack Server mask true
$client->pack($encode, 0x1, true, true);

var_dump('Client pack Server:', $encode);
var_dump(bin2hex($client->getPayload()));

echo PHP_EOL;
$server = new WebSocket();
var_dump('Server unpack Client:', $server->unpack($client->getPayload())->getPayloadData());
var_dump($server->unpack($client->getPayload()));

echo PHP_EOL;
$client = new WebSocket();
// Server pack Client mask false
$client->pack($encode, 0x1, true, false);

var_dump('Server pack Client:', $encode);
var_dump(bin2hex($client->getPayload()));

echo PHP_EOL;
$server = new WebSocket();
var_dump('Client unpack Server:', $server->unpack($client->getPayload())->getPayloadData());
var_dump($server->unpack($client->getPayload()));