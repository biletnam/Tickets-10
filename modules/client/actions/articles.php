<?php

use objArticles;
$objA = new objArticles($r);

$articles = $objA->list_articles_bytag( ('tags' => 'special:clients') );
$page['menu'] = '';
$page->add('main', $runtime->doTemplate($module, 'articles', array('articles' => $articles));

print dotmod($module, 'index', $page);
exit();

?>