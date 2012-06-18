<?php

$pageParams = %_REQUEST;

$tagfilter = lavnn('tag', $_REQUEST, '');
$search = lavnn('search', $_REQUEST, '');
# Parse tag filter if it was requested - if it contains block, it will be handled differently
($prefix, $tag) = split(':', $tagfilter, 2);
if ($tag == '' && $prefix <> '') {
  $tag = $prefix; $prefix = '';
}

# Get pagelet that renders articles
$sqlParams = array(
  'id' => $r['userID'], 
  'office' => $r['userInfo']['lngWorkPlace'].'',
  'department' => $r['userInfo']['team_id'].'',
  'sourcetype' => 'readarticle,editarticle', 
  'tagfilter' => $tagfilter,
  'search' => $search,
  'uniqueblock' => 1,
);

# If articles are filtered by block, we might show special form
if ($prefix == 'block') {
  $pageParams['blockinfo'] = hash2ref(s2r($module, 'GetArticleBlockInfo', array('code' => $tag)));
  $pageParams['searchform'] = $runtime->txt->do_template($module, 'search.form.block', $pageParams);
} else {
  $pageParams['searchform'] = $runtime->txt->do_template($module, 'search.form', $pageParams);
}

if ($tagfilter <> '' || $search <> '') {

  $articles = $objA->search_articles(%sqlParams);
  
  if (count($articles) > 1) {
    $pageParams['articles'] = $articles;
  } elseif (count($articles) == 1) {
    $articleInfo = $runtime->s2r($module, 'GetArticleData', array('id' => $articles[0]['id'])); 
    $articletags = $runtime->s2a($module, 'GetArticleTags', $articleInfo); 
    $articleInfo['preparedview'] = $objA->view_article($articleInfo, $articletags); 
    $pageParams['onearticle'] = $runtime->txt->do_template($module, 'search.onearticle', $articleInfo);  
  } else {
    $pageParams['noarticles'] = $runtime->txt->do_template($module, 'search.noarticles', array('fulltag' => $fulltag));
  }
} else {
  $pageParams['nocriteria'] = $runtime->txt->do_template($module, 'search.nocriteria');
}

# Render page from all calculated parts
$page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.search');
$page->add('main',  $runtime->txt->do_template($module, 'search', $pageParams);
$page->add('css',  $runtime->txt->do_template($module, 'css');



?>
