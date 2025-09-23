<?php
//database

//database credentials
include_once 'db_cred.php';

/**
 *@author David Sampah
 *@version 1.1
 */

 if (!class_exists('db_connection')) {
    class db_connection
    {
        private $db;
        public $results;

        // Constructor establishes a single DB connection
        public function __construct()
        {
            $this->db = mysqli_connect(SERVER, USERNAME, PASSWORD, DATABASE);

            if (mysqli_connect_errno()) {
                die("Database connection failed: " . mysqli_connect_error());
            }
        }

        // Get the connection
        public function db_conn()
        {
            return $this->db;
        }

        // Execute a query
        public function db_query($sqlQuery)
        {
            $this->results = mysqli_query($this->db, $sqlQuery);

            if ($this->results === false) {
                error_log("DB Query Error: " . mysqli_error($this->db));
                return false;
            }
            return true;
        }

        // Fetch one record
        public function db_fetch_one($sql)
        {
            if (!$this->db_query($sql)) {
                return false;
            }
            return mysqli_fetch_assoc($this->results);
        }

        // Fetch all records
        public function db_fetch_all($sql)
        {
            if (!$this->db_query($sql)) {
                return false;
            }
            return mysqli_fetch_all($this->results, MYSQLI_ASSOC);
        }

        // Count rows
        public function db_count()
        {
            if (!$this->results) {
                return false;
            }
            return mysqli_num_rows($this->results);
        }

        // Last inserted ID
        public function last_insert_id()
        {
            return mysqli_insert_id($this->db);
        }
    }
}

?>