<?php


$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {

  # Fix resulting list of tags, including those who have their proper input fields
  $_REQUEST['tags'] =~ s/:\s+/:/g;  # fixes gaps between colon and tag
  $_REQUEST['tags'] =~ s/:+/:/g; # fixes several colons in a row
  $newtags = array();
  foreach $newtag (split(',', $_REQUEST['tags'])) {
    push @newtags, trim($newtag); 
  }
  $articletype = lavnn('type', $_REQUEST, '');
  if ($articletype <> '') {
    push @newtags, "type:$articletype";
  }

  # automatically add blocks to tags
  $articleblocks = lavnn('blocks');
  if (count($articleblocks) > 0) {
    foreach $block (@articleblocks) {
      push @newtags, "block:$block";
    }
  }

  $_REQUEST['tags'] = join(', ', @newtags);  
  $_REQUEST['editor'] = $r['userInfo']['staff_id']; 
  srun($module, 'UpdateArticle', $_REQUEST); 
  srun($module, 'InsertArticleHistory', $_REQUEST);  
  # Get back full article info
  $articleInfo = $runtime->s2r($module, 'GetArticleData', $_REQUEST);

  ############ TAG HANDLING: we also store tags in cache (database table)
  
  # get existing state of cache for this article
  @existingtags = $runtime->s2a($module, 'GetArticleTags', array('id' => $id));
  %oldtags = slice_array($existingtags, 'fulltag');
  
  # add new tags
  foreach $newtag (@newtags) {
    if (!exists $oldtags{$newtag}) {
      ($prefix, $tag) = split(':', $newtag, 2);
      if ($tag == '') { #obviously, this means that tag without prefix was used
        $tag = $prefix; $prefix = ''; $newtag = ":$newtag";
      }
      %taginfo = array('article' => $id, 'fulltag' => $newtag, 'prefix' => $prefix, 'tag' => $tag);
#      formdebug($taginfo); print spreview($module, 'AddArticleTag', $taginfo); 
      srun($module, 'AddArticleTag', $taginfo);
    }
  }
  # remove obsolete tags
  foreach $oldtag (keys %oldtags) {
    if (!in_array($oldtag, $newtags)) {
      %taginfo = array('article' => $id, 'fulltag' => $oldtag);
      srun($module, 'RemoveArticleTag', $taginfo);
    }
  }
  
  set_cookie('flash', 'Article saved');
  go("?p=$module/edit&id=$id");
} else {
  go("?p=$module/myarticles");
}

?>
