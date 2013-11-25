<?php
/**
 * 
 * This file is part of Aura for PHP.
 * 
 * @package Aura.Cli_Kernel
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\Cli_Kernel;

use Aura\Cli\Status;
use Aura\Project_Kernel\ProjectKernel;
use Exception;

/**
 * 
 * A kernel for Aura CLI projects.
 * 
 * @package Aura.Cli_Kernel
 * 
 */
class CliKernel extends ProjectKernel
{
    /**
     * 
     * Invokes the kernel (i.e., runs it).
     * 
     * @return int The exit code.
     * 
     */
    public function __invoke()
    {
        parent::__invoke();
        
        $context    = $this->di->get('cli_context');
        $stdio      = $this->di->get('cli_stdio');
        $dispatcher = $this->di->get('cli_dispatcher');
        
        // get the params for the dispatcher
        $params = $context->argv->get();
        
        // strip the console script name
        array_shift($params);
        
        // strip the command name, and replace as a named param
        $command = array_shift($params);
        
        // is there a command name specified?
        if (! $command) {
            $stdio->errln('No command specified.');
            return Status::USAGE;
        }
        
        // does the command exist?
        if (! $dispatcher->hasObject($command)) {
            $stdio->errln("Command '{$command}' not recognized.");
            return Status::UNAVAILABLE;
        }
        
        // place the command back as a named param; the rest remain as
        // sequential params.
        $params['command'] = $command;
        
        // dispatch to the command
        try {
            $result = $dispatcher->__invoke($params);
            return (int) $result;
        } catch (Exception $e) {
            $stdio->errln($e->getMessage());
            return Status::FAILURE;
        }
    }
}
