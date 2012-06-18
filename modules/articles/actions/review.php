<?php

$id = lavnn('id', $_REQUEST, '');
$articleInfo = array();
if ($id <> '') {
  # First of all, get article data
  $articleInfo = $objA->get_article(('id' => $id)); 
  $runtime->saveMoment('  fetched article data from db');
  
  if (count($articleInfo) > 0) {
    # Check more access to this article
    if ($acc['superadmin'] == 1 || $articleInfo['Author'] == ($r['userID']) || $acc->check_resource("editarticle:$id", $r['userID'])) {
      $articleInfo['moreaccess'] .= $runtime->txt->do_template($module, 'moreaccess.edit', $articleInfo);
    }
    if ($acc['superadmin'] == 1 || $articleInfo['Author'] == ($r['userID']) || $acc->check_resource("readarticle:$id", $r['userID'])) {
      $articleInfo['moreaccess'] .= $runtime->txt->do_template($module, 'moreaccess.view', $articleInfo);
    }
    # render the page
    $articleInfo['articleInfo'] = $objA->render_article('review', $articleInfo);
    $page->add('title',  $articleInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.review', $articleInfo);
    $page->add('main', $runtime->txt->do_template($module, 'review', $articleInfo);

    # register pageview
    srun('main', 'RegisterPageview', array('entity_type' => 'viewarticle', 'entity_id' => $id, 'viewer_type' => 'U', 'viewer_id' => $r['userID']));
  }  
} else {
  $page->add('title',  $articleInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.notfound');
  $page->add('main', $runtime->txt->do_template($module, 'notfound', $articleInfo);
}

$page->add('css',  $runtime->txt->do_template($module, 'css');


  

?>
