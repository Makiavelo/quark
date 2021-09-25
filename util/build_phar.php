<?php
/**
 * Example: php build_phar.php --name="quark.phar" --from="./quark_no_dev" --stub="vendor/autoload"
 */
echo 'Starting to build...' . PHP_EOL;

$longopts  = array(
    "name:",
    "from:",
    "stub::"
);
$options = getopt('', $longopts);

$phar = new Phar($options['name']);
$phar->buildFromDirectory($options['from']);
$phar->setDefaultStub($options['stub'] ? $options['stub'] : 'vendor/autoload.php');

echo 'Done!' . PHP_EOL;