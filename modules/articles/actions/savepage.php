<?php

$partInfo = $objA->save_page(%_REQUEST); 
$article = $partInfo['article'] || 0;

go("?i=$module/pages&article=$article");

?>
