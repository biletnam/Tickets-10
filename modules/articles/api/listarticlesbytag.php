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

$tags = $apiparams['tags'] || '';
if ($tags <> '') {

  use objArticles;
  our $objA = new objArticles($r);
  $articles = $objA->list_articles_bytag( ('tags' => $tags) );

  if (count($articles) == 0) {
    push @warnings, $runtime->hash2ref( ('code' => 'ListArticlesByTag.NothingFound', 'text' => 'No articles found for provided combination of tags') );
  } else {
    $apiparams['articles'] = $articles;
    $output = $runtime->dotmod($controller, 'api.ListArticles', array('articles' => $articles));
  }

} else {
  $result = 'ERR';
  push @errors, $runtime->hash2ref( ('code' => 'ListArticlesByTag.InvalidParams', 'text' => 'Please specify at least tag or search string.') );
}

# Return resulting XML API output - quite similar to all APIs
binmode STDOUT, ":utf8";
print "content-type: $contenttype; charset=$charset;\n\n";
print $runtime->dotmod($controller, 'Envelope', array(
  'result' => $result,
  'output' => $output,
  'warnings' => Arrays::a2xml($warnings, 'Warnings', 'Warning'),
  'errors' => Arrays::a2xml($errors, 'Errors', 'Error'),
));

1;

?>
