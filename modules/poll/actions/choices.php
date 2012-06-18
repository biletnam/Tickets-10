<?php

$choices = $runtime->s2a($module, 'ListDropdownChoices', array('id' => lavnn('question')));
print dot('question.dropdown.edit.choices', array('id' => lavnn('question'), 'choices' => $choices));

?>
