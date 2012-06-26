<?php
$shortcuts = $runtime->s2a($module, 'ListUserShortcuts', array('user_id' => $r['userID']));
print $runtime->txt->do_template($module, 'shortcuts', array('shortcuts' => $shortcuts));
?>
