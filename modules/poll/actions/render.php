<?php
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  print $objP->render(('id' => $id));
} else {
  print dot('notfound');
}

?>
