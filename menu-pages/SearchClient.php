<?php
if(!class_exists("SearchClient")) {
    class SearchClient
    {
        //client  search
        public static function AllClinet($noofrow,$offset,$table_name,$findby) {
            global $wpdb;
            if($findby) {
                $all_client = $wpdb->get_results("select * from `$table_name` WHERE `name` LIKE '%$findby%' limit $offset,$noofrow");
                return $all_client;
            } else {
                $all_client = $wpdb->get_results("select * from `$table_name` limit $offset,$noofrow");
                return $all_client;
            }
        }

        //client count list
        public static function CountClienttable($table_name,$findby) {
            global $wpdb;
            if($findby) {
                $cat = $wpdb->get_results("SELECT * FROM `$table_name` WHERE `name` LIKE '%$findby%' ORDER BY `name` DESC");
                return $cat;
            } else {
                $cat = $wpdb->get_results("select * from `$table_name` ORDER BY `name` DESC");
                return $cat;
            }
        }
    }
}