<?php

$pageParams = (
  'name' => lavnn('name') || '', 
  'validity_check_date' => lavnn('validity_check_date') || '',
  'ids' => lavnn('ids') || ''
);

$locations = $runtime->s2a($module, 'ListLocations'); 
$pageParams['locationoptions'] = arr2ref(genOptions($locations, 'id', 'location_name', lavnn('location_id')));
$doctypes = $runtime->s2a('admin', 'ListDocTypes');
$pageParams['doctypeoptions'] = arr2ref(genOptions($doctypes, 'id', 'name', lavnn('doctype_id')));

$pageParams['hotels'] = arr2ref(s2a($module, 'ListHotels', $_REQUEST));
$pageParams['pagetitle'] = $page->add('title',  $runtime->doTemplate($module, 'hotels.title');
$languages = $runtime->s2a($module, 'ListBookingRequestLanguages');
$pageParams['languages'] = arr2ref(genOptions($languages, 'id', 'name'));

$page->add('main', $runtime->doTemplate($module, 'hotels', $pageParams);




?>
