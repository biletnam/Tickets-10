<?php

# Define help variable
$HELP = "
  MANDATORY
  resort_id       int
  travel_date     varchar(10) DD.MM.YYYY
  
  OPTIONAL
  flexible        int, default = 0
  nights          int, default = 7
  apt_type_id     int
  
  SUCCESSFUL 
  <AvailabilityInfo>
    <SlotGroups>
      <SlotGroup>
        <StartDate />
        <Nights> />
        <Slots>
          <Slot>
            <HardBlockID />
            <AptNo />
            <LocalAptTypeName />
            <AptTypeTypeName />
          </Slot>
        </Slots>
      </SlotGroup>
    </SlotGroups>
  </AvailabilityInfo>
";

# Return HELP string if it is requested
if (exists $apiparams['_help_']) {
  use HTML::Entities;
  print "content-type: text/html; charset=utf-8\n\n";
  print "<pre>".encode_entities($HELP);
  exit();  
}

# Check if built-in parameter values are overloaded
$contenttype = $apiparams['_contenttype_'] || 'text/xml';
$charset = $apiparams['_charset_'] || 'utf-8';
$format = $apiparams['_format_'] || '';
 
# Initialize first-level variables that appear in API output 
$result = 'OK';
$output = '';
$warnings = array();
$errors = array();

# API logic - check username and password
$resort_id = $apiparams['resort_id'] || '';
$travel_date = $apiparams['travel_date'] || '';
$flexible = $apiparams['flexible'] || 0;
$nights = $apiparams['nights'] || 7;
$apt_type_id = $apiparams['apt_type_id'] || ''; # optional

if ($resort_id <> '' && $travel_date <> '') {
  $slotgroups = array();
  for ($i = -$flexible; $i <= $flexible; $i++) {
    $nestedcallparams = array(
      'resort_id' => $resort_id, 
      'travel_date' => $travel_date, 
      'flexible' => $i, 
      'nights' => $nights,
    );
    $slots = $runtime->s2a($controller, 'GetAvailabilitySlots2', $nestedcallparams);
    if (count($slots) > 0) {
      $availabilityInfo = $slots[0]; 
      $availabilityInfo['slots'] = $slots;
      push @slotgroups, $runtime->$runtime->txt->do_template($controller, 'API.AvailabilityInfo2.SlotGroup', $availabilityInfo);
    } 
  }
  if (count($slotgroups) > 0) {
    $availabilityInfo['slotgroups'] = join('', @slotgroups);
  }
  $output = $runtime->$runtime->txt->do_template($controller, 'API.AvailabilityInfo2', $availabilityInfo);
  
} else {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'GetResortAvailability.InvalidInputParams', 'text' => 'Either resort or dates are not provided') );
}

# Return resulting XML API output - quite similar to all APIs
print "content-type: $contenttype; charset=$charset;\n\n";
print $runtime->$runtime->txt->do_template($controller, 'API.Envelope', array(
  'result' => $result,
  'output' => $output,
  'warnings' => Arrays::a2xml($warnings, 'Warnings', 'Warning'),
  'errors' => Arrays::a2xml($errors, 'Errors', 'Error'),
));


1;

?>
