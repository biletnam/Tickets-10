<?php

# Save module context for later use
our %_OLDMODULE = %_MODULE;

%_MODULE = array(
  'name' => 'tickets'
);

$runtime->set_module($moduleODULE);
$module = $moduleODULE['name'];

use objTicketing;
our $objT = new objTicketing($r);
$runtime->saveMoment('ticketing business object is created');

1;

?>
