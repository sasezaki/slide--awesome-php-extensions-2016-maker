<?php
declare(strict_types=1);

namespace GethnaProjectAnalyze\Service;

use GethnaProjectAnalyze\GithubClient;
use GethnaProjectAnalyze\Storage\StorageInterface as Storage;

class GithubRepository
{
    private $githubClient;
    private $storage;

    public function __construct(GithubClient $githubClient, Storage $storage)
    {
        $this->githubClient = $githubClient;
        $this->storage = $storage;
    }

    public function store($owner, $repo)
    {
        try {
            $repos = $this->githubClient->repos($owner, $repo);
            $this->storage->save($this->makeKey($owner, $repo), json_encode($repos, JSON_PRETTY_PRINT));
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }
    
    public function has($owner, $repo) : bool
    {
        return $this->storage->has($this->makeKey($owner,$repo));
    }

    public function get($owner, $repo)
    {
        return json_decode($this->storage->get($this->makeKey($owner, $repo)));
    }

    private function makeKey($owner, $repo)
    {
        return 'github_'.$owner.'_'.$repo;
    }
}