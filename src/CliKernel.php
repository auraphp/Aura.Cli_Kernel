<?php
/**
 *
 * This file is part of Aura for PHP.
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
    /**
     *
     * A CLI context object.
     *
     * @var Context
     *
     */
    protected $context;

    /**
     *
     * A standard I/O object.
     *
     * @var Stdio
     *
     */
    protected $stdio;

    /**
     *
     * The CLI dispatcher.
     *
     * @var Dispatcher
     *
     */
    protected $dispatcher;

    /**
     *
     * A PSR-3 logger.
     *
     * @var LoggerInterface
     *
     */
    protected $logger;

    /**
     *
     * Params passed at the command line for the dispatcher.
     *
     * @var array
     *
     */
    protected $params;

    /**
     *
     * The console script invoked at the command line.
     *
     * @var string
     *
     */
    protected $script;

    /**
     *
     * The name of the command to dispatch to.
     *
     * @var string
     *
     */
    protected $command;

    /**
     *
     * Constructor.
     *
     * @param Context $context The CLI context.
     *
     * @param Stdio $stdio A standard I/O object.
     *
     * @param Dispatcher $dispatcher The CLI dispatcher.
     *
     * @param LoggerInterface $logger A PSR-3 logger.
     *
     */
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

    /**
     *
     * Magic get for read-only properties.
     *
     * @param string $key The property name.
     *
     * @return mixed The property.
     *
     */
    public function __get($key)
    {
        return $this->$key;
    }

    /**
     *
     * Runs this kernel.
     *
     * @return int The exit code.
     *
     */
    public function __invoke()
    {
        $this->loadPropertiesFromContext();

        if ($this->commandIsUnvailable()) {
            return Status::UNAVAILABLE;
        }

        return $this->invokeCommand();
    }

    /**
     *
     * Loads the kernel properties from the CLI context.
     *
     * @return null
     *
     */
    protected function loadPropertiesFromContext()
    {
        $this->params = $this->context->argv->get();
        $this->script = array_shift($this->params);
        $this->command = array_shift($this->params);
        if (! $this->command) {
            $this->command = 'help';
        }
    }

    /**
     *
     * Is the command unavailable?
     *
     * @return bool
     *
     */
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

    /**
     *
     * Invokes the command via the dispatcher.
     *
     * @return int The command exit code.
     *
     */
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

    /**
     *
     * The command failed because of an uncaught exception.
     *
     * @param Exception $e The exception thrown by the failed command.
     *
     * @return int
     *
     */
    protected function commandFailed($e)
    {
        $exception = get_class($e);
        $message = $e->getMessage();
        $this->logger->error(__CLASS__ . ": failure: {$exception}: {$message}");
        $this->logger->error($e->getTraceAsString());
        $this->stdio->errln("{$exception}: {$message}");
        return Status::FAILURE;
    }
}
