<?php
$id = lavnn('id', $_REQUEST, 0);
$resortInfo = array(); 
if ($id > 0) {
  %resortInfo = $runtime->s2r($module, 'GetExtResortData', $_REQUEST); 
}
$countryoptions = arr2ref(s2a($module, 'ListCountries'));
$resortInfo['countryoptions'] = arr2ref(genOptions($countryoptions, 'country_id', 'country_name', $resortInfo['country']));
print dot('extresorttooltip', $resortInfo);
?>
