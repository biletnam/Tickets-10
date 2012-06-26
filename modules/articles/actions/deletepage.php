<?php

$pageInfo = $objA->get_page($_REQUEST);
$article = $pageInfo['article'] || 0;
$objA->delete_page($_REQUEST); 

go("?i=$module/pages&article=$article");

?>
