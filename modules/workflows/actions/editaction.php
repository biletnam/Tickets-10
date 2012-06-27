<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $actionInfo = $runtime->s2r($module, 'GetActionInfo', $_REQUEST);
  if (count($actionInfo) > 0) {
    $page->add('title',  $actionInfo['pagetitle'] = $r->txt->do_template($module, 'editaction.title', $actionInfo);
    $atype = $actionInfo['action_type'];
    $params = $runtime->s2r($module, 'GetActionParams', $_REQUEST); 
    $paramvalues = Arrays::array2hash($params, 'name', 'value');
     
    #print Dumper($params); print Dumper($paramvalues);
    
    $actionparams = array();
    
    if ($atype == 'createticket') {
      push @actionparams, $objW->render_parameter(('pcaption'=> 'Creator', 'pname' => 'creator', 'ptype' => 'employee', 'pvalue' => $paramvalues['creator']));
      push @actionparams, $objW->render_parameter(('pcaption'=> 'Reviewer', 'pname' => 'reviewer', 'ptype' => 'employee', 'pvalue' => $paramvalues['reviewer']));
      push @actionparams, $objW->render_parameter(('pcaption'=> 'Handler', 'pname' => 'handler', 'ptype' => 'employee', 'pvalue' => $paramvalues['handler']));
      push @actionparams, $objW->render_parameter(('pcaption'=> 'Title', 'pname' => 'title', 'ptype' => 'string', 'pvalue' => $paramvalues['title']));
      push @actionparams, $objW->render_parameter(('pcaption'=> 'Priority', 'pname' => 'priority', 'ptype' => 'dict:tickets.priority', 'pvalue' => $paramvalues['priority']));
    } else {
      $atype = 'unknown';
    }
    if (count($actionparams) > 0) {
      $actionInfo['parameters'] = join('', $actionparams);
      $actionInfo['actionparams'] = $r->txt->do_template($module, "editaction.parameters", $actionInfo);
    }
    $page->add('main', $r->txt->do_template($module, "editaction", $actionInfo);
  } else {
    $id = 0;
  }
}
if ($id == 0) {
  %pageParams = array();
  $page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'editaction.title.notfound');
  $page->add('main', $r->txt->do_template($module, 'editaction.notfound', $pageParams);
}



  
?>
