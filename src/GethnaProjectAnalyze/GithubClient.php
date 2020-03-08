<?php
declare(strict_types=1);

namespace GethnaProjectAnalyze;

use Zend\Http\Client;

class GithubClient
{
    const API_BASE = 'https://api.github.com';

    const PATH_REPOS = '/repos/%s/%s';

    private $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function repos($owner, $repo)
    {
        $httpClient = $this->httpClient;
        $request = new \Zend\Http\Request();
        $request->setUri(self::API_BASE.sprintf(self::PATH_REPOS, $owner, $repo));
        $response = $httpClient->send($request);

        if (!$response->isSuccess()) {
            throw new \Exception($response->getStatusCode());
        }

        return json_decode($response->getBody(), true);
    }
}