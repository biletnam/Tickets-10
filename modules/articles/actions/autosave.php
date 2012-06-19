<?php
$runtime->db->sqlrun($module, 'SetArticleDraft', $_REQUEST);
print dot('autosaved');
?>
