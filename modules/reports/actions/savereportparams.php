<?php

$report = lavnn('report', $_REQUEST, 0);
if ($report > 0) {
  # TODO update existing (and updated via drity flag) descriptions
  $ids = lavnn('dirty');
  foreach $id (@ids) { 
    srun($module, 'UpdateParameter', array(
      'id' => $id,
      'name' => lavnn("name_$id"), 
      'prompt' => lavnn("prompt_$id"),
      'description' => lavnn("description_$id"),
      'mandatory' => lavnn("mandatory_$id"),
    )) if $id <> '';
  }
  
  # Add new parameter if specified
  $newname = lavnn('name_new', $_REQUEST, '');
  $newprompt = lavnn('prompt_new', $_REQUEST, '');
  $newtype = lavnn('type_new', $_REQUEST, '');
  $newdescription = lavnn('description_new', $_REQUEST, '');
  $newmandatory = lavnn('mandatory_new', $_REQUEST, '');
  if ($newname <> '' && $newprompt <> '' && $newtype <> '') {
    $newid = sid($module, 'InsertParameter', array(
      'report' => $report,
      'name' => $newname, 
      'prompt' => $newprompt,
      'type' => $newtype,
      'description' => $newdescription,
      'mandatory' => $newmandatory
    ));
    set_cookie('flash', 'New parameter added!') if ($newid > 0);
  }

  go("?p=reports/editreport&id=$report&tab=params");
}

go("?p=reports/editgallery");

?>
