<?php

$page['nextURL'] = lavnn('url');
$page->add('title',  $page['pagetitle'] = $runtime->doTemplate($module, 'title.iforgot', $page);
$page->add('main', $runtime->doTemplate($module, 'iforgot', $page);



?>
