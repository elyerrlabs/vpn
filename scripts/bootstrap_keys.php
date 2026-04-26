<?php

declare(strict_types=1);

require __DIR__ . '/../app/Services/KeysGenerator.php';

$generator = new \Vpn\App\Services\KeysGenerator();
$force = in_array('--force', $argv, true);

if (!$force && $generator->keysExist()) {
    fwrite(STDOUT, "Keys already exist, skipping generation." . PHP_EOL);
    exit(0);
}

if (!$generator->generateKeys($force)) {
    fwrite(STDOUT, "Keys already exist, skipping generation." . PHP_EOL);
    exit(0);
}

fwrite(STDOUT, "Keys successfully generated in {$generator->path}" . PHP_EOL);
