<?php

$id = lavnn('id', $_REQUEST, '');
$articleInfo = array();
if ($id <> '') {
  # First of all, get article data
  $articleInfo = $objA->get_article(('id' => $id)); 
  $runtime->saveMoment('  fetched article data from db');
  
  $user_id = lavnn('user_id') || $r['userID'];
  if ($user_id <> $r['userID']) {

    # TODO Write some code for rendering user information if alternative user was selected
    # TODO Show differently for usual article and the one of type 'empform'
    
    if ($articleInfo['Type'] <> 'empform') {
      $articleInfo['userInfo'] = $runtime->txt->do_template($module, 'userinfo.not.empform');
    } else {
      $userInfo = $runtime->s2r('staff', 'GetEmployeeDetails', array('id' => $user_id));
      # also, check if user has view rights 
      if (count($userInfo) > 0) {
        #Substitute logged user in runtime, so other modules will treat user as current one
        $r['userID'] = $user_id;
        $r['userInfo'] = $userInfo;
        $articleInfo['userInfo'] = $runtime->txt->do_template($module, 'userinfo.empform', $userInfo);
      } else {
        $articleInfo['userInfo'] = $runtime->txt->do_template($module, 'userinfo.notfound');
      }
    }

  } else {
    if ($acc['superadmin'] == 1 || $articleInfo['Author'] == ($r['userID']) || $acc->check_resource("editarticle:$id", $r['userID'])) {
      $articleInfo['moreaccess'] .= $runtime->txt->do_template($module, 'moreaccess.edit', $articleInfo);
    }
    if ($acc->check_resource("articlereviewers:$id", $r['userID'])) {
      $articleInfo['moreaccess'] .= $runtime->txt->do_template($module, 'moreaccess.review', $articleInfo);
    }
    if ($acc['superadmin'] <> 1 && $articleInfo['Author'] <> ($r['userID']) && !$acc->check_resource("readarticle:$id", $r['userID'])) {
      %articleInfo = array();
    }
  }
  
  if (count($articleInfo) > 0) { 
    # Handle all custom article types
    $type = $articleInfo['Type'];
    if ($type == 'empform') {
      $submitInfo = $runtime->s2r($module, 'GetEmployeeFormSubmitDetails', array('article_id' => $id, 'employee_id' => $r['userID'])); 
      if (count($submitInfo) > 0) {
        $articleInfo['submit'] = $runtime->txt->do_template($module, 'view.empform.submitted', $submitInfo);
      } else {
        $articleInfo['submit'] = $runtime->txt->do_template($module, 'view.empform.submit', $articleInfo);
      }
    } 
  
    $articleInfo['articleInfo'] = $objA->render_article('view', $articleInfo);
    # render the page
    $page->add('title',  $articleInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.view', $articleInfo);
    $page->add('main', $runtime->txt->do_template($module, 'view', $articleInfo);

    # register pageview
    $runtime->db->sqlrun('main', 'RegisterPageview', array('entity_type' => 'viewarticle', 'entity_id' => $id, 'viewer_type' => 'U', 'viewer_id' => $r['userID']));
  } else {
    $page->add('title',  $articleInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.notfound');
    $page->add('main', $runtime->txt->do_template($module, 'notfound', $articleInfo);
  } 
  
  # Restore original runtime user info if needed
  if ($user_id <> $this['userID']) {
    $r['userID'] = $this['userID'];
    $r['userInfo'] = $this['userInfo'];
  }
} else {
  $page->add('title',  $articleInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.notfound');
  $page->add('main', $runtime->txt->do_template($module, 'notfound', $articleInfo);
}

$page->add('css',  $runtime->txt->do_template($module, 'css');


  

?>
