<?php

$id = lavnn('id', $_REQUEST, '');
$articleInfo = array();
if ($id <> '') {
  # First of all, get article data
  $articleInfo = $objA->get_article(('id' => $id)); 
  $runtime->saveMoment('  fetched article data from db');
  
  if (count($articleInfo) > 0) {

    # Check more access - only review, because preview is included by default  
    if ($acc->check_resource("articlereviewers:$id", $r['userID'])) {
      $articleInfo['moreaccess'] .= $runtime->txt->do_template($module, 'moreaccess.review', $articleInfo);
    }
  
    # parse tags to cut out special tags to present them separately
    $articleblocks = array();
    $simpletags = array(); $articletype = '';
    foreach $tagInfo ($articleInfo['tags']}) {
      $prefix = $tagInfo['prefix'];
      $tag = $tagInfo['tag'];
      if ($prefix == 'type') {
        $articletype = $tag;
      } elseif ($prefix == 'block') {
        push @articleblocks, $tag;
      } else {
        push @simpletags, $tagInfo['fulltag'];
      }
    }
    $runtime->saveMoment('  sorted tags to simple ones and special ones');

    # get possible values for types and blocks and preselect currently defined (if any)
    $articletypes = $runtime->getDictArr('main', 'articletypes', $articletype);
    $articleInfo['articletypes'] = $articletypes;
    $blocks = $runtime->s2a($module, 'ListArticleBlocks');
    $articleBlockCheckboxes = genCheckboxes($blocks, 'blocks', 'code', 'name', join(',', @articleblocks));
    $articleInfo['articleblocks'] = $articleBlockCheckboxes;
    # only remained tags will be shown in 'Tags' field on the form
    $articleInfo['simpletags'] = join(', ', @simpletags);
    $runtime->saveMoment('  prepared option lists for special tags');
    # If article is not draft or deleted, show sendflag checkbox
    if ($articleInfo['draft'] <> 1 && $articleInfo['deleted'] <> 1) {
      $articleInfo['sendnotifications'] = $runtime->txt->do_template($module, 'read.edit.sendnotifications');
    }

    # draft options
    $draftoptions = $runtime->getDictArr('main', 'yesno', $articleInfo['draft']);
    $articleInfo['draftoptions'] = $draftoptions;
    # Add notification history, if it exists
    $notifications = $runtime->s2a($module, 'ListArticleNotifications', $articleInfo);
    if (count($notifications) > 0 ) {
      $articleInfo['notifications'] = $notifications;
      $articleInfo['notificationhistory'] = $runtime->txt->do_template($module, "article.notifications", $articleInfo); 
    }
    # Get history of pageviews
    use ctlDataGrid;
    $basequery = spreview('main', 'GetPageviewStats', array('entity_type' => 'viewarticle', 'entity_id' => $id)); 
    $gridArticlePageviews = new ctlDataGrid($r, 'pageviews', $basequery, $module);
    $descriptor = $runtime->rf('main', 'sql/GetPageviewStats.columns.txt');
    $columns = $gridArticlePageviews->parse_columns_descriptor($descriptor);
    $gridArticlePageviews->set_columns(@columns);
    $gridArticlePageviews->set_width('100%');
    $gridArticlePageviews->set_tab('pageviews');
    $articleInfo['datagrid'] = $gridArticlePageviews->render();

    # render the page
    use ctlTab;
    $tcArticle = new ctlTab($r, 'tcArticle');
    $tcArticle->addTab('edit', dot('edit.tab.contents', $articleInfo), dot('edit.contents', $articleInfo));
    $tcArticle->addTab('attachments', dot('edit.tab.attachments', $articleInfo), dot('edit.attachments', $articleInfo));
    $tcArticle->addTab('readaccess', dot('edit.tab.readers', $articleInfo), dot('edit.readers', $articleInfo));
    $tcArticle->addTab('editaccess', dot('edit.tab.editors', $articleInfo), dot('edit.editors', $articleInfo));
    $tcArticle->addTab('reviewers', dot('edit.tab.reviewers', $articleInfo), dot('edit.reviewers', $articleInfo));
    $tcArticle->addTab('notifications', dot('edit.tab.notifications', $articleInfo), dot('edit.notifications', $articleInfo)) if ($articleInfo['draft'] <> 1 && $articleInfo['deleted'] <> 1);
    $tcArticle->addTab('pageviews', dot('edit.tab.pageviews', $articleInfo), dot('edit.pageviews', $articleInfo)) if ($articleInfo['datagrid'] <> ''); # not just created
    $tcArticle->setDefaultTab(lavnn('tab')) if (lavnn('tab') <> '');
    $articleInfo['tabcontrol'] = $tcArticle->getHTML();
    $runtime->saveMoment('  rendered tab control');
    $page->add('title',  $articleInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.edit', $articleInfo);
    $page->add('main', $runtime->txt->do_template($module, 'edit', $articleInfo);

    # register pageview
    $runtime->db->sqlrun('main', 'RegisterPageview', array('entity_type' => 'viewarticle', 'entity_id' => $id, 'viewer_type' => 'U', 'viewer_id' => $r['userID']));
  }  
} else {
  $page->add('title',  $articleInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.notfound');
  $page->add('main', $runtime->txt->do_template($module, 'notfound', $articleInfo);
}

$page['js'] .= $runtime->txt->do_template($module, 'read.js');
$page['js'] .= $runtime->txt->do_template('main', 'tabcontrol.js');
$page['js'] .= $runtime->txt->do_template('main', 'linkpeople.js');
$page->add('css',  $runtime->txt->do_template('main', 'tabcontrol.css');
$page->add('css',  $runtime->txt->do_template('main', 'linkpeople.css');
$page->add('css',  $runtime->txt->do_template($module, 'css');


  

?>
