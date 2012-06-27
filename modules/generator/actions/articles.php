<?php

use objArticles;
$objA = new objArticles($r);

$articles = $objA->list_articles_bytag( ('tags' => 'special:generators') );
$page['menu'] = '';
$page->add('main', $r->txt->do_template($module, 'articles', array('articles' => $articles));

$specialoffers = $objA->list_articles_bytag( ('tags' => 'special:generatoroffer') );
if (count($specialoffers) > 0) {
  $page->add('main',  $r->txt->do_template($module, 'specialoffers', array('specialoffers' => $specialoffers));
}

print $r->txt->do_template($module, 'index', $page);

?>
