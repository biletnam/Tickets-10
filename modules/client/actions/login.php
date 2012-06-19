<?php

$pageParams = array('url' => lavnn('url'));
$pageParams['title'] = $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.login');
$pageParams['main'] = $runtime->txt->do_template($module, 'login', $pageParams);
print $runtime->txt->do_template('main', 'client', $pageParams);
 
?>
