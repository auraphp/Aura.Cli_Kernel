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

use Aura\Cli\Context;
use Aura\Cli\Status;
use Aura\Cli\Stdio;
use Aura\Dispatcher\Dispatcher;
use Exception;
use Monolog\Logger;

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
        Context $context,
        Stdio $stdio,
        Dispatcher $dispatcher,
        Logger $logger
    ) {
        $this->context = $context;
        $this->stdio = $stdio;
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
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
        $script = array_shift($params);
        $this->logger->debug(__METHOD__ . " script: $script");
        
        // strip the command name, and replace as a named param
        $command = array_shift($params);
        
        // is there a command name specified?
        if (! $command) {
            $this->logger->error(__METHOD__ . ' no command specified');
            $this->stdio->errln('No command specified.');
            return Status::USAGE;
        }
        
        // does the command exist?
        if (! $this->dispatcher->hasObject($command)) {
            $this->logger->error(__METHOD__ . " command '{$command}' not recognized");
            $this->stdio->errln("Command '{$command}' not recognized.");
            return Status::UNAVAILABLE;
        }
        
        // place the command back as a named param; the rest remain as
        // sequential params.
        $params['command'] = $command;
        
        // dispatch to the command
        try {
            $this->logger->debug(__METHOD__ . " command: $command", $params);
            $result = $this->dispatcher->__invoke($params);
            return (int) $result;
        } catch (Exception $e) {
            $message = $e->getMessage();
            $this->logger->error(__METHOD__ . " failure: $message");
            $this->stdio->errln($e->getMessage());
            return Status::FAILURE;
        }
    }
}
