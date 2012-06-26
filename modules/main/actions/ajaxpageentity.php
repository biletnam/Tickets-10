<?php

$_REQUEST['chars'] = 2 if (lavnn('chars') == '');
$type = lavnn('type');
if ($type == 'runreport') {
  $reports = $runtime->s2a('reports', 'ListPredefinedReports');
  $params = array('reports' => $reports);
  print $runtime->txt->do_template('reports', 'ajaxpageentity', $params);
} elseif ($type == 'viewarticle') {
  print $runtime->txt->do_template('articles', 'ajaxpageentity', $params);
} elseif ($type == 'ticket') {
  print $runtime->txt->do_template('tickets', 'ajaxpageentity', $params);
} else {
  print 'not supported yet';
}
print $runtime->txt->do_template($module, 'ajaxpageentity', $_REQUEST);


?>
