<?php

$pages = $runtime->s2a($module, 'GetArticlePages', array('id' => $_REQUEST['article'])); 
print dot('pages', array('pages' => $pages));

?>
