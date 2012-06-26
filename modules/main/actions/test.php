<?php

$page->add('title',  'Test');
print $runtime->txt->do_template($module, 'index', $page);

?>
