<?php

    class Pagination {

        public $current_page;
        public $per_page;
        public $total_count;

        public function __construct($page=1, $per_page=20, $total_count=0) {
            $this->current_page = (int)$page;
            $this->per_page     = (int)$per_page;
            $this->total_count  = (int)$total_count;
        }
/**
 * The offset is calculated by the per_page amount times 1 minus the page
 * number
 * @return int
 */
        public function offset() {
            return ($this->current_page - 1) * $this->per_page;
        }

/**
 * Builds the sql required to pass to the User:: and Photograph::find_by_sql()
 * so that I create pages instead of returning all objects from the database
 * @param  string $table_name Name of the database table.
 * @return string             The sql for find_by_sql()
 */
        public function build_sql($table_name="") {
            $sql  = "SELECT * FROM {$table_name} ";
            $sql .= "LIMIT {$this->per_page} ";
            $sql .= "OFFSET {$this->offset()}";
            return $sql;
        }

        public function total_pages() {
            return ceil($this->total_count/$this->per_page);
        }

        public function next_page() {
            return ($this->current_page + 1);
        }

        public function pervious_page() {
            return ($this->current_page - 1);
        }

        public function has_next_page() {
            return $this->current_page >= $this->total_pages() ? false : true;
        }

        public function has_previous_page() {
            return $this->current_page <= 1 ? false : true;
        }

    }
?>