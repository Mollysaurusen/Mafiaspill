<?php
if (!defined('THRUTT')) {
    die('No access! :(');
}
if (THRUTT == "Sperrederrp!") {

    class database
    {
        var $host;
        var $user;
        var $pass;
        var $database;
        var $last_query;
        var $result;
        var $connection_id;
        var $num_queries = 0;
        var $start_time;
        var $last_error;

        function configure($host = "localhost", $user = "mafia", $pass = "mafia", $database = "mafia")
        {
            $this->host     = $host;
            $this->user     = $user;
            $this->pass     = $pass;
            $this->database = $database;
            return 1;
        }

        function connect()
        {
            if (!$this->host) {
                $this->host = "localhost";
            }
            if (!$this->user) {
                $this->user = "root";
            }
            $this->connection_id = mysqli_connect($this->host, $this->user, $this->pass, $this->database);
            if (!$this->connection_id) {
                $this->connection_error();
            }
            $this->num_queries++;
            return $this->connection_id;
        }

        function disconnect()
        {
            if ($this->connection_id) {
                mysqli_close($this->connection_id);
                $this->connection_id = 0;
                return 1;
            } else {
                return 0;
            }
        }

        function query($query)
        {
            $this->last_query = $query;
            $this->num_queries++;
            $this->result     = mysqli_query($this->connection_id, $this->last_query) or $this->query_error();
            return $this->result;
        }

        function fetch_row($result = 0)
        {
            if (!$result) {
                $result = $this->result;
            }
            return mysqli_fetch_assoc($result);
        }

        function fetch_object($result = 0)
        {
            if (!$result) {
                $result = $this->result;
            }
            return mysqli_fetch_object($result);
        }

        function num_rows($result = 0)
        {
            if (!$result) {
                $result = $this->result;
            }
            return mysqli_num_rows($result);
        }

        function insert_id()
        {
            return mysqli_insert_id($this->connection_id);
        }

        function connection_error()
        {
            global $obj;
            $feil = '
Bruker som utl&oslash;ste feilen: ('.$obj->user.'{'.$obj->id.'/'.$_SESSION["sessionzar"][0].'})'.date("H:i:s d.m.Y").': Fil ('.$_SERVER["REQUEST_URI"].$_SERVER["argv"].') Databasen kunne ikke tilkobles: '.mysqli_error($this->connection_id).'
';
            $fil  = "feil.txt";
            $f    = fopen($fil, "a+");
            fwrite($f, $feil);
            fclose($f);
            die("<p><b>Feil:</b> Databasen var utilgengelig: (".mysqli_errno($this->connection_id).") (".mysqli_error($this->connection_id).") <a href='loggut.php'>Index</a></p>");
        }

        function query_error()
        {
            global $obj;
            if (!isset($obj)) {
                $feil = '
Bruker som utl&oslash;ste feilen: (Ingen)'.date("H:i:s d.m.Y").': Fil ('.$_SERVER["REQUEST_URI"].') Det oppstod en feil med f&oslash;lgende sp&oslash;rring: '.mysqli_error($this->connection_id).'
Sp&oslash;rringen var: "'.$this->last_query.'
';
            } else {
                $feil = '
Bruker som utl&oslash;ste feilen: ('.$obj->user.'{'.$obj->id.'/'.$_SESSION["sessionzar"][0].'})'.date("H:i:s d.m.Y").': Fil ('.$_SERVER["REQUEST_URI"].') Det oppstod en feil med f&oslash;lgende sp&oslash;rring: '.mysqli_error($this->connection_id).'
Sp&oslash;rringen var: "'.$this->last_query.'
';
            }
            $fil              = "feil.txt";
            $f                = fopen($fil, "a+");
            fwrite($f, $feil);
            fclose($f);
            $this->last_error = "Det var en feil ved sp&oslash;rringen: &quote;".$this->last_query."&quote;(".mysqli_errno($this->connection_id).")Feil: ".mysqli_error($this->connection_id);
            return false;
        }

        function fetch_single($result = 0)
        {
            if (!$result) {
                $result = $this->result;
            }
            return mysqli_result::fetch_field;
        }

        function get_last_error()
        {
            return $this->last_error;
        }

        function mymicro()
        {
            $m = explode(" ", microtime());
            return $m[0] + $m[1];
        }

        function clock_start()
        {
            $this->start_time = $this->mymicro();
        }

        function clock_end()
        {
            $t = $this->mymicro();
            return round($t - $this->start_time, 7);
        }

        function clean_input($in)
        {
            $in = stripslashes($in);
            return str_replace(array("<", ">", '"', "'", "\n"), array("&lt;", "&gt;", "&quot;", "&#39;", "<br>"), $in);
        }

        function clean_input_nohtml($in)
        {
            $in = stripslashes($in);
            return str_replace(array("'"), array("&#39;"), $in);
        }

        function slash($in)
        {
            return addslashes($in);
        }

        function escape($text)
        {
            return mysqli_real_escape_string($this->connection_id, $text);
        }

        function affected_rows()
        {
            return mysqli_affected_rows($this->connection_id);
        }
    }
}
