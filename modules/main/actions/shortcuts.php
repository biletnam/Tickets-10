<?php
$shortcuts = $runtime->s2a($module, 'ListUserShortcuts', array('user_id' => $r['userID']));
print dot('shortcuts', array('shortcuts' => $shortcuts));
?>
