<?php
$reference = lavnn('reference');
$id = lavnn('id');
if ($id <> '' && $reference <> '') {
  $runtime->($module, 'SetVoucherReference', $_REQUEST);
}
print $reference;
?>
