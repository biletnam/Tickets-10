<?php

use objNotification;
$objN = new objNotification($r);

$cnt_latest = $objN->count_latest($r['userID']);
print $runtime->txt->do_template($module, 'notifications', array('cnt' => $cnt_latest)) if $cnt_latest > 0;

1;

?>
