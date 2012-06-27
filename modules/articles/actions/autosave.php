<?php
$runtime->db->sqlrun($module, 'SetArticleDraft', $_REQUEST);
print $r->txt->do_template($module, 'autosaved');
?>
