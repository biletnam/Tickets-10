<?php

# Check if built-in parameter values are overloaded
$contenttype = $apiparams['_contenttype_'] || 'text/xml';
$charset = $apiparams['_charset_'] || 'utf-8';
$format = $apiparams['_format_'] || '';
 
# Initialize first-level variables that appear in API output 
$result = 'OK';
$output = '';
$warnings = array();
$errors = array();

$id = $apiparams['id'] || 0;
if ($id > 0) {
  $articleInfo = $objA->get_article(%apiparams); 
  if (count($articleInfo) > 0) {
    $output = $runtime->$r->txt->do_template($controller, 'api.GetArticle', $articleInfo);  
  } else {
    $result = 'ERR';
    push @errors, $runtime->hash2ref( ('code' => 'GetArticle.Failed', 'text' => 'Could not find article by this id.') );
  }
} else {
  $result = 'ERR';
  push @errors, $runtime->hash2ref( ('code' => 'GetArticle.InvalidParams', 'text' => 'Article ID should be provided.') );
} 

# Return resulting XML API output - quite similar to all APIs
print "content-type: $contenttype; charset=$charset;\n\n";
print $runtime->$r->txt->do_template($controller, 'Envelope', array(
  'result' => $result,
  'output' => $output,
  'warnings' => Arrays::a2xml($warnings, 'Warnings', 'Warning'),
  'errors' => Arrays::a2xml($errors, 'Errors', 'Error'),
));

1;

?>
