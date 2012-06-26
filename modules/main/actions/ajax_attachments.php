<?php

$doctypes = $runtime->s2a('admin', 'ListDocTypes'); 
$doctypeoptions = genOptions($doctypes, 'id', 'name');
$attachments = $runtime->s2a('main', 'ListAttachments', array(
  'type' => $_REQUEST['etype'],
  'id' => $_REQUEST['eid']
));
print $runtime->txt->do_template($module, 'ajax.attachments', array(
  'etype' => $_REQUEST['etype'],
  'eid' => $_REQUEST['eid'],
  'doctypeoptions' => $doctypeoptions,
  'attachments' => $attachments,
  'counter' => $_REQUEST['counter']
));

?>
