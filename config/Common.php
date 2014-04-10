<?php
namespace Aura\Cli_Kernel\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    public function define(Container $di)
    {
        $di->set('cli_context', $di->lazyNew('Aura\Cli\Context'));
        $di->set('cli_stdio', $di->lazyNew('Aura\Cli\Stdio'));
        $di->set(
            'cli_dispatcher',
            $di->lazyNew('Aura\Dispatcher\Dispatcher', array(
                'object_param' => 'command',
            )
        ));
        $di->set(
            'cli_help_service',
            $di->lazyNew('Aura\Cli_Kernel\HelpService')
        );

        $di->params['Aura\Cli_Kernel\CliKernel'] = array(
            'context' => $di->lazyGet('cli_context'),
            'stdio' => $di->lazyGet('cli_stdio'),
            'dispatcher' => $di->lazyGet('cli_dispatcher'),
            'logger' => $di->lazyGet('logger'),
        );

        $di->params['Aura\Cli_Kernel\HelpCommand'] = array(
            'stdio' => $di->lazyGet('cli_stdio'),
            'dispatcher' => $di->lazyGet('cli_dispatcher'),
            'help_service' => $di->lazyGet('cli_help_service'),
        );
    }

    public function modify(Container $di)
    {
        $dispatcher = $di->get('cli_dispatcher');
        $dispatcher->setObject(
            'help',
            $di->lazyNew('Aura\Cli_Kernel\HelpCommand')
        );
    }
}
