<?php
/* 
 *  Copyright 2013-2014 by Levin Cao, all rights reserved.
 *
 *  These coded instructions, statements, and computer programs are the
 *  property of Lucent Technologies. Inc. and are protected by copyright laws.  
 *  Copying, modification, distribution and use without Lucent Technologies. 
 *  Inc.'s permission are prohibited. 
 *
 */
// mysql abstraction class.  Does some things to make life easier:
// - automatically sets up connection on instantiation
// - logging to file
// - intelligent error handling

/******************************/
/*                            */
/*  类名:cDatabase()          */
/*  作用:数据库操作           */
/*  使用方法:new cDatabase()  */
/*                            */
/******************************/

class cDatabase {
    var $db_host;
    var $db_username;
    var $db_password;
    var $default_db;
    var $sql_error_number;
    var $sql_error_name;
    var $db_type;
    var $link_id;
    var $debug;
    var $logfile;
    var $fp;

    function cDatabase($select_db = TRUE) // constructor
    {
        global $db_host, $db_username, $db_password, $default_db, $db_type, $db_use_pconnect;
        $this->db_host = DB_HOST;
        $this->db_username = DB_USERNAME;
        $this->db_password = DB_PASSWORD;
        $this->default_db = DEFAULT_DB;
        $this->db_type = DB_TYPE;
        $this->debug = FALSE;
        $this->logfile = 'db_queries.txt';

        if ($this->debug) {
            $this->fp = fopen($this->logfile, 'a');
        }

        if ($select_db) {
            if ($db_use_pconnect) {
                $this->pconnect($this->default_db);
            } else {
                $this->connect($this->default_db);
            }
        } else {
            if ($db_use_pconnect) {
                $this->pconnect();
            } else {
                $this->connect();
            }
        }



        if (($this->db_type != 'mysql') && ($this->db_type != 'postgres') && ($this->db_type != 'msql')) {
            die("Invalid database type in config.inc.php");
        }

    }

/******************************/
/*                            */
/*  函数名:accected_rows()    */
/*  作用:返回受影响的行数     */
/*                            */
/******************************/

    function affected_rows($result)
    {
        return mysql_affected_rows($this->result);
    }

    function auto_insert($table_name = '')
    {
        $value = 'NULL';

        if ($this->debug) {
            echo "auto_insert is $value<br />";
        }

        return $value;
    }

/******************************/
/*                            */
/*  函数名:connect()          */
/*  作用:数据库连接           */
/*                            */
/******************************/

    function connect($db_name = '')
    {

        //if (!$this->link_id = mysql_connect($this->db_host, $this->db_username, $this->db_password)) {
        if (!$this->link_id = mysqli_connect($this->db_host, $this->db_username, $this->db_password, $db_name)) {
             die($strings['ERROR_SQL_CONNECT']);
        }

       if (!empty($db_name)) {
           //mysql_select_db($db_name, $this->link_id) or $this->sql_error();
           mysqli_select_db($this->link_id,$db_name);
       }

        if ($this->debug) {
            fwrite($this->fp, $this->format_date() . " --- Connected to " . $this->db_type . "---\n");
        }

        return;
    }

/******************************/
/*                            */
/*  函数名:insert_id()        */
/*  作用:返回最后插入的ID     */
/*                            */
/******************************/

    function insert_id()
    {
        $insert_id = mysql_insert_id($this->link_id);

        if ($this->debug) {
            fwrite($this->fp, $this->format_date() . " Insert ID is " . $insert_id . "\n");
        }

        return $insert_id;
    }

/********************************/
/*                              */
/*  函数名:fetch_array()      */
/*  作用:返回查询条件结果存储到数组 */
/*                              */
/********************************/

    function fetch_array($result, $row = '0')
    {
        return mysqli_fetch_array($result);
    }

/************************************/
/*                                  */
/*  函数名:fetch_object()           */
/*  作用:返回查询条件结果存储到对象 */
/*                                  */
/************************************/

    function fetch_object($result, $row = '0')
    {
        return mysqli_fetch_object($result);
    }

/******************************/
/*                            */
/*  函数名:fetch_rows()       */
/*  作用:返回受影响的行数     */
/*                            */
/******************************/

    function fetch_row($result, $row = '')
    {
        return mysql_fetch_row($result);
    }

/******************************/
/*                            */
/*  函数名:format_date()      */
/*  作用:格式化日期格式       */
/*                            */
/******************************/

    function format_date()
    {
        return date('Y-m-d H:i:s');    // 2001-12-06 18:00:00
    }

    // returns an array with the field names for a given table_name
/******************************/
/*                            */
/*  函数名:list_fields()      */
/*  作用:返回字段名           */
/*                            */
/******************************/

    function list_fields($table_name)
    {
        $fields = mysql_list_fields($this->default_db, $table_name, $this->link_id);
        $columns = mysql_num_fields($fields);

        for ($i = 0; $i < $columns; $i++) {
            $field[] = mysql_field_name($fields, $i);
        }

        sort($field);
        return $field;
    }

/******************************/
/*                            */
/*  函数名:num_rows()         */
/*  作用:返回受影响的行数     */
/*                            */
/******************************/

    function num_rows($result)
    {
        $numrows = mysqli_num_rows($result);

        if ($this->debug) {
            fwrite($this->fp, $this->format_date() . " Numrows is $numrows\n");
        }

        return $numrows;
    }

/******************************/
/*                            */
/*  函数名:pconnect()         */
/*  作用:连接数据库           */
/*                            */
/******************************/

   function pconnect($db_name = '')
   {
       if (!$this->link_id = mysql_pconnect($this->db_host, $this->db_username, $this->db_password)) {
           die($strings['ERROR_SQL_CONNECT']);
       }

       if (!empty($db_name)) {
           mysql_select_db($db_name, $this->link_id) or $this->sql_error();
       }

        if ($this->debug) {
            fwrite($this->fp, $this->format_date() . " --- Connected to mysql ---\n");
        }

        return;
    }

/******************************/
/*                            */
/*  函数名:query()            */
/*  作用:执行SQL语句          */
/*                            */
/******************************/


    function query($sql_query)
    {
        if ($this->debug) {
            fwrite($this->fp, $this->format_date() . " Query: $sql_query\n");
        }
        //$result = @mysqli_query($sql_query, $this->link_id);
        mysqli_query($this->link_id, 'set names utf8');
        $result = mysqli_query($this->link_id, $sql_query);
        if (!$result) {
            $this->sql_error($sql_query);
        }

        return $result;
    }

/******************************/
/*                            */
/*  函数名:sql_error()        */
/*  作用:SQL错误信息          */
/*                            */
/******************************/

    function sql_error($query = FALSE)
    {
        global $admin_email;

        $this->sql_error_number = mysqli_errno($this->link_id);
        $this->sql_error_name = mysqli_error($this->link_id);

        //$admin_blurb = (ADMIN_EMAIL) ? "<a href=\"mailto:" . ADMIN_EMAIL . "\">site administrator</a>" : "site administrator";
        $admin_blurb = "<a href=\"mailto:levincao@qq.com\">site administrator</a>";

        echo "<br />There was an SQL error.  The error message is: <br /><b>$this->sql_error_name</b>" .
             "<br />Please notify the $admin_blurb.<br />";

        if ($this->debug) {
            fwrite($this->fp, $this->format_date() . " Error: $this->sql_error_name\n");
        }

        if ($query) {
            echo "The SQL Query that failed is: <b>$query</b>";
        }

        die();
    }

}
