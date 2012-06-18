<?php

$pageParams = ('url' => lavnn('url'));
$pageParams['title'] = $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.login');
$pageParams['main'] = $runtime->doTemplate($module, 'login', $pageParams);
print dotmod('main', 'client', $pageParams);
 
?>
