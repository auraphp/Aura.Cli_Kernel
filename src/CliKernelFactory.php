<?php
namespace Aura\Cli_Kernel;

use Aura\Project_Kernel\ProjectKernelFactory;

class CliKernelFactory extends ProjectKernelFactory
{
    protected $class = 'Aura\Cli_Kernel\CliKernel';
}
