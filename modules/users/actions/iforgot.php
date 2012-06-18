<?php

$page['nextURL'] = lavnn('url');
$page->add('title',  $page['pagetitle'] = $runtime->txt->do_template($module, 'title.iforgot', $page);
$page->add('main', $runtime->txt->do_template($module, 'iforgot', $page);



?>
