<?php
namespace Aura\Cli_Kernel\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    public function define(Container $di)
    {
        $di->set('aura/cli-kernel:context', $di->lazyNew('Aura\Cli\Context'));
        $di->set('aura/cli-kernel:stdio', $di->lazyNew('Aura\Cli\Stdio'));
        $di->set(
            'aura/cli-kernel:dispatcher',
            $di->lazyNew('Aura\Dispatcher\Dispatcher', array(
                'object_param' => 'command',
            )
        ));
        $di->set(
            'aura/cli-kernel:help_service',
            $di->lazyNew('Aura\Cli_Kernel\HelpService')
        );

        $di->params['Aura\Cli_Kernel\CliKernel'] = array(
            'context' => $di->lazyGet('aura/cli-kernel:context'),
            'stdio' => $di->lazyGet('aura/cli-kernel:stdio'),
            'dispatcher' => $di->lazyGet('aura/cli-kernel:dispatcher'),
            'logger' => $di->lazyGet('aura/project-kernel:logger'),
        );

        $di->params['Aura\Cli_Kernel\HelpCommand'] = array(
            'stdio' => $di->lazyGet('aura/cli-kernel:stdio'),
            'dispatcher' => $di->lazyGet('aura/cli-kernel:dispatcher'),
            'help_service' => $di->lazyGet('aura/cli-kernel:help_service'),
        );
    }

    public function modify(Container $di)
    {
        $dispatcher = $di->get('aura/cli-kernel:dispatcher');
        $dispatcher->setObject(
            'help',
            $di->lazyNew('Aura\Cli_Kernel\HelpCommand')
        );

        $help_service = $di->get('aura/cli-kernel:help_service');
        $help_service->set('help', $di->lazyNew('Aura\Cli_Kernel\HelpHelp'));
    }
}
