<?php

$pageParams = array('url' => lavnn('url'));
$pageParams['title'] = $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.login');
$pageParams['main'] = $r->txt->do_template($module, 'login', $pageParams);
print $r->txt->do_template('main', 'client', $pageParams);
 
?>
