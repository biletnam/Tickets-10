<?php

$pageParams = array();
$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  %pageParams = $runtime->s2r('resorts', 'GetHotelInfo', array('id' => $id));
  $pageParams['pagetitle'] = $page->add('title',  $r->txt->do_template($module, 'edithotel.title', $pageParams); 
} else {
  $pageParams['pagetitle'] = $page->add('title',  $r->txt->do_template($module, 'edithotel.new.title', $pageParams); 
}
$page->add('main', $r->txt->do_template($module, 'edithotel', $pageParams);



?>
