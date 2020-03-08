<?php
$fp = tmpfile();

fwrite($fp, file_get_contents(__DIR__.'/../templates/head.html'));

fwrite($fp, PHP_EOL.'<div class="reveal"><div class="slides">'.PHP_EOL);
fwrite($fp, file_get_contents(__DIR__.'/../templates/start.html'));

ob_start(function ($out) use ($fp){
    fwrite($fp, $out);
});
include __DIR__.'/make_section.php';
ob_end_clean();


fwrite($fp, PHP_EOL);
fwrite($fp, file_get_contents(__DIR__.'/../templates/ending.html'));

fwrite($fp, file_get_contents(__DIR__.'/../templates/foot.html'));
fwrite($fp, PHP_EOL.'</div></div>'.PHP_EOL);

rewind($fp);
fpassthru($fp);