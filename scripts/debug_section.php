<?php
use SfpPHPExtension\Section;
use SfpPHPExtension\PeclData;

use Zend\Http\Client;
use Zend\Cache\StorageFactory;
use GethnaProjectAnalyze\ {
    Service\GithubRepository, GithubClient, Storage\CacheStorage
};


require_once __DIR__.'/../vendor/autoload.php';
$cache  = StorageFactory::adapterFactory('filesystem', [
    'cache_dir' => 'data/github',
]);

$list = include __DIR__.'/../extlist.php';
$ext = $list['lstrojny/php-nanotime'];
$peclData = new PeclData(__DIR__.'/../data/pecl');
$gitData = json_decode(file_get_contents(__DIR__.'/../data/gitdate.json'));
$github = new GithubRepository(new GithubClient(new Client()), new CacheStorage($cache));
$comment = include __DIR__.'/../comment.php';


$section = new Section(
    'lstrojny/php-nanotime',
    $ext,
    $peclData,
    $gitData,
    $github,
    $comment
);

var_dump($github->get('bukka','php-fann'));