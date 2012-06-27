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

function go($requestedURL) {
    $url = $requestedURL ? 'index.pl' . $requestedURL : $defaultURL;
    print "Location: $url\n\n";
}

function go2($script, $requestedURL) {
    $url = $requestedURL ? $script . $requestedURL : $defaultURL;
    print "Location: $url\n\n";
}

function genOptions($arr, $keyfield, $valuefield, $selectvalue) {
    return $runtime->genOptions($arr, $keyfield, $valuefield, $selectvalue);
}

function genCheckboxes($arr, $name, $keyfield, $valuefield, $ids) {
    $output = array();
    $checkids = split(',', $ids);

    foreach ($arr as $item) {
        $checkbox = array('name' => $name);
        $checkbox['key'] = $key = $item->{$keyfield};
        $checkbox['value'] = $value = $item->{$valuefield};
        if (in_array($key, $checkids)) {
            $checkbox['checked'] = 'checked';
        }
        if ($key <> '' && $value <> '') {
            $output[] = $checkbox;
        }
    }

    return $output;
}

function genMenu($level1desciptor, $level2selector, $level2desciptor) {
    // Get first level menu items
    $level1items = array();
    list($module, $template) = split('\/', $level1desciptor);
    $filename = "modules/$module/templates/descriptors/$template.txt";
    if (file_exists($filename)) {
        // expected format of line         key:title:url
        foreach ($fs->getFileLines($filename) as $line) {
            $lineparts = split(':', $line);
            $key = $lineparts[0];
            $title = $lineparts[1];
            $url = $lineparts[2];
            $hash = array(
                'url' => $url,
                'selected' => ($key == $level2selector ? 'selected' : ''),
                'title' => $title
            );
            if ($url != '' || $title != '') {
                $level1items[] = $r->txt->do_template('main', 'navigation.level1.item', $hash);
            }
        }
    }
    $level1 = array('menuitems' => join('', $level1items));
    // Get second level menu items
    $level2items = array();
    list($module, $template) = split('\/', $level2desciptor);
    $filename = "modules/$module/templates/descriptors/$template.txt";
    if (file_exists($filename)) {
        // expected format of line         key:title:url:alignment
        //  if title is "_", we suppose that 'url' is actually a qualified name of template
        foreach ($fs->getFileLines($filename) as $line) {
            $lineparts = split(':', $line);
            $key = $lineparts[0];
            $title = $lineparts[1];
            $align = $lineparts[3] || 'left';
            if ($title == '_') {
                $templatepath = $lineparts[2];
                list($module, $template) = split("\/", $templatepath);
                $filename = "modules/$module/templates/$template.html";
                if (file_exists($filename)) {
                    $hash = array(
                        'text' => $txt->doText($fs->getFileContents($filename), $_REQUEST),
                        'align' => $align
                    );
                    $level2items[] = $r->txt->do_template('main', 'navigation.level2.text', $hash);
                }
            } else {
                $url = $lineparts[2];
                $hash = array('url' => $url, 'align' => $align, 'title' => $title);
                if ($url != '' || $title != '') {
                    $level2items [] = $r->txt->do_template('main', 'navigation.level2.link', $hash);
                }
            }
        }
    }
    $level2 = array('menuitems' => join('', $level2items));

    return $r->txt->do_template('main', 'navigation.level1', $level1) . $r->txt->do_template('main', 'navigation.level2', $level2);
}

function join_request_array($varname) {
    $_varvalue = $_REQUEST[$varname];
    if (lavnn($varname, $_REQUEST, '') != '') {
        $_varvalue = '';
    } elseif (count($elements) > 1) {
        $_varvalue = join(',', $elements);
    }

    return $_varvalue;
}

?>