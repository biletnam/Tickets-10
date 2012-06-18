<?php

class CArrayFilter {

    public function __construct($txtProcessor) {
        // Link back to runtime context
        $this->host = $txtProcessor;
    }

    public function apply($value, $filters, $params = array()) {
        // Check if variable is of array type
        if (is_object($value))
            return 'Object';
        if (!is_array($value))
            return $value;
        // Fetch the filter to apply on the value
        $nextfilter = array_shift($filters);

        // Check if filter is valid
        if ($nextfilter == '' && count($filters) == 0)
            return $value;
        // Parse filter definition
        $filterDefinition = explode("?", $nextfilter, 2);
        $filtername = $filterDefinition[0];
        $filterparamstr = count($filterDefinition) > 1 ? $filterDefinition[1] : '';
        parse_str($filterparamstr, $filterparams);
        // Apply filter on the value
        switch ($filtername) {
            case "ifempty":
                if (count($value) > 0)
                    return $value;
                break;
            case "stopifempty":
                if (count($value) == 0)
                    return "";
                break;
            case "stopifnotempty":
                if (count($value) != 0)
                    return "";
                break;
            case "length":
                $value = count($value);
                break;
            case "first":
                $value = $value[0];
                break;
            case "last":
                $value = array_pop($value);
                break;
            case "buildquerystring":
                $value = '?' . http_build_query($value);
                break;
            case "cutcolumn":
                $columnname = lavnn('column', $params, '');
                $output = array();
                foreach ($value as $row) {
                    $columnvalue = lavnn($columnname, $row, '');
                    if ($columnvalue != '')
                        $output[$columnvalue] = $columnvalue;
                }
                $value = $output;
                break;
            case "joincolumn":
                $fieldname = $filterparams['field'] or '';
                $rowvalues = array();
                foreach ($value as $row) {
                    $fieldvalue = $row[$fieldname] or '';
                    if ($fieldvalue != '') {
                        $rowvalues[] = $row[$fieldname];
                    }
                }
                $value = join(',', $rowvalues);
                break;
            case "looptemplate":
                $modulename = $filterparams['module'] or '';
                $templatename = $filterparams['name'] or '';
                $value = $this->host->loop_template($modulename, $templatename, $value);
                break;
            case "ksort":
                ksort($value);
                break;
            case "asort":
                asort($value);
                break;
            case "sort":
                sort($value);
                break;
            case "debug":
                $value = print_r($value, true);
                break;
        }

        // Do recursive call if there are more filters in the pipeline
        return (count($filters) > 0) ? $this->host->do_filter($value, $filters, $params) : $value;
    }

}

?>