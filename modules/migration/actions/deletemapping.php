<?php

$mapping = $runtime->s2r($module, 'GetMappingInfo', $_REQUEST);
if (count($mapping) > 0) {
  $idfieldvalue = $mapping['id_field_value'];
  $runtime->db->sqlrun($module, 'DeleteMapping', $mapping);
  print $r->txt->do_template($module, 'mapping.ajax.deleted', $mapping);
} else {
  print 'Failed';
}

?>
