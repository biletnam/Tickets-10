<?php

class CNumericFilter {

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
            case "money":
                $value = (is_numeric($value)) ? round($value, 2) : '';
                break;
            /*
              case "currency":
              $ccyinfo = lavnn('currencyInfo', $runtime->data, array());
              $ccyrate = lavnn('rate', $ccyinfo, 1);
              $ccycode = lavnn('code', $ccyinfo, "");
              $value = ($ccycode != "") ? $ccycode.' '.sprintf("%.2f", round($value * $ccyrate, 2)) : "";
              // TODO different rounding for different currencies
              break;
             */
        }

        // Do recursive call if there are more filters in the pipeline
        return (count($filters) > 0) ? $this->host->do_filter($value, $filters, $params) : $value;
    }

}