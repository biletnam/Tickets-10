<?php

$cinfo = $r['clientInfo'];

if (count($cinfo) > 0 && $r['clientID'] <> '') {
  print dotmod('clients', 'logininfo', $cinfo);
} else {
  print dotmod('clients', 'pleaselogin');
}

1;

?>
