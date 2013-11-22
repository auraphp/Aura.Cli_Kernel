<?php
/**
 * Services
 */
$di->set('cli_context', $di->lazyNew('Aura\Cli\Context'));
$di->set('cli_stdio', $di->lazyNew('Aura\Cli\Stdio'));
$di->set('cli_dispatcher', $di->lazyNew('Aura\Dispatcher\Dispatcher'));
