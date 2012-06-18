<?php
#formdebug(%_REQUEST); die();

$src = lavnn('src', $_REQUEST, '');
$controlname = lavnn('controlname', $_REQUEST, '');
($source_type, $source_id) = split(':', $src, 2);
$sqlParams = array('source_link' => $src, 'source_type' => $source_type, 'source_id' => $source_id);
Arrays::copy_fields($sqlParams, $_REQUEST, qw(locations hotels));
$type = lavnn('type', $_REQUEST, '');
if ($type == 'location') {
  $sqlParams['link_type'] = 1;
}
if ($sqlParams['link_type'] <> '') {
  $newid = sid($module, 'AddHotelsLink', $sqlParams);
  if ($newid > 0) {
    $_REQUEST['flash'] = $runtime->txt->do_template($module, 'ajaxmessage.flash', array('controlname' => $controlname, 'text' => 'Successfully added'));
  } else {
    $_REQUEST['error'] = $runtime->txt->do_template($module, 'ajaxmessage.error', array('controlname' => $controlname, 'text' => 'Adding failed'));
  }
}
print dot('linkhotels.add', $_REQUEST);

1;

?>
