<?php

$src = lavnn('src', $_REQUEST, '');
$controlname = lavnn('controlname', $_REQUEST, '');
($source_type, $source_id) = split(':', $src, 2);
$sqlParams = array('source_link' => $src, 'source_type' => $source_type, 'source_id' => $source_id);
Arrays::copy_fields($sqlParams, $_REQUEST, qw(offices departments people));
$type = lavnn('type', $_REQUEST, '');
if ($type == 'office') {
  $sqlParams['link_type'] = 1;
} elseif ($type == 'department') {
  $sqlParams['link_type'] = 2;
} elseif ($type == 'person') {
  $sqlParams['link_type'] = 3;
}
if ($sqlParams['link_type'] <> '') {
  $newid = $runtime->sid($module, 'AddPersonLink', $sqlParams);
  if ($newid > 0) {
    $_REQUEST['flash'] = $r->txt->do_template($module, 'ajaxmessage.flash', array('controlname' => $controlname, 'text' => 'Successfully added'));
  } else {
    $_REQUEST['error'] = $r->txt->do_template($module, 'ajaxmessage.error', array('controlname' => $controlname, 'text' => 'Adding failed'));
  }
}
print $r->txt->do_template($module, 'linkpeople.add', $_REQUEST);

1;

?>
