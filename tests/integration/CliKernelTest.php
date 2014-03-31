<?php
namespace Aura\Cli_Kernel;

use Aura\Project_Kernel\Factory;

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
        
        // run the console script and retain the kernel
        $this->cli_kernel = $this->console();
    }
    
    // this should remain an exact copy of cli/console.php,
    // with the exception that it should not exit()
    protected function console()
    {
        $path = __DIR__;
        $di = (new Factory)->newContainer(
            $path,
            'integration',
            "$path/composer.json",
            "$path/vendor/composer/installed.json"
        );

        // create and invoke a cli kernel
        $cli_kernel = $di->newInstance('Aura\Cli_Kernel\CliKernel');
        
        // retain the status but do not exit
        $this->status = $cli_kernel();
        return $cli_kernel;
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
    
    public function testCommandNotAvailable()
    {
        $this->exec(array('aura-integration-no-such-command'));
        $this->assertStdout('');
        $this->assertStderr("Command 'aura-integration-no-such-command' not available." . PHP_EOL);
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
