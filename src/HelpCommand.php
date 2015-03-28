<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Cli_Kernel;

use Aura\Cli\Stdio;
use Aura\Cli\Status;
use Aura\Dispatcher\Dispatcher;

/**
 *
 * A command to show help output.
 *
 * @package Aura.Cli_Kernel
 *
 */
class HelpCommand
{
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
     * The help output generation service.
     *
     * @var HelpService
     *
     */
    protected $help_service;

    /**
     *
     * Constructor.
     *
     * @param Stdio $stdio A standard I/O object.
     *
     * @param Dispatcher $dispatcher THe CLI dispatcher.
     *
     * @param HelpService $help_service The help output generation service.
     *
     */
    public function __construct(
        Stdio $stdio,
        Dispatcher $dispatcher,
        HelpService $help_service
    ) {
        $this->stdio = $stdio;
        $this->dispatcher = $dispatcher;
        $this->help_service = $help_service;
    }

    /**
     *
     * Invokes the command.
     *
     * @param string $command The command to get help for; if empty, shows the
     * list of all commands and their summaries.
     *
     * @return int
     *
     */
    public function __invoke($command = null)
    {
        if ($command) {
            return $this->showCommand($command);
        }

        return $this->showSummaries();
    }

    /**
     *
     * Shows the help for a command.
     *
     * @param string $command The command to get help for.
     *
     * @return int
     *
     */
    protected function showCommand($command)
    {
        if (! $this->dispatcher->hasObject($command)) {
            $this->stdio->errln("Command '{$command}' not available.");
            return Status::UNAVAILABLE;
        }

        $help = rtrim($this->help_service->getHelp($command));
        if (! $help) {
            $this->stdio->errln("Help for command '{$command}' not available.");
            return Status::UNAVAILABLE;
        }

        $this->stdio->outln($help);
        return Status::SUCCESS;
    }

    /**
     *
     * Shows the list of all commands and their summaries.
     *
     * @return int
     *
     */
    protected function showSummaries()
    {
        $commands = array_keys($this->dispatcher->getObjects());
        sort($commands);
        foreach ($commands as $command) {
            $summ = trim($this->help_service->getSummary($command));
            if (! $summ) {
                $summ = 'No summary available.';
            }
            $this->stdio->outln("<<bold>>{$command}<<reset>>");
            $this->stdio->outln("    {$summ}");
        }
    }
}
