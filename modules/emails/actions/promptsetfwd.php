<?php
print $runtime->txt->do_template($module, 'search.listitem.actions.forward', $_REQUEST);
print $runtime->txt->do_template($module, 'search.listitem.ajax.clear.forwarder', $_REQUEST);
?>
