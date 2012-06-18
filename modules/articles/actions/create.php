<?php

$editor = $_REQUEST['editor'] = $r['userInfo']['staff_id'];
$id = sid($module, 'CreateArticle', $_REQUEST);
if ($id > 0) {
  $_REQUEST['id'] = $id;
  srun($module, 'InsertArticleHistory', $_REQUEST);  
  # Look if there are some tags to add
  $tags = lavnn('tags', $_REQUEST, '');
  if ($tags <> '') {
    # Fix resulting list of tags, including those who have their proper input fields
    $tags =~ s/:\s+/:/g;  # fixes gaps between colon and tag
    $tags =~ s/:+/:/g; # fixes several colons in a row
    $tt = array();
    foreach $t (split(',', $tags)) {
      ($prefix, $tag) = split(':', $t, 2);
      if ($tag == '') { #obviously, this means that tag without prefix was used
        $tag = $prefix; $prefix = ''; $newtag = ":$t";
      }
      %taginfo = array('article' => $id, 'fulltag' => $t, 'prefix' => $prefix, 'tag' => $tag);
      srun($module, 'AddArticleTag', $taginfo);
    }
  }
  # Delete draft if any
  srun($module, 'DeleteArticleDraft', array('user_id' => $editor, $article_id => ''));
  # Relocate to the same article in edit mode
  go("?p=$module/edit&id=$id");
} else {
  go("?p=_M/home");
}

?>
