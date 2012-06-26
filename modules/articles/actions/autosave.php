<?php
$runtime->db->sqlrun($module, 'SetArticleDraft', $_REQUEST);
print $runtime->txt->do_template($module, 'autosaved');
?>
