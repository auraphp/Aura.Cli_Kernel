<?php
$dispatcher = $di->get('cli_dispatcher');
$context = $di->get('cli_context');
$stdio   = $di->get('cli_stdio');

$dispatcher->setObject('aura-integration-hello', function () use ($stdio) {
    $stdio->outln("Hello World!");
});
