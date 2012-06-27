<?php

$source = lavnn('src');
$controlname = lavnn('controlname');
if ($source <> '' && $controlname <> '') {
  $pageParams = array('src' => $source, 'controlname' => $controlname);
  $links = $runtime->s2a($module, 'ListHotelsLinks', $_REQUEST);
  if (count($links) > 0) {
    $links_bylinktype = slice_array($links, 'link_type');
    @links = array();
    foreach $link ($links_bylinktype['1']}) {
      $link['locations'] = explainLocations($link['locations']) || dot('linkhotels.locations.all');
      $link['hotels'] = explainHotels($link['hotels']) || dot('linkhotels.hotels.all');
      $link['controlname'] = $controlname;
      push @links, dot('linkhotels.listitem.location', $link);
    }
    print $r->txt->do_template($module, 'linkhotels.list', array('controlname' => $controlname, 'links' => join('', $links)));
  } else {
    print $r->txt->do_template($module, 'linkhotels.list', array('controlname' => $controlname, 'links' => dot('linkhotels.none')));
  }
}

function explainLocations {
  ($ids)

  if ($ids <> '') {
    $locations = $runtime->s2a($module, 'ListHotelLocations', array('ids' => $ids));  
    if (count($locations) > 0) {
      return join_column(', ', 'location_name', $locations);
    }
  }
  return '';
}

function explainHotels {
  ($ids)

  if ($ids <> '') {
    $hotels = $runtime->s2a($module, 'ListHotels', array('ids' => $ids));  
    if (count($hotels) > 0) {
      return join_column(', ', 'hotel_name', $hotels);
    }
  }
  return '';
}


1;
 
?>
