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

// get the project kernel
$base = dirname(dirname(dirname(dirname(__DIR__))));
$project_kernel = require "{$base}/vendor/aura/project-kernel/scripts/kernel.php";

// invoke it to get the DI container
$di = $project_kernel->__invoke();

// run the cli kernel and exit with its returned status code
$cli_kernel = $di->newInstance('Aura\Cli_Kernel\CliKernel');
$status = $cli_kernel->__invoke();
exit($status);
