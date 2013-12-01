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
use Exception;

/**
 * 
 * A kernel for Aura CLI projects.
 * 
 * @package Aura.Cli_Kernel
 * 
 */
class CliKernel
{
    public function __construct(
        $context,
        $stdio,
        $dispatcher
    ) {
        $this->context = $context;
        $this->stdio = $stdio;
        $this->dispatcher = $dispatcher;
    }
    /**
     * 
     * Invokes the kernel (i.e., runs it).
     * 
     * @return int The exit code.
     * 
     */
    public function __invoke()
    {
        // get the params for the dispatcher
        $params = $this->context->argv->get();
        
        // strip the console script name
        array_shift($params);
        
        // strip the command name, and replace as a named param
        $command = array_shift($params);
        
        // is there a command name specified?
        if (! $command) {
            $this->stdio->errln('No command specified.');
            return Status::USAGE;
        }
        
        // does the command exist?
        if (! $this->dispatcher->hasObject($command)) {
            $this->stdio->errln("Command '{$command}' not recognized.");
            return Status::UNAVAILABLE;
        }
        
        // place the command back as a named param; the rest remain as
        // sequential params.
        $params['command'] = $command;
        
        // dispatch to the command
        try {
            $result = $this->dispatcher->__invoke($params);
            return (int) $result;
        } catch (Exception $e) {
            $this->stdio->errln($e->getMessage());
            return Status::FAILURE;
        }
    }
}
