<?php
/**
 * 
 * This file is part of Aura for PHP.
 * 
 * @package Aura.Cli_Kernel
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 * @var Aura\Di\Container $di The DI container.
 * 
 */

/**
 * Services
 */
$di->set('cli_context', $di->lazyNew('Aura\Cli\Context'));
$di->set('cli_stdio', $di->lazyNew('Aura\Cli\Stdio'));
$di->set('cli_dispatcher', $di->lazyNew('Aura\Dispatcher\Dispatcher'));

/**
 * Aura\Cli_Kernel\CliKernel
 */
$di->params['Aura\Cli_Kernel\CliKernel'] = array(
    'context' => $di->lazyGet('cli_context'),
    'stdio' => $di->lazyGet('cli_stdio'),
    'dispatcher' => $di->lazyGet('cli_dispatcher'),
    'logger' => $di->lazyGet('logger'),
);
