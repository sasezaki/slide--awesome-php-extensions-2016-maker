<?php
foreach (include __DIR__.'/../extlist.php' as $ext_name => $ext) {
    if (!isset($ext['github'])) continue;

    static $skip = true;
    if ('tvlooy/piano' !== $ext_name && $skip) {
        continue;
    } else {
        $skip = false;
    }

    echo 'git clone ', $ext['github'], '.git ', str_replace('/', '_', $ext_name), PHP_EOL;
}