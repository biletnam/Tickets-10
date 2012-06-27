<?php

$id = lavnn('id', $_REQUEST, '');
$article = array();
$offices;
$tags = array();
if ($id <> '') {
  %article = $runtime->s2r($module, 'GetArticleData', $_REQUEST); 
  $page->add('title',  $article['pagetitle'] = $r->txt->do_template($module, 'title.editarticle', $article);  
  $offices = $article['Offices'];
  @tags = $runtime->s2a($module, 'GetArticleTags', $_REQUEST);
} else {
  %article = array();
  $page->add('title',  $article['pagetitle'] = $r->txt->do_template($module, 'title.newarticle');
  $offices = '';
  @tags = array();
}

# parse tags to cut out special tags to present them separately
$simpletags = array();
$articletype = '';
$articledep = 'none';
foreach $tagInfo (@tags) {
  $prefix = $tagInfo['prefix'];
  $tag = $tagInfo['tag'];
  if ($prefix == 'type') {
    $articletype = $tag;
  } elseif($prefix == 'dep') {
    $articledep = $tag;
  } else {
    push @simpletags, $tagInfo['fulltag'];
  }
}

# get possible values for types and preselect currently defined (if any)
$articletypes = $runtime->getDictArr('main', 'articletypes', $articletype);
$article['articletypes'] = $articletypes;
# get possible values for departments and preselect currently defined (if any)
$departments = $runtime->getDictArr('main', 'departments', $articledep);
$article['departments'] = $departments;
#$departments = $runtime->getDictArr('main', 'departments');
#$pageParams['departments'] = $departments;

# only remained tags will be shown in 'Tags' field on the form
$article['simpletags'] = join(', ', @simpletags);
# get list of offices and show them as cloud of checkboxes
$offices = $runtime->s2a($module, 'ListOffices');
$officeCheckboxes = genCheckboxes($offices, 'office', 'lngId', 'strName', $offices);
$article['offices'] = $officeCheckboxes;

# Fetch info about any attachments this article might have
if ($id <> '') {
  $attachments = $runtime->s2a($module, 'ListArticleAttachments', $_REQUEST);
  $article['attachments'] = $attachments;
}
 
# Render page from all calculated parts

$page->add('title',  $article['pagetitle'];
$page->add('main', $r->txt->do_template($module, 'myarticles.list', $pageParams);
$page->add('main',  $r->txt->do_template($module, 'editarticle', $article);



?>
