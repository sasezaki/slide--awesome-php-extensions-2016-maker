<?php
require_once __DIR__ . '/../vendor/autoload.php';

$scraper = new Diggin\Scraper\Scraper();

$scraper->process('//td[@class="content"]//a[2]', 'category', 'text');

$scraper->process('//tr//th[text()="Summary"]/parent::node()/td', 'Summary', 'text');
$scraper->process('//tr//th[text()="Maintainers"]/parent::node()/td', 'Maintainers', 'text');
$scraper->process('//tr//th[text()="Description"]/parent::node()/td', 'Description', 'text');
$scraper->process('//tr//th[text()="Homepage"]/parent::node()/td', 'Homepage', 'text');

//$scraper->process('//tr//th[text()="Available Releases"]/parent::node()/parent::node()/tr[3]/th', 'latest_release_version', 'text');
//$scraper->process('//tr//th[text()="Available Releases"]/parent::node()/parent::node()/tr[3]/td[2]', 'latest_release_date', 'text');
//
//$scraper->process('//tr//th[text()="Available Releases"]/parent::node()/parent::node()/tr[last()]/th', 'first_release_version', 'text');
//$scraper->process('//tr//th[text()="Available Releases"]/parent::node()/parent::node()/tr[last()]/td[2]', 'first_release_date', 'text');


$result = $scraper->scrape($argv[1]);

echo json_encode($result, JSON_PRETTY_PRINT);

