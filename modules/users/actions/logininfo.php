<?php

$uinfo = $r['userInfo'];

if (count($uinfo) > 0 && $r['userID'] <> '') {
  $uinfo['url'] = lavnn('url'); 
  print dotmod('users', 'logininfo', $uinfo);
} else {
  print dotmod('users', 'pleaselogin');
}

1;

?>
