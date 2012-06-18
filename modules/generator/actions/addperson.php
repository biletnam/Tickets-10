<?php
$n = lavnn('n', $_REQUEST, 0);
$pageParams = array('n' => $n);
$pageParams['relationships'] = arr2ref($runtime->getDictArr($module, 'relationship')); 
$nationalities = $runtime->s2a($module, 'ListNationalities'); 
$pageParams['nationalities'] = arr2ref(genOptions($nationalities, 'nationality_id', 'nationality_name'));
print dot('newrequest.addperson', $pageParams);
?>
