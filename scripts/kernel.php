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

// the project base directory, relative to
// {$project}/vendor/aura/cli_kernel/scripts/kernel.php
$base = dirname(dirname(dirname(dirname(__DIR__))));

// config mode
$file = str_replace("/", DIRECTORY_SEPARATOR, "{$base}/config/_mode");
$mode = trim(file_get_contents($file));
if (! $mode) {
    $mode = "default";
}

// autoloader
$loader = require "{$base}/vendor/autoload.php";

// create the project kernel and invoke it to start the project running, then
// exit with its returned status code
$factory = new CliKernelFactory;
$kernel = $factory->newInstance($base, $mode, $loader);
$status = (int) $kernel->__invoke();
exit($status);
