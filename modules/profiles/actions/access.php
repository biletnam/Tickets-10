<?php

$ITEMS_PER_PAGE = lavnn('perpage') || 10; 

$pageParams = ('perpage' => $ITEMS_PER_PAGE);
$userInfo = %{$r['userInfo']};
$user_id = $r['userID'];
$resources = $acc->list_user_resources('', $user_id);
$bytype = Arrays::slice_array($resources, 'source_type'); 
#print Dumper($bytype);

$type = lavnn('type', $_REQUEST, '');
if ($type == 'followhotel') { 
  $entities = @{$bytype{$type}};
  $cnt = count($entities);
  $pageParams['accesses'] = render_entities($type, $cnt, Arrays::join_column(',', 'source_id', $entities));
  $page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, "title.access.$type"); 
  $page->add('main', $runtime->doTemplate($module, "access.$type", $pageParams);
} else {
  $accesses = array();
  foreach $type (keys %bytype) {
    $entities = @{$bytype{$type}};
#    print Dumper(@entities);
    $cnt = count($entities);
    $ee = render_entities($type, $cnt, Arrays::join_column(',', 'source_id', $entities), $ITEMS_PER_PAGE); 
    push @accesses, $ee if $ee <> '';
  }
  #print Dumper($accesses);
  $pageParams['accesses'] = join('', @accesses);
  $page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.access');
  $page->add('main', $runtime->doTemplate($module, 'access', $pageParams);
}






function render_entities {
  ($type, $cnt, $ids, $perpage)
  $perpage ||= -1;
  $allowed = qw(readarticle viewarticle followhotel editoffice editpoll);
  if (Arrays::in_array($type, $allowed)) {
    if ($cnt <= $perpage || $perpage < 0) {
      $entities = $runtime->s2a($module, "GetEntities.$type", array('ids' => $ids));  
      $access = ('type' => $type, 'cnt' => count($entities), 'entities' => $entities);
      return dot("access.$type.list", $access);
    } elseif($cnt > $perpage) {
      return dot("access.$type.stats", array('cnt' => $cnt));    
    }
  }
  return ''; # If no allowed entity types are matched, return empty string
}

?>
