<?php
namespace Aura\Cli_Kernel;

use Aura\Project_Kernel\Factory;

class CliKernelTest extends \PHPUnit_Framework_TestCase
{
    protected $cli_kernel;
    
    protected $status;
    
    protected function console(array $argv = array())
    {
        array_unshift($argv, 'cli/console.php');
        $_SERVER['argv'] = $argv;
        
        $path = __DIR__;
        $di = (new Factory)->newContainer(
            $path,
            'integration',
            "$path/composer.json",
            "$path/vendor/composer/installed.json"
        );

        $this->cli_kernel = $di->newInstance('Aura\Cli_Kernel\CliKernel');
        $this->status = $this->cli_kernel->__invoke();
    }
    
    public function testHello()
    {
        $this->console(array('aura-integration-hello'));
        $expect = 'Hello World!';
        $this->assertStdout('Hello World!' . PHP_EOL);
        $this->assertStderr('');
    }
    
    public function testNoCommandSpecified()
    {
        $this->console();
        $this->assertStdout('');
        $this->assertStderr('No command specified.' . PHP_EOL);
    }
    
    public function testCommandNotAvailable()
    {
        $this->console(array('aura-integration-no-such-command'));
        $this->assertStdout('');
        $this->assertStderr("Command 'aura-integration-no-such-command' not available." . PHP_EOL);
    }
    
    public function testException()
    {
        $this->console(array('aura-integration-exception'));
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
