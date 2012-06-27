<?php

$uinfo = $r['userInfo'];

if (count($uinfo) > 0 && $r['userID'] <> '') {
  $uinfo['url'] = lavnn('url'); 
  print $r->txt->do_template('users', 'logininfo', $uinfo);
} else {
  print $r->txt->do_template('users', 'pleaselogin');
}

1;

?>
