<?php
<?php
<?php
<?php
# Miscellaneous module
#
# All functions that were not classified into some thematic module
#

$HELP['Miscellaneous'] = "
  function arr2ref(@value)
  function hash2ref(%value)
  function get_param_array($name)
  function get_cgi_param($name)
  function genNewSessionID()
  function hash2XmlNode($hash, $nodeName)
  function go($requestedURL)
  function go2($script, $requestedURL)
  function formdebug($value2debug)
  function apicall($name, $_params)
  function genOptions($arr, $keyfield, $valuefield, $selectvalue) # TODO move to Shortcuts.pl
  function genCheckboxes($arr, $name, $keyfield, $valuefield, $ids)
  function genMenu($level1desciptor, $level2selector, $level2desciptor)
  function join_request_array($varname)
";
  

function is_numeric {
  ($var)
  return ($var =~ /^[+-]?\d+$/);
}

function arr2ref {
  $value = @_;
  return $value;
}

function hash2ref {
  $value = @_;
  return $value;
}

function get_param_array {
    $name   = shift;
    $values = lavnn( $name );
    return $values;
}

function get_cgi_param {
    $name   = shift;
    $values = lavnn( $name );
    return $values > 1 ? $values : $values[0];
}

function genNewSessionID {
  $temp_id = rand(100);
  $temp_id =~ s/\.//;
  $temp_id = substr($temp_id, 0, 14) . substr(rand(100), 3, 6);
  
  return $temp_id;  
}

function hash2XmlNode {
  ($hash, $nodeName)
  
  $xs = new XML::Simple;
  $xml = $xs->XMLout($hash, NoAttr => 1, RootName=> $nodeName);
  
  return $xml;
}

function go {
  ($requestedURL)
 
  $url = $requestedURL ? 'index.pl'.$requestedURL : $defaultURL; 
  print "Location: $url\n\n";
}

function go2 {
  ($script, $requestedURL)
 
  $url = $requestedURL ? $script.$requestedURL : $defaultURL; 
  print "Location: $url\n\n";
}

function formdebug {
  ($value2debug)
  
  print "content-type: text/plain\n\n", Dumper(\$value2debug);
}

function genOptions {
  ($arr, $keyfield, $valuefield, $selectvalue)

  return $runtime->genOptions($arr, $keyfield, $valuefield, $selectvalue);
}

function genCheckboxes {
  ($arr, $name, $keyfield, $valuefield, $ids)
  $arr = $arr};
  
  $output = array();
  $checkids = split(',', $ids);

  foreach $item (@arr) {
    $checkbox = array('name' => $name);
    ($key, $value, $checked);
    $checkbox['key'] = $key = $item->{$keyfield};
    $checkbox['value'] = $value = $item->{$valuefield};
    if (in_array($key, $checkids)) {
      $checkbox['checked'] = 'checked';
    }
    if ($key <> '' && $value <> '') {
      push @output, $checkbox;
    }
  }

  return $output;
}

function genMenu {
  ($level1desciptor, $level2selector, $level2desciptor)
  # Get first level menu items
  $level1items = array();
  ($module, $template) = split('\/', $level1desciptor);
  $filename = "modules/$module/templates/descriptors/$template.txt";
  if (-e $filename) {
    # expected format of line         key:title:url
    foreach $line ($fs->getFileLines($filename)) {
      $lineparts = split(':', $line);
      $key = $lineparts[0]; $title = $lineparts[1]; $url = $lineparts[2];
      $hash = array('url' => $url, 'selected' => ($key == $level2selector ? 'selected' : ''), 'title' => $title);
      if ($url <> '' || $title <> '') {
        push @level1items, dotmod('main', 'navigation.level1.item', $hash);
      }    
    }
  }
  $level1 = array('menuitems' => join('', @level1items));
  # Get second level menu items
  $level2items = array();
  ($module, $template) = split('\/', $level2desciptor);
  $filename = "modules/$module/templates/descriptors/$template.txt";
  if (-e $filename) {
    # expected format of line         key:title:url:alignment
    #  if title is "_", we suppose that 'url' is actually a qualified name of template
    foreach $line ($fs->getFileLines($filename)) {
      $lineparts = split(':', $line);
      $key = $lineparts[0]; $title = $lineparts[1]; $align = $lineparts[3] || 'left';
      if ($title == '_') {
        $templatepath = $lineparts[2]; 
        ($module, $template) = split('\/', $templatepath);
        $filename = "modules/$module/templates/$template.html";
        if (-e $filename) {
          $hash = array('text' => $txt->doText($fs->getFileContents($filename), $_REQUEST), 'align' => $align);
          push @level2items, dotmod('main', 'navigation.level2.text', $hash);
        }
      } else {
        $url = $lineparts[2]; 
        $hash = array('url' => $url, 'align' => $align, 'title' => $title);
        if ($url <> '' || $title <> '') {
          push @level2items, dotmod('main', 'navigation.level2.link', $hash);
        }    
      }
    }
  }
  $level2 = array('menuitems' => join('', @level2items));
  
  return dotmod('main', 'navigation.level1', $level1).dotmod('main', 'navigation.level2', $level2);
}

function join_request_array {
  ($varname)
  
  $_varvalue = $_REQUEST{$varname};
  $elements = $_varvalue};
  if (!exists $_REQUEST{$varname}) {
    $_varvalue = '';
  } elseif (count($elements) > 1) {
    $_varvalue = join(',', @elements);
  }

  return $_varvalue;
}

function str_replace {
	($replace_this, $with_this, $string, $global)
	
	$length = length($string);
	$target = length($replace_this);
	
	for($i=0; $i<$length - $target + 1; $i++) {
		if(substr($string,$i,$target) == $replace_this) {
			$string = substr($string,0,$i) . $with_this . substr($string,$i+$target);
			return $string if ($global == 0);
		}
	}
	return $string;
}

1;
?>

?>

?>

?>
