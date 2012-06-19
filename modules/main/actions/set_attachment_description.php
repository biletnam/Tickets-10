<?php
$runtime->db->sqlrun($module, 'SetAttachmentDescription', $_REQUEST);
$attInfo = $runtime->s2r('profiles', 'GetAttachmentDetails', $_REQUEST);
print dot('attachment.description', $attInfo);
?>
