<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Cli_Kernel;

/**
 *
 * A registry service to return help output.
 *
 * @package Aura.Cli_Kernel
 *
 */
class HelpService
{
    /**
     *
     * A registry of help object factories mapped by command names.
     *
     * @var array
     *
     */
    protected $map = array();

    /**
     *
     * Constructor.
     *
     * @param array $map A registry of help object factories mapped by command
     * names.
     *
     */
    public function __construct(array $map = array())
    {
        $this->map = $map;
    }

    /**
     *
     * Sets a help object factory into the map by name.
     *
     * @param string $command The help command name.
     *
     * @param callable $callable The callable factory to create the help object.
     *
     * @return null
     *
     */
    public function set($command, $callable)
    {
        $this->map[$command] = $callable;
    }

    /**
     *
     * Is a particular help command registered?
     *
     * @param string $command The help command name.
     *
     * @return bool
     *
     */
    public function has($command)
    {
        return isset($this->map[$command]);
    }

    /**
     *
     * Gets a new instance of the help object for a particular command.
     *
     * @param string $command The help command name.
     *
     * @return object
     *
     */
    public function get($command)
    {
        $callable = $this->map[$command];
        return $callable();
    }

    /**
     *
     * Gets the full help output for a particular command.
     *
     * @param string $command The help command name.
     *
     * @return string
     *
     */
    public function getHelp($command)
    {
        if ($this->has($command)) {
            return $this->get($command)->getHelp($command);
        }

        return '';
    }

    /**
     *
     * Gets the help summary output for a particular command.
     *
     * @param string $command The help command name.
     *
     * @return string
     *
     */
    public function getSummary($command)
    {
        if ($this->has($command)) {
            return $this->get($command)->getSummary($command);
        }

        return '';
    }
}
