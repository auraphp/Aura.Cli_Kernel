<?php
namespace Aura\Cli_Kernel;

use Aura\Project_Kernel\ProjectContainer;

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
        // the project base directory
        $base = dirname(dirname(dirname(dirname(dirname(__DIR__)))));

        // set up autoloader
        $loader = require "$base/vendor/autoload.php";
        $loader->add('', "{$base}/src");

        // load environment modifications
        require "{$base}/config/_env.php";

        // create the project container
        $di = ProjectContainer::factory($base, $loader, $_ENV, null);

        // create and invoke a cli kernel
        $cli_kernel = $di->newInstance('Aura\Cli_Kernel\CliKernel');
        $this->status = $cli_kernel();
        
        // done
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
