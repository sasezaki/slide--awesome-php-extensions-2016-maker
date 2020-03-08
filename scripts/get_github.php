<?php

use Zend\Http\Client;
use Zend\Cache\StorageFactory;
use GethnaProjectAnalyze\ {
    Service\GithubRepository, GithubClient, Storage\CacheStorage
};

$lists = require_once __DIR__.'/../extlist.php';
require_once __DIR__.'/../vendor/autoload.php';

$cache  = StorageFactory::adapterFactory('filesystem', [
    'cache_dir' => 'data/github',
//    'ttl' => 86400 * 365
]);

$repository = new GithubRepository(new GithubClient(new Client()), new CacheStorage($cache));

foreach ($lists as $list) {
    if (!isset($list['github'])) {
        continue;
    }

    list($owner, $repo) = explode('/', substr($list['github'], strlen('https://github.com/')));

    if ($repository->has($owner, $repo)) {
        $repo = $repository->get($owner, $repo);
    } else {
        echo $owner, $repo, PHP_EOL;
        $repository->store($owner, $repo);
    }
}