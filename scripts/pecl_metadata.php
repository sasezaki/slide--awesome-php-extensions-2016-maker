<?php

/**
 * https://pecl.php.net/rest/p/packages.xml
 * https://pecl.php.net/rest/r/amqp/allreleases.xml
 * https://pecl.php.net/rest/r/amqp/0.1.0.xml
 *
 */

foreach (include __DIR__.'/../extlist.php' as $ext_name => $ext) {
    if (!isset($ext['pecl']['url'])) continue;

    $ext_name = str_replace('/', '_' ,$ext_name);
    echo 'php scripts/scrape_pecl.php ',  $ext['pecl']['url'], " > data/pecl/{$ext_name}.json", PHP_EOL;
}