<?php

# initialize page params using request parameters
%pageParams = array(
  'gen_id' => $gen_id,
  'baseurl' => 'http://' . $ENV['SERVER_NAME'] . $r['config']['BASEURL_SCRIPTS'] ,
  'logoffurl' => 'http://www.clubabsolute.ru/logoff.php', 
  'msg' => $msg,
  'lang' => $lang,
);

# generate options for selecting a hotel. 
$arr = $runtime->s2a($module, 'ListGeneratorHotelLocations', array('id' => $gen_id));
$pageParams['locations'] = arr2ref(genOptions($arr, 'id', 'location_name', $_REQUEST['location_id'])); 

# add options for marital status and apartment types
$pageParams['relationships1'] = arr2ref($runtime->getDictArr($module, 'relationship', $_REQUEST['rel1'])); 
$pageParams['relationships2'] = arr2ref($runtime->getDictArr($module, 'relationship', $_REQUEST['rel2'])); 
$nationalities = $runtime->s2a($module, 'ListNationalities'); 
$pageParams['nationalities1'] = arr2ref(genOptions($nationalities, 'nationality_id', 'nationality_name', $pageParams['nationality1']));
$pageParams['nationalities2'] = arr2ref(genOptions($nationalities, 'nationality_id', 'nationality_name', $pageParams['nationality2']));
@arr = $runtime->s2a($module, 'ListApartmentTypes');
$pageParams['apttypes'] = arr2ref(genOptions($arr, 'type_id', 'type_name', $_REQUEST['apttype']));
if ($lang == 'ru') {
  @arr = $runtime->s2a($module, 'ListCountriesRussian'); @arr = array();
} else {
  @arr = $runtime->s2a($module, 'ListCountries');
}
$pageParams['countries'] = arr2ref(genOptions($arr, 'country_id', 'country_name'), $_REQUEST['country_id']);

# render the form
$page['js'] .= $runtime->txt->do_template($module, 'newrequest.addperson.js', $pageParams);
$page['js'] .= $runtime->txt->do_template($module, 'newrequest.validation.js');
$page['baseurl'] = $pageParams['baseurl'];
$page->add('main', $runtime->txt->do_template($module, 'newrequest', $pageParams);
print $runtime->txt->do_template($module, 'index', $page);

?>
