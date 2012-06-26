<?php

$pages = $runtime->s2a($module, 'GetArticlePages', array('id' => $_REQUEST['article'])); 
print $runtime->txt->do_template($module, 'pages', array('pages' => $pages));

?>
