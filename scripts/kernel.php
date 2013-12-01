<?php
/**
 * 
 * This file is part of Aura for PHP. It is a bootstrap for Composer-oriented
 * CLI projects.
 * 
 * @package Aura.Cli_Kernel
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\Cli_Kernel;

use Aura\Project_Kernel\ProjectKernelFactory;

// the project base directory, relative to
// {$project}/vendor/aura/cli_kernel/scripts/kernel.php
$base = dirname(dirname(dirname(dirname(__DIR__))));

// the project config mode
$file = str_replace("/", DIRECTORY_SEPARATOR, "{$base}/config/_mode");
$mode = trim(file_get_contents($file));
if (! $mode) {
    $mode = "default";
}

// composer autoloader
$loader = require "{$base}/vendor/autoload.php";

// project config
$project_kernel_factory = new ProjectKernelFactory;
$project_kernel = $project_kernel_factory->newInstance($base, $mode, $loader);
$di = $project_kernel->__invoke();

// run the cli kernel and exit with its returned status code
$cli_kernel = $di->get('cli_kernel');
$status = $cli_kernel->__invoke();
exit($status);
