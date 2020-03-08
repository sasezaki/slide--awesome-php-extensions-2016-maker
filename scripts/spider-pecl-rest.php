<?php
chdir(__DIR__.'/../');

$skip_packags = ['yami'];

// get packages.xml
if (!file_exists('data/pecl-rest/p/packages.xml')) {
    `wget https://pecl.php.net/rest/p/packages.xml -O data/pecl-rest/p/packages.xml`;
}

$packages = simplexml_load_file('data/pecl-rest/p/packages.xml');

if (isset($argv[1])) {
    switch ($argv[1]) {
        case 'allreleases' :
            goto allreleases;
        case 'first_releases' :
            goto first_releases;
        case 'latest_releases' :
            goto latest_releases;
        default :
            break;
    }
}

allreleases : {
    // all releases
    foreach ($packages->p as $package) {
        if (!file_exists("data/pecl-rest/r/{$package}")) {
            mkdir("data/pecl-rest/r/{$package}");
        }
        if (!file_exists("data/pecl-rest/r/{$package}/allreleases.xml") &&
            !file_exists("data/pecl-rest/r/{$package}/.skip")) {
            `wget https://pecl.php.net/rest/r/{$package}/allreleases.xml -O data/pecl-rest/r/{$package}/allreleases.xml`;
        }
        if (file_exists("data/pecl-rest/r/{$package}/allreleases.xml")
            && filesize("data/pecl-rest/r/{$package}/allreleases.xml") === 0) {
            unlink("data/pecl-rest/r/{$package}/allreleases.xml");
            touch("data/pecl-rest/r/{$package}/.skip");
        }
    }
}


first_releases : {
    // get first release xml
    foreach ($packages->p as $package) {
        if (in_array((string)$package, $skip_packags)) continue;

        if (file_exists("data/pecl-rest/r/{$package}/allreleases.xml")) {
            $allreleases = simplexml_load_file("data/pecl-rest/r/{$package}/allreleases.xml");
            $allreleases->registerXPathNamespace('r', 'http://pear.php.net/dtd/rest.allreleases');
            $version = (string) $allreleases->xpath('//r:r[last()]')[0]->v;

            if (!file_exists("data/pecl-rest/r/{$package}/{$version}.xml")) {
                `wget https://pecl.php.net/rest/r/{$package}/{$version}.xml -O data/pecl-rest/r/{$package}/{$version}.xml`;
            }
        }
    }
}

latest_releases : {
    foreach ($packages->p as $package) {
        if (file_exists("data/pecl-rest/r/{$package}/allreleases.xml")) {
            $allreleases = simplexml_load_file("data/pecl-rest/r/{$package}/allreleases.xml");
            $allreleases->registerXPathNamespace('r', 'http://pear.php.net/dtd/rest.allreleases');
            $version = (string) $allreleases->xpath('//r:r[1]')[0]->v;
            if (!file_exists("data/pecl-rest/r/{$package}/{$version}.xml")) {
                `wget https://pecl.php.net/rest/r/{$package}/{$version}.xml -O data/pecl-rest/r/{$package}/{$version}.xml`;
            }
        }
    }
}
