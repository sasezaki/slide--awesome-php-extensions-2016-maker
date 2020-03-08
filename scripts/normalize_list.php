<?php
$list = include __DIR__ .'/../extlist.php';

$return = [];

foreach ($list as $k => $sec) {
    $key = $k;

    if (isset($sec['pecl']['url'])) {
        $key = 'pecl/'. substr($sec['pecl']['url'], strlen('https://pecl.php.net/package/'));

        $return[$key]['pecl'] = $sec['pecl']['url'];
    }

    $return[$key]['category'] = $sec['category'];
    if (isset($sec['github'])) $return[$key]['github'] = $sec['github'];
}

var_export($return);