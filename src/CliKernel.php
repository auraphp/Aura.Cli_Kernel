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

    protected $script;

    protected $command;

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
        $this->loadPropertiesFromContext();
        if ($this->commandIsUnvailable()) {
            return Status::UNAVAILABLE;
        } else {
            return $this->invokeCommand();
        }
    }

    protected function loadPropertiesFromContext()
    {
        $this->params = $this->context->argv->get();
        $this->script = array_shift($this->params);
        $this->command = array_shift($this->params);
        if (! $this->command) {
            $this->command = 'help';
        }
    }

    protected function commandIsUnvailable()
    {
        if ($this->dispatcher->hasObject($this->command)) {
            return false;
        }
        
        $message = "Command '{$this->command}' not available.";
        $this->logger->error(__CLASS__ . ': ' . $message);
        $this->stdio->errln($message);
        return true;
    }

    protected function invokeCommand()
    {
        $message = __CLASS__ . ": command: {$this->command}";
        $this->logger->debug($message, $this->params);

        try {
            $exit = $this->dispatcher->__invoke($this->params, $this->command);
            $this->logger->debug(__CLASS__ . ": success: {$this->command}");
        } catch (Exception $e) {
            $exit = $this->commandFailed($e);
        }

        return (int) $exit;
    }

    protected function commandFailed($e)
    {
        $exception = get_class($e);
        $message = $e->getMessage();
        $this->logger->error(__CLASS__ . ": failure: {$exception}: {$message}");
        $this->stdio->errln("{$exception}: {$message}");
        return Status::FAILURE;
    }
}
