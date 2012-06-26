<?php

# inline action called from main/dashboard

$dashboard_articleage = $r['userInfo']['additionalData']['dashboard_articleage'] || 30; 
$sqlParams = array(
  'id' => $r['userID'], 
  'office' => $r['userInfo']['lngWorkPlace'].'',
  'department' => $r['userInfo']['team_id'].'',
  'sourcetype' => "readarticle','editarticle", 
  'uniqueblock' => 'yes',
  'skiptagprefix' => 'special',
  'age' => $dashboard_articleage
);

$articles = $objA->search_articles(%sqlParams);
$pageParams = array('days' => $dashboard_articleage);
$pageParams['articles'] = $articles;
print $runtime->txt->do_template($module, 'dashboard', $pageParams);

?>
