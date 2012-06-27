<?php

$choices = $runtime->s2a($module, 'ListDropdownChoices', array('id' => lavnn('question')));
print $r->txt->do_template($module, 'question.dropdown.edit.choices', array('id' => lavnn('question'), 'choices' => $choices));

?>
