<?php
$_REQUEST['reportresults'] = "Time Report"; 
$_REQUEST['user_id'] = lavnn('user_id') || $r['userID'];
$results = $runtime->s2a($module, 'GetUserReportingAggregated', $_REQUEST);
$_REQUEST['results'] = $results;
print $r->txt->do_template($module, 'usertime.report.results', $_REQUEST);

?>
