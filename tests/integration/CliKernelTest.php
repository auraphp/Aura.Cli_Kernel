<?php
namespace Aura\Cli_Kernel;

class CliKernelTest extends \PHPUnit_Framework_TestCase
{
    protected $cli_kernel;
    
    protected function exec(array $argv = array())
    {
        // force into integration mode
        $_ENV['AURA_CONFIG_MODE'] = 'integration';
        
        // add the arguments after a faked initial script name
        $_SERVER['argv'] = array_merge('cli/console.php', $argv);
        
        // run the kernel
        require dirname(dirname(__DIR__)) . '/scripts/kernel.php';
        
        // retain from the kernel script
        $this->cli_kernel = $cli_kernel;
    }
    
    public function testHello()
    {
        $this->exec(array('aura-integration-hello'));
        $expect = 'Hello World!';
        $this->assertStdout($expect, $actual);
    }
    
    protected function assertStdout($expect)
    {
        $stdout = $this->cli_kernel->stdio->getStdout();
        $stdout->rewind();
        $actual = $stdout->fread(strlen($expect));
        $this->assertSame($expect, $actual);
    }
}
