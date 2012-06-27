<?php

$familymemberInfo = array('book_id' => $_REQUEST['book_id']);
if (lavnn('id') <> '') {
  %familymemberInfo = $runtime->s2r($module, 'GetFamilyMemberInfo', $_REQUEST);
}
$relationships = $runtime->s2a($module, 'ListRelationships'); 
$familymemberInfo['relationshipoptions'] = arr2ref(genOptions($relationships, 'rel_id', 'rel_name', $familymemberInfo['relationship_id']));
 
print $r->txt->do_template($module, 'bookfamilytooltip', $familymemberInfo);

?>
