<?php
namespace Aura\Cli_Kernel;

use Aura\Cli\Context;
use Aura\Cli\Stdio;
use Aura\Cli\Status;
use Aura\Dispatcher\Dispatcher;

class HelpCommand
{
    protected $stdio;

    protected $dispatcher;

    protected $help_service;

    public function __construct(
        Stdio $stdio,
        Dispatcher $dispatcher,
        HelpService $help_service
    ) {
        $this->stdio = $stdio;
        $this->dispatcher = $dispatcher;
        $this->help_service = $help_service;
    }

    public function __invoke($command = null)
    {
        if ($command) {
            return $this->showCommand($command);
        } else {
            return $this->showSummaries();
        }
    }

    protected function showCommand($command)
    {
        $help = rtrim($this->help_service->getHelp($command));
        if (! $help) {
            $this->stdio->errln("Help for command '{$command}' not available.");
            return Status::UNAVAILABLE;
        }
        $this->stdio->outln($help);
    }

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
