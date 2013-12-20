<?php
$logger = $di->get('logger');
$dispatcher = $di->get('cli_dispatcher');
$context = $di->get('cli_context');
$stdio   = $di->get('cli_stdio');

$logger->pushHandler($di->newInstance('Monolog\Handler\NullHandler'));

$dispatcher->setObject('aura-integration-hello', function () use ($stdio) {
    $stdio->outln("Hello World!");
});

$dispatcher->setObject('aura-integration-exception', function () {
    throw new \Exception('mock exception');
});
