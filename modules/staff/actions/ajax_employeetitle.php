<?php
$params = array();
$titles = $runtime->s2a($module, 'ListJobTitles');
$titleoptions = genOptions($titles, 'lngId', 'strName');
$params['titleoptions'] = $titleoptions;
print dot('employeetitle', $params);
?>
