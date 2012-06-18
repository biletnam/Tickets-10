<?php

$pageParams = array();

$blocks = $runtime->s2a($module, 'ListArticleBlocks');
$pageParams['blocks'] = $blocks;
$pageParams['ids'] = Arrays::join_column(',', 'id', $blocks);

# Render page from all calculated parts
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.blocks'));
$page->add('main',  $runtime->doTemplate($module, 'blocks', $pageParams));
$page->add('css',  $runtime->doTemplate($module, 'css'));


?>
