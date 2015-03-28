<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Cli_Kernel;

use Aura\Cli\Help;

/**
 *
 * Help for the help command.
 *
 * @package Aura.Cli_Kernel
 *
 */
class HelpHelp extends Help
{
    /**
     *
     * Initialize this help object.
     *
     * @return null
     *
     */
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
