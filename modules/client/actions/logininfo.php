<?php

$cinfo = $r['clientInfo'];

if (count($cinfo) > 0 && $r['clientID'] <> '') {
  print $r->txt->do_template('clients', 'logininfo', $cinfo);
} else {
  print $r->txt->do_template('clients', 'pleaselogin');
}

1;

?>
