<?php
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/serviceContainer.php';


$server = new Swoole\Server("0.0.0.0", 9501, SWOOLE_BASE, SWOOLE_SOCK_TCP);
$app = $containerBuilder->get('app');

// Register the function for the event `connect`
$server->on('connect', function($server, $fd) use($app) {
    echo(sprintf('client [%s]: connection', $fd));
    $server->send($fd, $app->getGreeting());
    $server->send($fd, $app->getMenu());
});

// Register the function for the event `receive`
$server->on('receive', function($server, $fd, $from_id, $data) use($app) {
    echo(sprintf("client [%d]: command [%s]\n", $fd, trim($data)));

    $app->runMenuCommand($server, $fd, $data);
    //$server->send($fd, $reply);
    $server->send($fd, "\n");
    $server->send($fd, $app->getMenu());
});

// Register the function for the event `close`
$server->on('close', function($server, $fd){
    echo(sprintf('client [%s]: close', $fd));
});

// Start the server
$server->set(array(
    'worker_num' => 1, // The number of worker processes
    'backlog' => 128, // TCP backlog connection number
));

$server->start();