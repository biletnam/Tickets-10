<?php

class ctlDataGrid {
  private $runtime;
  private $controlname = '';
  public $basequery = '';
  public $module = '';
  public $columns = array();
  private $headers = array();
  public $tab = '';             // Tab of ctlTab if grid is rendered on it.
  
  public $customtemplates = array();  // Array with custom templates that override generated HTML
  public $tablewidth = 0;       // Explicit width for a table of the grid. Not set when 0.
  public $pagesize = 0;         // Number of rows to render on single page. No paging is done when 0.
  public $pageno = 0;           // Number of current page. Ignored when $pagesize equals 0.
  public $length = 0;           // Total number of rows that gets returned for $basequery.
  public $rowcount = 0;         // Total number of rows to render. Equals to $length when $pagesize is not defined.
  public $sort = 0;             // Index of column to sort on. When negative number is given, DESC order is used.
  public $rowselection = '';    // Name of id column for which select box is rendered in every row
  
  public $hasFilters = 0;       // Set automatically, if parsed columns have any. 

	public function __construct($r, $name, $basequery, $module) {
    $this->runtime = $r;
    $this->controlname = $name;
    $this->basequery = $basequery;
    $this->module = $module;
	}
  
  public function setPager($pagesize, $pageno) {
    $this->pagesize = $pagesize;
    $this->pageno = $pageno;
  }

  public function setCustomTemplate($name, $content) {
    $this->customtemplates[$name] = $content;
  }

  public function setRowSelection($id) {
    $this->rowselection = $id;
  }
  
  public parseColumnDescriptor($descriptor) {
    $columns = array(); 
    $filters = '';
    foreach (split("\n", $descriptor) as $line) {
      list($name, $type, $title, $paramstr) = split(':', trim($line));
      $params = array();
      parse_str($paramstr, $params);
      $filters .= lavnn('filter', $params '');
      $columns = array(
        'name' => $name,
        'type' => $type,
        'title' => $title,
        'params' => $params,
      ));
    }
    $this->hasFilters = ($filters != '' ? 1 : 0);
    return $columns;
  }
  
  public reflectColumns($query) {
    $columns = array(); 
    $filters = '';
    foreach ($this->runtime->db->getQueryMetadata($query) as $c) {
      $columns[] = array(
        'name' => $c['name'],
        'type' => $c['type'],
        'title' => $c['name'],
        'params' => array(), # TODO: find a way to pass parameters to reflected columns
      ));
    }
    return $columns;
  }

  public function render($renderoptions) {
    $datacells = array();
    $datarows = array();
    $baseurl = $this->getBaseUrl();
    $i = 0;
    $sortingclause = ''; $ascdesc = '';
    $datacelltemplate = '';

    // put selection
    $idcolumn = $this->rowselection;
    if ($idcolumn ne '') {
      $headers[] = $this->runtime->txt->do_template('main', 'datagrid.cell.header.rowselect', array());
      $datacells[] = $this->runtime->txt->do_template('main', 'datagrid.cell.data.rowselect', array('idcol' => $idcolumn));
      $filters[] = $this->runtime->txt->do_template('main', 'datagrid.cell.filter.none', array());
    }
    
    // iterate through all parsed/reflected columns 
    foreach ($columns as $col) {
      $col['id'] = ++$i;
      $col['baseurl' = $baseurl;
      $col['controlname'] = $this->controlname;
      
      // Check if basequery should be rewritten to allow sorting
      if (abs($this->sort) == $i) {
        $sortingclause = ' ORDER BY '.$col['name'];
        if ($this->sort == $i) {
          $col['sign'] = '-';
          $ascdesc = 'asc';
        } else {
          $sortingclause .= ' DESC';
          $ascdesc = 'desc';
        } 
        $basequery = 'SELECT * FROM ( '. $this->basequery .' ) basequery '. $sortingclause;
      }
      
      // Choose the good template to render data cell
      switch ($col['type']) {
        case 'dict':
          $col['dictmodule'] = lavnn('dictmodule', $col['params'], $this->module);
          $col['dictname'] = $col['params']['dictname'];
          $datacelltemplate = $this->runtime->txt->get_template('main', 'datagrid.cell.data.dict'); 
          break;
        case 'date':
          $col['dateformat'] = lavnn('dateformat', $col['params'], 'fulldate');
          $datacelltemplate = $this->runtime->txt->get_template('main', 'datagrid.cell.data.date'); 
          break;
        default:
          $datacelltemplate = $this->runtime->txt->get_template('main', 'datagrid.cell.data');
      }
      $headertemplatename = 'datagrid.cell.header'.($col['params']['sortable'] == '' ? '' : '.sortable');
      
      // add generated template fragments
      $headers[] = $this->runtime->getTemplate('main', $headertemplatename, $col);  
      $datacells[] = doText($datacelltemplate, $col, 1); 
      
      // add column filter template if needed
      if ($this->has_filters > 0) {
        $columnfilter = $col['params']['filter'];
        switch ($columnfilter) {
          case 'dropdown':
            $elementname = $col['controlname'] . '_filter_' . $col['name'];
            $varname = $col['controlname'] . '_options_' . $col['name'];
            $col[$varname] = $this->gen_column_options($col['name'], $_REQUEST[$elementname]); 
            $col['baseurl'] = $this->cut_parameter($col['baseurl'], $elementname);
            $filters[] = doText(doText($this->runtime->getTemplate('main', 'datagrid.cell.filter.dropdown'), $col, 1), $col);
            break;
          case 'freeform':
            $filters[] = doText($this->runtime->getTemplate('main', 'datagrid.cell.filter.freeform'), $col, 1);
            break;
          else:
            $filters[] = doText($this->runtime->getTemplate('main', 'datagrid.cell.filter.none'), $col, 1);
        }
      } 
    }

    // add a column for actions if needed
    if ($this->customtemplates['actions'] != '') {
      $headers[] = $this->runtime->text->do_template('main', 'datagrid.cell.header.actions', $col);  
      $datacells[] = $this->customtemplates['actions'];
      $filters[] = doText($this->runtime->getTemplate('main', 'datagrid.cell.filter.none'), array(), 1);
    }

    // produce the templates using gathered information
    $headers = $this->runtime->text->do_template('main', 'datagrid.row.headers', ('cells' => join('', $headers)));
    $datarowtemplate = $this->runtime->text->do_template('main', 'datagrid.row.data', ('cells' => join('', $datacells)));
    if ($this->has_filters > 0) {
      $filters = $this->runtime->text->do_template('main', 'datagrid.row.filters', ('cells' => join('', $filters)));
    }
    if ($this->customtemplates['datarow'] != '') { # Overwrite with passed template 
      $datarowtemplate = $this->customtemplates['datarow'];
    }
    if ($this->customtemplates['superheaders'] != '') { # Add custom topmost headers 
      $headers = $this->customtemplates['superheaders'] . $headers;
    }
  
    // Prepare data
    $dbtype = $this->runtime->db->connections[$dbkey]["dbtype"];
		$result = $this->runtime->db->runQuery($dbkey, $query);
    $i = 0; $rowcount = 0; $continue = 1;
    $first = ($this->pagesize > 0) ? 1 + $this->pagesize * ($this->pageno - 1) : 0;
    $last = ($this->pagesize > 0) ? $this->pagesize * $this->pageno : 0;   
    while ($continue) {
      $row = result2Row($result, $dbtype);
      if (count($row) > 0) {
        $row['_i_'] = ++$i; $row['_mod2_'] = ($i % 2); $row['_module_'] = $this->module;
        if ($first == 0 || ($first <= $i && $last >= $i)) {
          $datarows[] = $this->runtime->txt->doText($datarowtemplate, $row); $rowcount++;
        }
      } else {
        $continue = 0;
      }
    }  
    $this->length = $i; 
    $this->rowcount = $rowcount; 
  
    // Return custom template if no data was found
    if ($i == 0) {
      return lavnn('nodata', $this->customtemplates, '');
    } 
  
    $grid = array(
      'controlname' => $this->controlname,
      'width' => ($this->tablewidth > 0? 'width="'.$this->tablewidth.'"' : ''),
      'headers' => $headers,
      //'filters' => $r->{'txt'}->doText($filters),
      'datarows' => join('', $datarows),
    );
    // Mark sorting order if needed
    if ($this->sort ne 0) {
      $grid['marksorting'] = $this->runtime->text->do_template('main', 'datagrid.marksorting', array(
        'controlname' => $this->controlname,
        'sortcolumn' => abs($this->sort),
        'ascdesc' => $ascdesc,
      )); # TODO - in 'datagrid.marksorting' there is a reference to image. show it in right location. styles?
    }
    // Add pager control if needed  
    if ($this->pagesize > 0) {
      $grid['pager'] = $this->renderPager($renderoptions);
    } else {
      $grid['pager'] = $this->renderRowCount();
    }

    $this->runtime->text->do_template('main', 'datagrid', $grid);
  }
  
  private function renderRowCount() {
    # TODO i18n for 'datagrid.rowcount' template
    
    return $this->runtime->text->do_template('main', 'datagrid.rowcount', array(
      'length' => $this->length, 
      'rowcount' => $this->rowcount
    ));
  }

  private function renderPager($renderoptions) {
    # TODO i18n for 'datagrid.pager' template
    
    // Premature exit if parameters are not correct
    if ($this->rowcount == 0 || $this->pagesize == 0) {
      return ''; 
    }
    
    $baseurl = $this->getBaseUrl('pageno');
    $pagecount = int($this->length / $this->pagesize);
    $pagecount++ if $pagecount * $this->pagesize < $this->length;
    $pageno = $this->pageno;
    
    // If there is just one page, we don't need to draw pager either
    if ($pagecount < 2) {
      return ($this->rowcount > 0) ? $this->renderRowCount() : '';
    }
      
    $links = array(); 
    $first_delimiter = $last_delimiter = 0;
    for ($i = 1; $i <= $pagecount; $i++) {
      if ($i == $this->pageno) {
        $links[] = $this->runtime->text->do_template('main', 'datagrid.pager.current', array('pageno' => $pageno));    
      } else if ($i < 4 || $i > $pagecount - 3 || abs($i - $pageno) < 3) {
        $links[] = $this->runtime->text->do_template('main', 'datagrid.pager.selectable', array(
          'pageno' => $i, 
          'baseurl' => $baseurl, 
          'controlname' => $this->controlname,
        ));    
      } else {
        // draw placeholders between 3 and i - 3, and between i + 3 and n - 3
        if ($i > 3 && $i < $pageno - 2 && $first_delimiter eq 0) {
          $links[] = $this->runtime->text->do_template('main', 'datagrid.pager.placeholder');
          $first_delimiter = 1;
        }     
        if ($i < $pagecount - 2 && $i > $pageno + 2 && $last_delimiter eq 0) {
          $links[] = $this->runtime->text->do_template('main', 'datagrid.pager.placeholder');
          $last_delimiter = 1;
        }     
      }
    }
    
    return $this->runtime->text->do_template('main', 'datagrid.pager', array(
      'pages' => join('', $links),
      'rowcount' => $this->renderRowCount(),
    ));  
  }
	
  private function gen_column_options($column, $selectvalue) {
    $basequery = $this->basequery;
    $data = $this->runtime->db->get_array("SELECT DISTINCT $column FROM ($basequery) basequery ORDER BY $column");
  
    return arr2arr($data, $column, $column, $selectvalue);
  } 
  
  private function cut_parameter($querystring, $paramname) {
    $searchpattern = '&'.$paramname.'='.urlencode($_REQUEST[$paramname]); 
    
    return str_replace($searchpattern, '', $querystring);
  } 
  
  private function getBaseUrl($option) { 
    # returns the query string with no information about paging and sorting for THIS datagrid control
    # TODO rewrite using parse_str, unset and http_build_query; move to some library

    $baseurl = $_SERVER{'QUERY_STRING'};
    if ($option == '' || $option == 'pageno') {
      // reset paging
      $paramname = $this->controlname.'_pageno'; 
      $searchpattern = '&'.$paramname.'='.$_REQUEST[$paramname]; 
      $baseurl = str_replace($searchpattern, '', $baseurl); 
    }
    if ($option == '' || $option == 'sort') {
      // reset sorting
      $paramname = $this->controlname.'_sort'; 
      $searchpattern = '&'.$paramname.'='.$_REQUEST[$paramname]; 
      $baseurl = str_replace($searchpattern, '', $baseurl); 
    }
    // reset tab
    if ($this->tab ne '') {
      $replacepattern = '&tab='.$this->tab;
      if (array_key_exists('tab', $_REQUEST)) {
        $searchpattern = '&tab='.$_REQUEST['tab'];
        $baseurl = str_replace($searchpattern, $replacepattern, $baseurl); 
      } else {
        $baseurl .= $replacepattern; 
      }
    }
      
    return $baseurl;
  }

}

?>