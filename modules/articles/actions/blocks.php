<?php

$pageParams = array();

$blocks = $runtime->s2a($module, 'ListArticleBlocks');
$pageParams['blocks'] = $blocks;
$pageParams['ids'] = join_column(',', 'id', $blocks);

# Render page from all calculated parts
$pageParams['pagetitle'] = $r->txt->do_template($module, 'title.blocks');
$page->add('title',  $pageParams['pagetitle']);
$page->add('main',  $r->txt->do_template($module, 'blocks', $pageParams));
$page->add('css',  $r->txt->do_template($module, 'css'));

?>
