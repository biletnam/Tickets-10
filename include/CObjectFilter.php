<?php

class CObjectFilter {

    public function __construct($txtProcessor) {
        // Link back to runtime context
        $this->host = $txtProcessor;
    }

    public function apply($value, $filters, $params = array()) {
        // Check if variable is of array type
        if (is_array($value))
            return 'Array';
        if (!is_object($value))
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
        // switch ($filtername) {  }    
        // Do recursive call if there are more filters in the pipeline
        return (count($filters) > 0) ? $this->host->do_filter($value, $filters, $params) : $value;
    }

}

?>