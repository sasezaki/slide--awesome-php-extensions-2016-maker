<?php
namespace SfpPHPExtension;

use GethnaProjectAnalyze\Service\GithubRepository;

class Section
{
    private $name;
    private $ext;
    private $peclRepository;
    private $gitData;
    private $github;
    private $comments;

    private $extending;
    private $specific_description;

    public function __construct($ext_name, array $ext, PeclRepository $peclRepository, $gitData, GithubRepository $github, array $comments,
                                array $extending, array $specific_description)
    {
        $this->name = $ext_name;
        $this->ext = $ext;
        $this->peclRepository = $peclRepository;
        $this->gitData = $gitData;
        $this->github = $github;
        $this->comments = $comments;
        $this->extending = $extending;
        $this->specific_description = $specific_description;
    }

//    public function __isset($key)
//    {
//        return isset($this->ext[$key]);
//    }
//
//    public function __get($key)
//    {
//
//        if ($key === 'homepage') {
//            if (isset($this->ext['github'])) {
//                return $this->ext['github'];
//            }
//
//            return '';
//        }
//        if ($key === 'homepage' && isset($this->ext['pecl'])) {
//            return $this->ext['pecl']   ;
//        }
////        if ($key === 'pecl_url' && isset($this->ext['pecl']['url'])) {
////            return $this->ext['pecl']['url'];
////        }
////
////        if ($key === 'package_name' && isset($this->ext['pecl']['url'])) {
////            return substr($this->ext['pecl']['url'], strlen("https://pecl.php.net/package/"));
////        }
//
////        if ($key === 'peclData') {
////            if ($this->peclData->has($this->name)) {
////                return $this->peclData->get($this->name);
////            }
////            return '';
////        }
//
//
//        return $this->ext[$key];
//    }

    public function getHomepage()
    {
        if ($this->hasPeclModel()) {
            $peclModel = $this->getPeclModel();
            if (!empty($peclModel->Homepage)) {
                return $peclModel->Homepage;
            }
        }

//        if (isset($this->ext['pecl'])) {
//            return $this->ext['pecl'];
//        }

        if (isset($this->ext['github'])) {
            return $this->ext['github'];
        }

        return '';
    }

    public function getDescription()
    {
        if ($this->hasSpecificDescription()) {
            return $this->getSpecificDescription();
        }

        if ($this->hasPeclModel()) {
            return $this->getPeclModel()->Description;
        }

        if ($this->hasGithub()) {
            return $this->getGithub()->description;
        }

        return '';
    }

    public function hasPeclModel()
    {
        if ('pecl/' !== substr($this->name, 0, 5)) {
            return false;
        }

        $package = substr($this->name, 5);

        return $this->peclRepository->has($package);
    }

    public function getPeclModel() : PeclModel
    {
        $package = substr($this->name, 5);
        return $this->peclRepository->fetch($package);
    }
    
    public function hasSpecificDescription()
    {
        return array_key_exists($this->name, $this->specific_description);
    }

    public function getSpecificDescription()
    {
        return $this->specific_description[$this->name];
    }

    public function hasGitData()
    {
        $name = str_replace('/', '_', $this->name);
        return isset($this->gitData->{$name});
    }

    public function getGitData()
    {
        $name = str_replace('/', '_', $this->name);

        return [
            'latest_date' => substr($this->gitData->{$name}[0], 0 ,10),
            'first_date' => substr($this->gitData->{$name}[1], 0 ,10)
        ];
    }

    public function hasGithub()
    {
        if (!isset($this->ext['github'])) {
            return false;
        }

        list($owner, $repo) = explode('/', substr($this->ext['github'], strlen('https://github.com/')));

        return $this->github->has($owner, $repo);
    }

    public function getGithub()
    {
        list($owner, $repo) = explode('/', substr($this->ext['github'], strlen('https://github.com/')));

        if ($this->github->has($owner, $repo)) {
            $repo = $this->github->get($owner, $repo);
            return $repo;
        }

        return false;
    }

    public function getComments() : array 
    {
        if (array_key_exists($this->name, $this->comments) &&
                is_array($this->comments[$this->name])) {
            return $this->comments[$this->name];
        } else {
            return [];
        }
    }

    public function hasExtending()
    {
        if (array_key_exists($this->name, $this->extending) &&
            is_array($this->extending[$this->name])) {
            return true;
        }
        return false;
    }

    public function getExtending()
    {
        if (array_key_exists($this->name, $this->extending) &&
            is_array($this->extending[$this->name])) {
            return $this->extending[$this->name];
        }
        return [];
    }
}
