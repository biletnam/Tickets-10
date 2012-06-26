<?php

$id = lavnn('id');
if ($id > 0) {
  $articleInfo = $objA->get_article(('id' => $id, 'mode' => 'minimum'));
  if (count($articleInfo) > 0) {
    $_SESSION['flash'] = 'Form submitted!') if ($objA->submit_empform(('id' => $id)) > 0);
    go("?p=$module/view&id=$id");
  } else {
    $_SESSION['error'] = 'Article not found!');
  }
} else {
  $_SESSION['error'] = 'Malformed request!');
}
go("$module/home");

?>
