<?php

$op = lavnn('op', $_REQUEST, '');
$ids = array();
while (($request_key, $request_value) = each $_REQUEST) {
  my($prefix, $suffix) = split('_', $request_key);
  if ($prefix == 'id' && $suffix <> '') {
    push @ids, $suffix;
  }
}
if (count($ids) > 0 && $op <> '') {
  if ($op == 'markdeleted') {
    $runtime->db->sqlrun($module, 'MarkArticlesAsDeleted', array('ids' => join(',', @ids)));
    $_SESSION['flash'] = count($ids)." articles marked as Deleted");
  } elseif ($op == 'marknotdeleted') {
    $runtime->db->sqlrun($module, 'MarkArticlesAsNotDeleted', array('ids' => join(',', @ids)));
    $_SESSION['flash'] = count($ids)." articles marked as Not Deleted");
  } elseif ($op == 'markdraft') {
    $runtime->db->sqlrun($module, 'MarkArticlesAsDraft', array('ids' => join(',', @ids)));
    $_SESSION['flash'] = count($ids)." articles marked as Draft");
  } elseif ($op == 'marknotdraft') {
    $runtime->db->sqlrun($module, 'MarkArticlesAsNotDraft', array('ids' => join(',', @ids)));
    $_SESSION['flash'] = count($ids)." articles marked as Not Draft");
  }
} else {
    $_SESSION['error'] = "Select some items in order to run multiple operation");
}

go('?p='.$module.'/myarticles');

?>
