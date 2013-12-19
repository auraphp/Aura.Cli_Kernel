<?php
$dispatcher = $di->get('cli_dispatcher');
$context = $di->get('cli_context');
$stdio   = $di->get('cli_stdio');

$dispatcher->setObject('aura-integration-hello', function () use ($stdio) {
    $stdio->outln("Hello World!");
});

$dispatcher->setObject('aura-integration-exception', function () {
    throw new \Exception('mock exception');
});
