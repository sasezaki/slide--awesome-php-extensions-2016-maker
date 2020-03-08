<?php
//[
//    'krakjoe/ui' => [
//        'php' => null,
//        'category' => '',
//        'since' => null,
//        'pecl' => [
//            'available' => false,
//        ],
//        'homepage' => null,
//        'meta' => [
//            'remi-check' => null,
//        ],
//    ]
//];

$ext = include __DIR__ . '/ext_name_list.php';

$list = [];
$template = [
        'php' => null,
        'category' => '',
        'since' => null,
//    'pecl' => [
//        'url' => 'https://pecl.php.net/package/ds',
//    ],
        'homepage' => null,
//        'meta' => [
//            'remi-check' => null,
//        ],
    ];

foreach ($ext as $name) {
    $list[$name] = array_merge($template,[
        'homepage' => "https://github.com/{$name}"
    ]);
}

// echo json_encode($list, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);

var_export($list);