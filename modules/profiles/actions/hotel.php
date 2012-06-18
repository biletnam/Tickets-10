<?php

$id = lavnn('id');
if ($id > 0) {
  $hotelInfo = $runtime->s2r($module, 'GetHotelDetails', $_REQUEST);
  #print Dumper($hotelInfo);
  $runtime->saveMoment('  hotel info fetched from db');
  if (count($hotelInfo) > 0) {
    $page->add('title',  $hotelInfo['pagetitle'] = $runtime->doTemplate($module, 'title.hotel', $hotelInfo);
    $wallPosts = $objWall->get_posts(('entity_type' => 'hotel', 'entity_id' =>$id, $acc->check_access("edithotel:$id"))); #print Dumper($wallPosts);
    $hotelInfo['wall'] = $objWall->render($wallPosts);
    $hotelAttachments = $runtime->s2a($module, 'ListAttachments', array('entity_type' => 'hotel', 'entity_id' => $id));
    $hotelInfo['attachments'] = $hotelAttachments; 
    $page->add('main', $runtime->doTemplate($module, 'hotel', $hotelInfo);
  } else {
    $page->add('title',  $hotelInfo['pagetitle'] = $runtime->doTemplate($module, 'title.hotel.notfound');
    $page->add('main', $runtime->doTemplate($module, 'hotel.notfound', $hotelInfo);
  }
}



?>
