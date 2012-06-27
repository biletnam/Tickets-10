<?php

$page->add('title',  'Test');
print $r->txt->do_template($module, 'index', $page);

?>
