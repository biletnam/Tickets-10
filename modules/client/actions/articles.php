<?php

use objArticles;
$objA = new objArticles($r);

$articles = $objA->list_articles_bytag( ('tags' => 'special:clients') );
$page['menu'] = '';
$page->add('main', $runtime->txt->do_template($module, 'articles', array('articles' => $articles));

print $runtime->txt->do_template($module, 'index', $page);
exit();

?>
