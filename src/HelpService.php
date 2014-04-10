<?php
namespace Aura\Cli_Kernel;

class HelpService
{
    protected $map = array();

    public function __construct(array $map = array())
    {
        $this->map = $map;
    }

    public function set($name, $callable)
    {
        $this->map[$name] = $callable;
    }

    public function has($name)
    {
        return isset($this->map[$name]);
    }

    public function get($name)
    {
        $callable = $this->map[$name];
        return $callable();
    }

    public function getHelp($name)
    {
        if ($this->has($name)) {
            return $this->get($name)->getHelp($name);
        }
    }

    public function getSummary($name)
    {
        if ($this->has($name)) {
            return $this->get($name)->getSummary($name);
        }
    }
}
