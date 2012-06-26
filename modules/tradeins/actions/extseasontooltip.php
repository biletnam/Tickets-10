<?php
$id = lavnn('id', $_REQUEST, 0);
$seasonInfo = array(); 
if ($id > 0) {
  %seasonInfo = $runtime->s2r($module, 'GetExtSeasonData', $_REQUEST); 
}
$resortoptions = arr2ref(s2a($module, 'ListExtResorts'));
$seasonInfo['resortoptions'] = arr2ref(genOptions($resortoptions, 'id', 'name', $seasonInfo['resort']));
print $runtime->txt->do_template($module, 'extseasontooltip', $seasonInfo);
?>
