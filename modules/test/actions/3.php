<?php
      $html = "here is the link: #/intranet_pictures/1.avatar.png#; ";
      print "before: $html";
      $serverbase = 'SERVERURL_EXTERNAL';
      $baseurl_pictures = $r['config']['BASEURL_PICTURES'];
      $fullurl_pictures = $r['config']{$serverbase} . $r['config']['BASEURL_PICTURES'];
      $html =~ s/$baseurl_pictures/$fullurl_pictures/g;
      print "after: $html";
      
?>
