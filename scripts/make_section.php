<?php
use SfpPHPExtension\Section;

use Zend\Http\Client;
use Zend\Cache\StorageFactory;
use GethnaProjectAnalyze\ {
    Service\GithubRepository, GithubClient, Storage\CacheStorage
};

require_once __DIR__.'/../vendor/autoload.php';

$cache  = StorageFactory::adapterFactory('filesystem', [
    'cache_dir' => 'data/github',
]);

$list = include __DIR__.'/../extlist.php';

//
    $lists = [];
    foreach ($list as $name => $p) {
        if (isset($p['pecl'])) continue;
        $lists[$name] = $p;
    }
    $lists = array_merge_recursive($lists, include __DIR__.'/../peclList.php');

// filter
    $blacklist = include __DIR__.'/../blacklist.php';
    foreach ($lists as $key => $list) {
        if (in_array($key, $blacklist)) {
            unset($lists[$key]);
        }
    }

$aggre = new \SfpPHPExtension\CategoryAggregate();
$lists = $aggre->divide($lists); // カテゴリごとのnestedリスト

$peclRepository = new SfpPHPExtension\PeclRepository(__DIR__.'/../data/pecl-rest', __DIR__.'/../data/pecl-web');

$gitData = json_decode(file_get_contents(__DIR__.'/../data/gitdate.json'));
$github = new GithubRepository(new GithubClient(new Client()), new CacheStorage($cache));

$comment = include __DIR__.'/../comment.php';
$extending =  include __DIR__.'/../extending.php'; // appending notes
$specific_description = include __DIR__.'/../description.php';

$extList = call_user_func(function() use ($lists, $peclRepository, $gitData, $github, $comment, $extending, $specific_description) {
    foreach ($lists as $category => $list) {
        yield $category;
        foreach ($list as $ext_name => $ext) {
            $section = new Section(
                    $ext_name,
                    $ext,
                    $peclRepository,
                    $gitData,
                    $github,
                    $comment,
                    $extending, $specific_description
                );

            // 2015 年以降でスキップ
//            if ($section->hasGitData()) {
//                if (substr($section->getGitData()['first_date'], 0, 4) < 2015) {
//                    continue;
//                }
//            }

            yield $ext_name =>  $section;
        }
        yield false; // end of category
    }
});
// var_dump(array_diff(array_keys($list), array_keys(iterator_to_array($extList)))); // カテゴライズしてないとdiffがでる
?>

<?php $counter = 0; /** @var Section $ext */ ?>
<?php foreach ($extList as $ext_name => $ext) : ?>

    <?php if (!$ext instanceof Section) : ?>
        <?php if (is_string($ext)) : ?>
<!--<section>-->
    <section><?= $ext ?></section>
        <?php endif; ?>
        <?php if ($ext === false) : ?>
<!--</section>-->
        <?php endif; ?>
        <?php continue; ?>
    <?php endif ?>

    <?php ++$counter ?>
    <section>
        <!-- <?= $ext_name ?> -->
        <span style="font-size:130%;"><?= $counter ?>. <?= $ext_name ?></span><br>
        <a class="homepage" href="<?= $ext->getHomepage()?>"><?= $ext->getHomepage() ?></a>

        <div class="description"><?= mb_substr($ext->getDescription(), 0, 200),  (mb_strlen($ext->getDescription()) > 200) ? "..." : "" ?></div>
        <hr>

        <?php if (!in_array($ext_name, ['pecl/ds', 'pecl/sync','pecl/env'])) : ?>
        <?php if ($comments = $ext->getComments()): ?>
        <div class="comment">
            <ul>
                <?php foreach ($comments as $comment) : ?>
                    <li><?= $comment ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <hr>
        <?php endif; ?>
        <?php endif; ?>

        <?php if ($ext->hasPeclModel()) : ?>
            <?php $pecl = $ext->getPeclModel(); ?>
        <div class="pecl"><span class="name"><a class="url" href="https://pecl.php.net/package/<?= substr($ext_name,5) ?>"><?= $ext_name ?></a></span>
          <table>
            <tbody>
              <tr class="Maintainers">
               <th>Maintainers</th>
               <td><?= $pecl->Maintainers ?><br></td>
              </tr>
              <tr class="latest_release">
               <th>Latest Release</th>
               <td><?= $pecl->latest_release_date ?> - <?= $pecl->latest_release_version ?></td>
              </tr>
              <tr class="first_release">
               <th>First Release</th>
               <td><?= $pecl->first_release_date ?> - <?= $pecl->first_release_version ?></td>
              </tr>
            </tbody>
          </table>
        </div>
        <hr/>
        <?php endif; ?>

        <?php if ($ext->hasGitData()) : ?>
            <?php $git = $ext->getGitData(); ?>
        <div class="git">
          <table>
            <tbody>
              <tr class="latest_release">
               <th>Latest Commit</th>
               <td><?= $git['latest_date'] ?></td>
              </tr>
              <tr class="first_release">
               <th>First Commit</th>
               <td><?= $git['first_date'] ?></td>
              </tr>
            </tbody>
          </table>
        </div>
        <hr>
        <?php endif; ?>

    </section>

    <?php if (in_array($ext_name, ['pecl/ds', 'pecl/sync', 'pecl/env'])) : ?>
    <section>
        <span style="font-size:110%;"><?= $counter ?>. <?= $ext_name ?></span><hr>
        <?php if ($comments = $ext->getComments()): ?>
            <div class="comment">
                <ul>
                    <?php foreach ($comments as $comment) : ?>
                        <li><?= $comment ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <hr>
        <?php endif; ?>
    </section>
    <?php endif; ?>

    <?php if ($ext->hasExtending()) : ?>
    <?php foreach ($ext->getExtending() as $extended) : ?>
    <section>
        <span style="font-size:110%;"><?= $counter ?>. <?= $ext_name ?></span><hr>
        <?= $extended; ?>
    </section>
    <?php endforeach; ?>
    <?php endif; ?>

<?php endforeach; ?>
