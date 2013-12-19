<?php
namespace Aura\Cli_Kernel;

class CliKernelTest extends \PHPUnit_Framework_TestCase
{
    protected $cli_kernel;
    
    protected $status;
    
    protected function exec(array $argv = array())
    {
        // force into integration mode
        $_ENV['AURA_CONFIG_MODE'] = 'integration';
        
        // add the arguments after a faked initial script name
        array_unshift($argv, 'cli/console.php');
        $_SERVER['argv'] = $argv;
        
        // run the kernel
        $this->status = require dirname(dirname(__DIR__)) . '/scripts/kernel.php';
        
        // retain from the kernel script
        $this->cli_kernel = $cli_kernel;
    }
    
    public function testHello()
    {
        $this->exec(array('aura-integration-hello'));
        $expect = 'Hello World!';
        $this->assertStdout('Hello World!' . PHP_EOL);
        $this->assertStderr('');
    }
    
    public function testNoCommandSpecified()
    {
        $this->exec();
        $this->assertStdout('');
        $this->assertStderr('No command specified.' . PHP_EOL);
    }
    
    public function testCommandNotRecognized()
    {
        $this->exec(array('aura-integration-no-such-command'));
        $this->assertStdout('');
        $this->assertStderr("Command 'aura-integration-no-such-command' not recognized." . PHP_EOL);
    }
    
    public function testException()
    {
        $this->exec(array('aura-integration-exception'));
        $this->assertStdout('');
        $this->assertStderr('mock exception' . PHP_EOL);
    }
    
    protected function assertStdout($expect)
    {
        $stdout = $this->cli_kernel->stdio->getStdout();
        $stdout->rewind();
        $actual = $stdout->fread(strlen($expect));
        $this->assertEquals($expect, $actual);
    }
    
    protected function assertStderr($expect)
    {
        $stderr = $this->cli_kernel->stdio->getStderr();
        $stderr->rewind();
        $actual = $stderr->fread(strlen($expect));
        $this->assertEquals($expect, $actual);
    }
}
