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

    public function __invoke($name = null)
    {
        if ($name) {
            return $this->showCommand($name);
        } else {
            return $this->showSummaries();
        }
    }

    protected function showCommand($name)
    {
        $help = rtrim($this->help_service->getHelp($name));
        if (! $help) {
            $this->stdio->errln("Help for command '{$name}' not available.");
            return Status::UNAVAILABLE;
        }
        $this->stdio->outln($help);
    }

    protected function showSummaries()
    {
        $names = array_keys($this->dispatcher->getObjects());
        sort($names);
        foreach ($names as $name) {
            $summ = trim($this->help_service->getSummary($name));
            if (! $summ) {
                $summ = 'No summary available.';
            }
            $this->stdio->outln("<<bold>>{$name}<<reset>>");
            $this->stdio->outln("    {$summ}");
        }
    }
}
