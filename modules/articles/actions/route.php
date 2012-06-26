<?php

$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {

  # First of all, get article data
  $articleInfo = $objA->get_article(('id' => $id, 'mode' => 'minimum')); 
  
  if (count($articleInfo) > 0) {
    # check access to the article
    $access = 'none';
    if ($acc['superadmin'] == 1 || $articleInfo['Author'] == ($r['userID'])) {
      $access = 'edit';
    } elseif ($acc->check_resource("editarticle:$id", $r['userID'])) {
      $access = 'edit';
    } elseif ($acc->check_resource("articlereviewers:$id", $r['userID'])) {
      $access = 'review';
    } elseif ($acc->check_resource("readarticle:$id", $r['userID'])) {
      $access = 'read';
    }
    $runtime->saveMoment('  access checked with result: '.$access);
    if ($access == 'none') {
      $_SESSION['error'] = 'You have no permissions to access article "' . $articleInfo['Title'] . '"');
      # fall through to default redirect
    } elseif ($access == 'read') {
      go("?p=$module/view&id=$id");
    } elseif ($access == 'review') {
      go("?p=$module/review&id=$id");
    } elseif ($access == 'edit') {
      go("?p=$module/edit&id=$id");
    }
  }
}

# Redirect to homepage if no good route was found for the request
go("?p=$module/home");
?>
