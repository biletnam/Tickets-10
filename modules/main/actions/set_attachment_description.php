<?php
$runtime->db->sqlrun($module, 'SetAttachmentDescription', $_REQUEST);
$attInfo = $runtime->s2r('profiles', 'GetAttachmentDetails', $_REQUEST);
print $runtime->txt->do_template($module, 'attachment.description', $attInfo);
?>
