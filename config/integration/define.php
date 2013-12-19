<?php
/**
 * Aura\Cli\Stdio
 */
$di->params['Aura\Cli\Stdio'] = array(
    'stdin' => $di->lazyNew('Aura\Cli\Stdio\Handle', array(
        'name' => 'php://memory',
        'mode' => 'r+',
    )),
    'stdout' => $di->lazyNew('Aura\Cli\Stdio\Handle', array(
        'name' => 'php://memory',
        'mode' => 'w+',
    )),
    'stderr' => $di->lazyNew('Aura\Cli\Stdio\Handle', array(
        'name' => 'php://memory',
        'mode' => 'w+',
    )),
    'formatter' => $di->lazyNew('Aura\Cli\Stdio\Formatter'),
);
