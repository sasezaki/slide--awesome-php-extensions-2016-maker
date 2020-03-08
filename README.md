「このPHP拡張がすごい!」 - スライド作成用スクリプト集
---------------------------------------

 - 「このPHP拡張がすごい！2017」スライド作成時に用意した各PHP拡張の収集スクリプト＆スライド作成ツールです。
     - https://www.slideshare.net/sasezaki/php2017-70025223
 - 作った本人がどのような手順で動作させたかうろ覚えです。
 
  
 ## 元ネタの用意
 
 ### pecl拡張のデータ準備
  - PECL からのデータ取得
    - `php scripts/spider-pecl-rest.php allreleases` 
    - `php scripts/spider-pecl-rest.php first_releases`
    - `php scripts/spider-pecl-rest.php latest_releases`
  - 2013年以降のリポジトリを対象にリスト作成
     - `php scripts/make_peclData.php > peclList.php`

### 野良拡張の用意
  - 見つけた拡張リスト集を用意する - `ext_name_list.php`
  ```
<?php return [
    'mgdm/MFFI',
    'krakjoe/ui',
```
 - 仮のリスト集作成 `php make_extlist.php > extlist_{yyyymmdd}.php`
 - extlist_{yyyymmdd}.php にカテゴリやpeclのURL追加
 - `php normalize_list.php > extlist.php` してリスト作成
 - [gitリポジトリ] `scripts/gitclone.php`
 - [gitリポジトリ] `gitdate.php` を叩いて `data/gitdate.jsonにデータ用意`

## 追記情報などの設定
 - blacklist.php - make_section.php で利用
 - comment.php - 以下のような`commned_yyyymmdd.php`として用意したものから再生成したもの
```php
<?php return [
    'mgdm/MFFI',
    'pecl/ui',
    'pecl/sync' => [
        'PHP 7対応ブランチで<pre>php -d extension=modules/sync.so --re sync</pre>したとき、定義されてたクラスは、`SyncMutex`, `SyncSemaphore`, `SyncEvent`, `SyncReaderWriter`'
    ],
    'kitech/php-go' => [
        'いくつかのGoバインディングのひとつ(これが一番有名？)'
    ],
``` 
 - extending.php - コメント以外の付加ノート (2ページ目)
 - description.php - 詳細情報
 
## スライドの作成

```sh
php scripts/make_slide.php
```
 - ※ 内部で `scripts/make_section.php` を実行している
 

----
## APPENDIX
 - ext_name_list.php の用意の仕方
   https://github.com/sasezaki?language=c&tab=stars から眼力
   