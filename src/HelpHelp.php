<?php
namespace Aura\Cli_Kernel;

use Aura\Cli\Help;

class HelpHelp extends Help
{
    protected function init()
    {
        $this->summary = 'Gets the available commands, or the help for one command.';
        $this->usage = array(
            '',
            '<command>'
        );
        $this->descr = <<<EOT
    Issue `<<ul>>help<<reset>>` to get the list of available commands,
    or `<<ul>>help<<reset>> command` to get help on a specific command.
EOT;
    }
}
