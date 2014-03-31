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

        $di->params['Aura\Cli_Kernel\CliKernel'] = array(
            'context' => $di->lazyGet('cli_context'),
            'stdio' => $di->lazyGet('cli_stdio'),
            'dispatcher' => $di->lazyGet('cli_dispatcher')
        );
    }
}
