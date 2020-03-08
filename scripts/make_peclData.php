<?php
use SfpPHPExtension\PeclRepository;

require_once __DIR__.'/../vendor/autoload.php';

$peclRepository = new PeclRepository(__DIR__.'/../data/pecl-rest', __DIR__.'/../data/pecl-web');

//var_dump($peclRepostiory->first_release_dates());

//foreach ($peclRepostiory->first_release_dates() as $package => $date) {
//    if (substr($date, 0, 4) < 2013) {
//        continue;
//    }
//    echo 'php scripts/scrape_pecl.php ',  "http://pecl.php.net/package/{$package}" , " > data/pecl-web/{$package}.json", PHP_EOL;
////    sleep(3);
//}

//$list = [
//  'pecl/ui' =>  [
//      'category' => '',
//      'github' => '',
//      'pecl' => 'ui'
//  ]
//];

$lists = [];
foreach ($peclRepository->first_release_dates() as $package => $date) {
    if (substr($date, 0, 4) < 2013) {
        continue;
    }

    /** @var \SfpPHPExtension\PeclModel $pecl */
    $pecl = $peclRepository->fetch($package);

    $p = [
        'category' => $pecl->category,
        'pecl' => $package,
    ];

    if ('github.com' === parse_url($pecl->Homepage, PHP_URL_HOST)) {
        $p['github'] = $pecl->Homepage;
    }

    $lists["pecl/{$package}"] = $p;
}


var_export($lists);
