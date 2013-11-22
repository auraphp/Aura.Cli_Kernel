<?php
namespace Aura\Cli_Kernel;

use Aura\Cli\Status;
use Aura\Project_Kernel\ProjectKernel;
use Exception;

class CliKernel extends ProjectKernel
{
    public function __invoke()
    {
        parent::__invoke();
        
        $context    = $this->di->get('cli_context');
        $stdio      = $this->di->get('cli_stdio');
        $dispatcher = $this->di->get('cli_dispatcher');
        
        // get the command name
        $name = $context->argv->shift();
        
        // is there a command name specified?
        if (! $name) {
            $stdio->errln('No command specified.');
            return Status::USAGE;
        }
        
        // does the command exist?
        if (! $dispatcher->hasObject($name)) {
            $stdio->errln("Command '$name' not recognized.");
            return Status::UNAVAILABLE;
        }
        
        // dispatch to the command
        try {
            $result = $dispatcher->__invoke($name);
            return (int) $result;
        } catch (Exception $e) {
            $stdio->errln($e->getMessage());
            return Status::FAILURE;
        }
    }
}
