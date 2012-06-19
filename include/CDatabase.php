<?php

class CDatabase {

    public $r;
    public $lasterror;
    public $affectedrows;
    private $db;

    public function __construct($r) {
        // Link back to runtime context
        $this->r = $r;
        // Try to connect to database
        $link = mysql_connect($r->config['db']['SERVER'], $r->config['db']['USERNAME'], $r->config['db']['PASSWORD']);
        if (!$link) {
            $r->fatal_error('DB_CONNECT_FAILED');
        } else {
            if (!mysql_select_db($r->config['db']['DBNAME'], $link)) {
                $r->fatal_error('DB_CONNECT_FAILED');
            }
        }
        $this->db = $link; // Everything's just fine
        $r->save_moment('Database connected');
    }

    public function preview($modulename, $templatename, $params) {
        /* Shortcut to a function that prepares SQL statement from the template */
        return $this->r->txt->do_sql_template($modulename, $templatename, $params);
    }

    public function sql2array($modulename, $templatename, $params) {
        /* Returns result of SELECT query stored in the given template */
        $output = array();
        $query = $this->r->txt->do_sql_template($modulename, $templatename, $params);
        return $this->get_array($query);
    }

    public function sql2row($modulename, $templatename, $params) {
        /* Returns first result of SELECT query stored in the given template */
        $output = array();
        $query = $this->r->txt->do_sql_template($modulename, $templatename, $params);
        return $this->get_row($query);
    }

    public function runsql($modulename, $templatename, $params) {
        /* Returns result of INSERT/DELETE/UPDATE query stored in the template */
        $query = $this->r->txt->do_sql_template($modulename, $templatename, $params);
        return $this->run_query($query);
    }

    public function update($modulename, $templatename, $params) {
        /* Returns result of UPDATE query stored in the template */
        $query = $this->r->txt->do_sql_template($modulename, $templatename, $params);
        return $this->run_query($query);
    }

    public function delete($modulename, $templatename, $params) {
        /* Returns result of DELETE query stored in the template */
        $query = $this->r->txt->do_sql_template($modulename, $templatename, $params);
        return $this->run_query($query);
    }

    public function insert($modulename, $templatename, $params) {
        /* Returns result of INSERT query stored in the template */
        $query = $this->r->txt->do_sql_template($modulename, $templatename, $params);
        return $this->run_query($query);
    }

    public function count($modulename, $templatename, $params) {
        /* Returns number of rows returned by SELECT query stored in the template */
        $query = $this->r->txt->do_sql_template($modulename, $templatename, $params);
        $result = $this->get_row("SELECT COUNT(*) AS cnt FROM ($query) basequery");
        return $result['cnt'];
    }

    public function get_new_id($modulename, $templatename, $params) {
        /* Returns ID of identity field affected by INSERT query stored in the template */
        $query = $this->r->txt->do_sql_template($modulename, $templatename, $params);
        $result = $this->run_query($query);
        return ($result['result'] == 'OK') ? mysql_insert_id() : 0;
    }

    public function get_array($query) {
        /* Prepares array from query results */
        $output = array();
        $this->lasterror = '';
        $result = mysql_query($query);
        if (!$result) {
            $error = $this->lasterror = mysql_error();
            $this->r->save_moment("Could not successfully run query ($sql)" . $error);
        } elseif (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_assoc($result)) {
                $output[] = $row + array('_i_' => $i, '_mod2_' => $i++ % 2);
            }
        }
        return $output;
    }

    public function get_row($query) {
        /* Prepares associated array with one row data from query results */
        $output = array();
        $this->lasterror = '';
        $result = mysql_query($query);
        if (!$result) {
            $error = $this->lasterror = mysql_error();
            $this->r->save_moment("Could not successfully run query ($sql)" . $error);
        } elseif (mysql_num_rows($result) > 0) {
            $output = mysql_fetch_assoc($result);
        }
        return $output;
    }

    public function run_query($query) {
        /* Generic execution of the query, wrapping possible exception */
        $this->lasterror = '';
        $result = mysql_query($query);
        if (!$result) {
            $error = $this->lasterror = mysql_error();
            $this->r->save_moment("Could not successfully run query ($sql)" . $error);
            return -1;
        } else {
            $this->affectedrows = $n = mysql_affected_rows();
            return $n;
        }
    }

}

?>