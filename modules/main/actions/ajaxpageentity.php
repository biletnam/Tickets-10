<?php

$_REQUEST['chars'] = 2 if (lavnn('chars') == '');
$type = lavnn('type');
if ($type == 'runreport') {
  $reports = $runtime->s2a('reports', 'ListPredefinedReports');
  $params = array('reports' => $reports);
  print dotmod('reports', 'ajaxpageentity', $params);
} elseif ($type == 'viewarticle') {
  print dotmod('articles', 'ajaxpageentity', $params);
} elseif ($type == 'ticket') {
  print dotmod('tickets', 'ajaxpageentity', $params);
} else {
  print 'not supported yet';
}
print dot('ajaxpageentity', $_REQUEST);


?>
