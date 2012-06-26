<?php
$pageParams = $_REQUEST;
$page['menu'] = '';
$page->add('main', $runtime->txt->do_template($module, 'promptlogin', $pageParams);
print $runtime->txt->do_template($module, 'index', $page);

?>
