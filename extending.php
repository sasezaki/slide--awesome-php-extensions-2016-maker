<?php

return [
    'bullsoft/php-binlog' => [
        '<img src="image/binlog.png" style="height:400px;">',
    ],
    'cybozu/php-yrmcds' => [
//        <<<'TWITTER'
//<blockquote class="twitter-tweet" data-lang="en-gb"><p lang="ja" dir="ltr">yrmcds/libyrmcds/php-yrmcds, それぞれ memcached, libmemcached, PECL memcache or memcached に悩んでいる人がいればぜひお試しを。安定度は自信あります。<a href="https://t.co/08yAv1e9b4">https://t.co/08yAv1e9b4</a></p>&mdash; Yamamoto, Hirotaka (@ymmt2005) <a href="https://twitter.com/ymmt2005/status/799070194693902336">17 November 2016</a></blockquote>
//<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
//TWITTER
        '<img src="image/yrmcds.png">',
    ],
    'dmitry-saprykin/php-btp-extension' => [
            'btpとは？<hr /><a href="https://github.com/mambaru/btp-daemon" target="_blank">mambaru/btp-daemon: BTP daemon - performance analysis tool </a>のREADMEより<br><img src="image/btp.png">'
        ],
    'krakjoe/autostrict' => [
        '<img src="image/autostrict-twitter.png" style="width:130%">',
        <<<'CODE'
<pre><code>$ cat thanksphp.php
</code></pre>

<pre><code>&lt;?php (function (int $x, int $y){ var_dump($x + $y);})("1.5", 2.8);
</code></pre>

<pre><code>$ php -d zend_extension=autostrict.so -d autostrict.enable=0 thanksphp.php
</pre></code>

<pre><code>int(3)
</pre></code>

<pre><code>$ php -d zend_extension=autostrict.so -d autostrict.enable=1 thanksphp.php
</pre></code>

<pre><code>PHP Fatal error:  Uncaught TypeError: Argument 1 passed to {closure}() must be of the type integer, string given, called in /tmp/thanksphp.php on line 1 and defined in /tmp/thanksphp.php:1
Stack trace:
#0 /tmp/thanksphp.php(1): {closure}('1.5', 2.8)
#1 {main}
  thrown in /tmp/thanksphp.php on line 1
</pre></code>
CODE

    ],
    'krakjoe/uneval' => [
        <<<'CODE'
<pre><code>
switch (opline->opcode) {
    case ZEND_INCLUDE_OR_EVAL: {
        if (opline->extended_value == ZEND_EVAL) {
            zend_error(E_ERROR, "eval() is disabled in this environment for security purposes");
            return;
        }
    } break;
}
</code></pre>
CODE

    ],
    'pecl/ds' => [
        '<img src="image/ds-pq.gif"><hr />記事 <a href="https://medium.com/@rtheunissen/efficient-data-structures-for-php-7-9dda7af674cd#.7dty0re3f" target="_blank">Efficient data structures for PHP 7 – Medium</a> より'
    ]
];