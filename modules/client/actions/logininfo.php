<?php

$cinfo = $r['clientInfo'];

if (count($cinfo) > 0 && $r['clientID'] <> '') {
  print $runtime->txt->do_template('clients', 'logininfo', $cinfo);
} else {
  print $runtime->txt->do_template('clients', 'pleaselogin');
}

1;

?>
