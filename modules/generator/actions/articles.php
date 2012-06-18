<?php

use objArticles;
$objA = new objArticles($r);

$articles = $objA->list_articles_bytag( ('tags' => 'special:generators') );
$page['menu'] = '';
$page->add('main', $runtime->txt->do_template($module, 'articles', array('articles' => $articles));

$specialoffers = $objA->list_articles_bytag( ('tags' => 'special:generatoroffer') );
if (count($specialoffers) > 0) {
  $page->add('main',  $runtime->txt->do_template($module, 'specialoffers', array('specialoffers' => $specialoffers));
}

print dotmod($module, 'index', $page);

?>
