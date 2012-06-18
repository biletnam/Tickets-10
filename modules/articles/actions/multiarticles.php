<?php

$op = lavnn('op', $_REQUEST, '');
$ids = array();
while (($request_key, $request_value) = each %_REQUEST) {
  my($prefix, $suffix) = split('_', $request_key);
  if ($prefix == 'id' && $suffix <> '') {
    push @ids, $suffix;
  }
}
if (count($ids) > 0 && $op <> '') {
  if ($op == 'markdeleted') {
    srun($module, 'MarkArticlesAsDeleted', array('ids' => join(',', @ids)));
    set_cookie('flash', count($ids)." articles marked as Deleted");
  } elseif ($op == 'marknotdeleted') {
    srun($module, 'MarkArticlesAsNotDeleted', array('ids' => join(',', @ids)));
    set_cookie('flash', count($ids)." articles marked as Not Deleted");
  } elseif ($op == 'markdraft') {
    srun($module, 'MarkArticlesAsDraft', array('ids' => join(',', @ids)));
    set_cookie('flash', count($ids)." articles marked as Draft");
  } elseif ($op == 'marknotdraft') {
    srun($module, 'MarkArticlesAsNotDraft', array('ids' => join(',', @ids)));
    set_cookie('flash', count($ids)." articles marked as Not Draft");
  }
} else {
    set_cookie('error', "Select some items in order to run multiple operation");
}

go('?p='.$module.'/myarticles');

?>
