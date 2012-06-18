<?php
$report = lavnn('report', $_REQUEST, '');
if ($report <> '') {
  $results = $runtime->s2a($module, $report, $reportParams); 
  print $r['txt']print_table_csv($results); 
}
?>
