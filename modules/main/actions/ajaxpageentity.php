<?php

$_REQUEST['chars'] = 2 if (lavnn('chars') == '');
$type = lavnn('type');
if ($type == 'runreport') {
  $reports = $runtime->s2a('reports', 'ListPredefinedReports');
  $params = array('reports' => $reports);
  print $r->txt->do_template('reports', 'ajaxpageentity', $params);
} elseif ($type == 'viewarticle') {
  print $r->txt->do_template('articles', 'ajaxpageentity', $params);
} elseif ($type == 'ticket') {
  print $r->txt->do_template('tickets', 'ajaxpageentity', $params);
} else {
  print 'not supported yet';
}
print $r->txt->do_template($module, 'ajaxpageentity', $_REQUEST);


?>
