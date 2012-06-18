<?php

$id = lavnn('id', $_REQUEST, 0);
print "<!--" . spreview($module, 'ListEmployeeUnpaidLeave', array('employee' => $id)) . "-->";
$unpaid = $runtime->s2a($module, 'ListEmployeeUnpaidLeave', array('employee' => $id));
print dot('unpaidtooltip', array('months' => $unpaid));

?>
