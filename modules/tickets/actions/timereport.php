<?php
$_REQUEST['reportresults'] = "Time Report"; 
$_REQUEST['user_id'] = lavnn('user_id') || $r['userID'];
$results = $runtime->s2a($module, 'GetUserReportingAggregated', $_REQUEST);
$_REQUEST['results'] = $results;
print dot('usertime.report.results', $_REQUEST);

?>