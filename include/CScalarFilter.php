<?php

class CScalarFilter {

    public function __construct($txtProcessor) {
        // Link back to runtime context
        $this->host = $txtProcessor;
    }

    public function apply($value, $filters, $params = array()) {
        // Check if variable is of scalar type
        if (is_array($value))
            return 'Array';
        if (is_object($value))
            return 'Object';
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
            case "lowercase": $value = strtolower($value);
                break;
            case "uppercase": $value = strtoupper($value);
                break;
            case "trim": $value = trim($value);
                break;
            case "length": $value = strlen($value);
                break;
            case "wordcount": $value = str_word_count($value);
                break;
            case "htmlentities": $value = htmlentities($value);
                break;
            case "round":
                if (is_numeric($value)) {
                    $precision = $filterparams['digits'] or 0;
                    $value = round($value, $precision);
                } else
                    $value = 0;
                break;
            case "shortener":
                $wordcount = $filterparams['words'] or 0;
                $charcount = $filterparams['chars'] or 0;
                if ($wordcount > 0) {
                    $words = str_word_count($value, 1);
                    if (count($words) > $wordcount)
                        $value = join(' ', array_slice($words, 0, $wordcount));
                } elseif ($charcount > 0) {
                    if (strlen($value) > $charcount)
                        $value = substr($value, $charcount);
                }
                break;
            case "includesql":
                $modulename = $filterparams['module'] or '';
                $templatename = $filterparams['name'] or '';
                $value = $this->host->do_sql_template($modulename, $templatename, $params);
                break;
            case "fwdtemplate":
                $modulename = $filterparams['module'] or '';
                $templatename = $filterparams['name'] or '';
                $value = $this->host->do_template($modulename, $templatename, $params);
                break;
            case "dbsafe":
                $value = str_replace("'", "''", $value);
                break;
            case "htmlsafe":
                $value = str_replace('"', '&quot;', $value);
                break;
            case "urlencode":
                $value = urlencode($value);
                break;
        }

        // Do recursive call if there are more filters in the pipeline
        return (count($filters) > 0) ? $this->host->do_filter($value, $filters, $params) : $value;
    }

}