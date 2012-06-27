<?php

use objArticles;
$objA = new objArticles($r);

$articles = $objA->list_articles_bytag( ('tags' => 'special:clients') );
$page['menu'] = '';
$page->add('main', $r->txt->do_template($module, 'articles', array('articles' => $articles));

print $r->txt->do_template($module, 'index', $page);
exit();

?>
