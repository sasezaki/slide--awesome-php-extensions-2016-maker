<?php

//  find -maxdepth 1 -mindepth 1 | xargs -I {} php -r 'chdir($argv[1]); echo "fetch..."; `git pull`; echo "done", PHP_EOL; chdir("../");' {}

$git_dir = '/mnt/c/dev/git';

//$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($git_dir));
$di = new DirectoryIterator($git_dir);

$dates =[];

/** @var SplFileInfo $f */
foreach ($di as $f) {
    if (!$f->isDir()) continue;
    if ((string)$f === '.' || (string) $f === '..') continue;

    $output = [];
    chdir(realpath($git_dir.DIRECTORY_SEPARATOR.$f));
    exec("git log --date=iso | head -n 4 | grep Date | sed -e 's/Date:   //'", $output, $ret);
    exec("git log --reverse --date=iso | head -n 4 | grep Date | sed -e 's/Date:   //'", $output, $ret);

    $dates[(string)$f] = $output;
}

file_put_contents(__DIR__.'/../data/gitdate.json', json_encode($dates, JSON_PRETTY_PRINT));