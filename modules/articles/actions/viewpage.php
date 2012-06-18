<?php
$article = lavnn('article', $_REQUEST, '');
$page = lavnn('page', $_REQUEST, '');
print $objA->render_page($article, $page);
?>
