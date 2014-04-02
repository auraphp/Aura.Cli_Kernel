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
use Psr\Log\LoggerInterface;

/**
 * 
 * A kernel for Aura CLI projects.
 * 
 * @package Aura.Cli_Kernel
 * 
 */
class CliKernel
{
    protected $context;
    
    protected $stdio;
    
    protected $dispatcher;
    
    protected $logger;
    
    protected $params;

    public function __construct(
        Context $context,
        Stdio $stdio,
        Dispatcher $dispatcher,
        LoggerInterface $logger
    ) {
        $this->context = $context;
        $this->stdio = $stdio;
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
    }
    
    public function __get($key)
    {
        return $this->$key;
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
        $exit = $this->setParams();
        if ($exit) {
            return $exit;
        }

        try {
            $exit = $this->invokeCommand();
        } catch (Exception $e) {
            $exit = $this->commandFailed($e);
        }

        return $exit;
    }

    protected function setParams()
    {
        $this->params = $this->context->argv->get();
        $this->removeScriptFromParams();
        $this->setNamedCommandInParams();
        return $this->getStatus();
    }

    protected function removeScriptFromParams()
    {
        $script = array_shift($this->params);
        $this->logger->debug(__METHOD__ . " script: $script");
    }

    protected function setNamedCommandInParams()
    {
        $this->params['command'] = array_shift($this->params);
    }

    protected function getStatus()
    {
        if (! $this->commandIsSpecified()) {
            return Status::USAGE;
        }

        if (! $this->commandIsAvailable()) {
            return Status::UNAVAILABLE;
        }
        
        return 0;
    }

    protected function commandIsSpecified()
    {
        if ($this->params['command']) {
            return true;
        }

        $this->logger->error(__CLASS__ . ' command not specified');
        $this->stdio->errln('No command specified.');
        return false;
    }

    protected function commandIsAvailable()
    {
        $command = $this->params['command'];
        if ($this->dispatcher->hasObject($command)) {
            return true;
        }

        $this->logger->error(__CLASS__ . " command '{$command}' not available");
        $this->stdio->errln("Command '{$command}' not available.");
        return false;
    }

    protected function invokeCommand()
    {
        $command = $this->params['command'];
        $this->logger->debug(__CLASS__ . " command: $command", $this->params);
        $exit = $this->dispatcher->__invoke($this->params);
        return (int) $exit;
    }

    protected function commandFailed($e)
    {
        $message = $e->getMessage();
        $this->logger->error(__CLASS__ . " failure: $message");
        $this->stdio->errln($e->getMessage());
        return Status::FAILURE;
    }
}
