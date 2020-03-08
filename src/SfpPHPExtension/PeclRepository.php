<?php
namespace SfpPHPExtension;

use SimpleXMLElement;

class PeclRepository
{
    private $pecl_rest_dir;
    private $pecl_web_dir;

    private $packages;

    public function __construct($pecl_rest_dir, $pecl_web_dir)
    {
        $this->pecl_rest_dir = $pecl_rest_dir;
        $this->pecl_web_dir = $pecl_web_dir;
    }

    public function getPackages() : SimpleXMLElement
    {
        if (!$this->packages) {
            $this->packages = simplexml_load_file($this->pecl_rest_dir.'/p/packages.xml');
        }

        return $this->packages;
    }

    public function first_release_dates() : array
    {
        $dates = [];
        foreach ($this->getPackages()->p as $package) {
            if ($versionXml = $this->getFirstVersionXml($package)) {
                $dates[(string)$package] = (string) $versionXml->da;
            }
        }

        return $dates;
    }

    /**
     * //$category, //info.xml - ca
    //$Summary, //info.xml - s
    //$Maintainers, //  package.1.3.0.xml -lead
    //$Description //info.xml - d
    //$Homepage,  // extra
    //$latest_release_version,
    //$latest_release_date,
    //$first_release_version,
    //$first_release_date,
     */
    public function fetch($package)
    {
        $pecl = new PeclModel();
        if ($webInfo = $this->getWebInfo($package)) {
            $pecl->category = $webInfo->category ?? null;
            $pecl->Summary = $webInfo->Summary ?? null;
            $pecl->Maintainers = ($webInfo->Maintainers) ? $this->filterMaintainer($webInfo->Maintainers) : null;
            $pecl->Description = $webInfo->Description ?? null;
            $pecl->Homepage = $webInfo->Homepage ?? null;
        }

        if ($latestVersion = $this->getLatestVersionXml($package)) {
            $pecl->latest_release_date = (string) $latestVersion->da;
            $pecl->latest_release_version = (string) $latestVersion->v;
        }
        if ($firstVersion = $this->getFirstVersionXml($package)) {
            $pecl->first_release_date = (string) $firstVersion->da;
            $pecl->first_release_version = (string) $firstVersion->v;
        }
        
        return $pecl;
    }

    private function filterMaintainer(string $Maintainers) :string
    {
        $Maintainers = str_replace(['[details]', ' (lead)', '(developer)', '[wishlist]'], '' , $Maintainers);
        $Maintainers = preg_replace('/\w+@\w+\.\w+/s', '' , $Maintainers);
        $Maintainers = str_replace(['&lt;','&gt;',], '' , $Maintainers);

        return $Maintainers;
    }

    public function has($package) :bool
    {
        return (bool) $this->getWebInfo($package);
    }

    protected function getWebInfo($package)
    {
        if (file_exists($this->pecl_web_dir."/{$package}.json")) {
            return json_decode(file_get_contents($this->pecl_web_dir."/{$package}.json"));
        }

        return false;
    }

    protected function getLatestVersionXml($package)
    {
        if ($version = $this->getLatestVersion($package)) {
            if ($versionXml = $this->getVersionXml($package, $version)) {
                return $versionXml;
            }
        }

        return false;
    }

    protected function getLatestVersion($package)
    {
        if (file_exists($this->pecl_rest_dir."/r/{$package}/allreleases.xml")) {
            $allreleases = simplexml_load_file($this->pecl_rest_dir . "/r/{$package}/allreleases.xml");
            $allreleases->registerXPathNamespace('r', 'http://pear.php.net/dtd/rest.allreleases');
            $version = (string)$allreleases->xpath('//r:r[1]')[0]->v;
            return $version;
        }

        return false;
    }

    protected function getFirstVersionXml($package)
    {
        if ($version = $this->getFirstVersion($package)) {
            if ($versionXml = $this->getVersionXml($package, $version)) {
                return $versionXml;
            }
        }

        return false;
    }

    protected function getFirstVersion($package)
    {
        if (file_exists($this->pecl_rest_dir."/r/{$package}/allreleases.xml")) {
            $allreleases = simplexml_load_file($this->pecl_rest_dir . "/r/{$package}/allreleases.xml");
            $allreleases->registerXPathNamespace('r', 'http://pear.php.net/dtd/rest.allreleases');
            $version = (string)$allreleases->xpath('//r:r[last()]')[0]->v;
            return $version;
        }

        return false;
    }

    protected function getVersionXml($package, $version)
    {
        if (file_exists($this->pecl_rest_dir."/r/{$package}/{$version}.xml")
            && filesize($this->pecl_rest_dir."/r/{$package}/{$version}.xml") > 0) {
            return  simplexml_load_file($this->pecl_rest_dir."/r/{$package}/{$version}.xml");
        }

        return false;
    }
}