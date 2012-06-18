<?php

// lookup array value
function lav($key, $arr, $default) {
    if (!is_array($arr))
        return $default;
    return array_key_exists($key, $arr) ? $arr[$key] : $default;
}

// lookup array value, and use default even if it was present, but empty
function lavnn($key, $arr, $default) {
    $av = lav($key, $arr, "");
    return ($av == "") ? $default : $av;
}

// slice two columns from two-dimensional array, using one of them as a key
function arr2arr($arr, $keyfield = 'id', $valuefield = 'name', $select = '') {
    $output = array();
    foreach ($arr as $row) {
        $key = lavnn($keyfield, $row, '');
        $value = lavnn($valuefield, $row, '');
        if ($key != '' && $value != '') {
            $output[$key] = array("key" => $key, "value" => $value);
            if ($select != '' && $key == $select) {
                $output[$key]["selected"] = 'selected';
            }
        }
    }
    return $output;
}

// transform two-dimensional array into dictionary (hashtable)
function arr2dict($arr, $keyfield, $valuefield, $allow_empty_value = 0) {
    $output = array();
    foreach ($arr as $row) {
        $key = lavnn($keyfield, $row, '');
        $value = lavnn($valuefield, $row, '');
        if ($key != '' && (($value != '') || $allow_empty_value > 0)) {
            $output[$key] = $value;
        }
    }
    return $output;
}

// transform dictionary into associative key-value array
function dict2arr($dict, $select = '') {
    $output = array();
    foreach ($dict as $key => $value) {
        $output[$key] = array("key" => $key, "value" => $value);
        if ($select != '' && $key == $select) {
            $output[$key]["selected"] = 'selected';
        }
    }
    return $output;
}

// implode values of one column in an array
function implode_column($glue, $column, $arr) {
    $values = array();
    foreach ($arr as $row) {
        $value = lavnn($column, $row, '');
        if ($value != '')
            $values[] = $value;
    }
    return implode($glue, $values);
}

// implode values of one column in an array, using quotes
function implode_column_quoted($glue, $column, $arr, $quote ="'") {
    $values = array();
    foreach ($arr as $row) {
        $value = lavnn($column, $row, '');
        if ($value != '')
            $values[] = $value;
    }
    return $quote . implode($quote . $glue . $quote, $values) . $quote;
}

// Find a sum of values of numeric column of two-dimensional array
function sum_column($arr, $column) {
    $output = 0;
    foreach ($arr as $row) {
        $value = lavnn($column, $row, 0);
        if (is_numeric($value))
            $output += $value;
    }
    return $output;
}

// slice two-dimensional array into associative array by values in a key column
function slice_array($arr, $column) {
    $output = array();
    foreach ($arr as $row) {
        $output[$row[$column]][] = $row;
    }
    return $output;
}

// reduce array by eleminating all rows where one column's values not equal to search
function filter_array($arr, $column, $value) {
    $output = array();
    foreach ($arr as $row) {
        if ($row[$column] == $value) {
            $output[] = $row;
        }
    }
    return $output;
}

?>
