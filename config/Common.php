<?php
namespace Aura\Project_Kernel\Aura\Cli_Kernel\Config;

use Aura\Di\Container;
use Aura\Project_Kernel\Config;

class Common extends Config
{
    public function define(Container $di)
    {
        $di->set('cli_context', $di->lazyNew('Aura\Cli\Context'));
        $di->set('cli_stdio', $di->lazyNew('Aura\Cli\Stdio'));
        $di->set('cli_dispatcher', $di->lazyNew('Aura\Dispatcher\Dispatcher'));

        $di->params['Aura\Cli_Kernel\CliKernel'] = array(
            'context' => $di->lazyGet('cli_context'),
            'stdio' => $di->lazyGet('cli_stdio'),
            'dispatcher' => $di->lazyGet('cli_dispatcher'),
            'logger' => $di->lazyGet('logger'),
        );
    }

    public function modify(Container $di)
    {
        $dispatcher = $di->get('cli_dispatcher');
        $dispatcher->setObjectParam('command');
    }
}
