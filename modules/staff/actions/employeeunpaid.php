<?php

$id = lavnn('id', $_REQUEST, 0);
print "<!--" . spreview($module, 'ListEmployeeUnpaidLeave', array('employee' => $id)) . "-->";
$unpaid = $runtime->s2a($module, 'ListEmployeeUnpaidLeave', array('employee' => $id));
print $r->txt->do_template($module, 'unpaidtooltip', array('months' => $unpaid));

?>
