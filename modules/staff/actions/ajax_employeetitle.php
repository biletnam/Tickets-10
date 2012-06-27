<?php
$params = array();
$titles = $runtime->s2a($module, 'ListJobTitles');
$titleoptions = genOptions($titles, 'lngId', 'strName');
$params['titleoptions'] = $titleoptions;
print $r->txt->do_template($module, 'employeetitle', $params);
?>
