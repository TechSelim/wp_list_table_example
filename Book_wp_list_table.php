<?php

if ( !class_exists('WP_List_Table') ) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class WPlist_table extends  WP_List_Table{

    /** Class constructor */
    public function __construct()
    {

        parent::__construct([
            'singular' => __('Customer', 'sp'), //singular name of the listed records
            'plural'   => __('Customers', 'sp'), //plural name of the listed records
            'ajax'     => false //should this table support ajax?

        ]);
    }

    function get_columns()
    {
        $columns = array(
            'cb' => 'CB',
            'booktitle' => 'Title',
            'author'    => 'Author',
            'isbn'      => 'ISBN',
            'selim'      => 'MILES'
        );
        return $columns;
    }

    function prepare_items(){

        $pageNum = $this->get_pagenum();
        $perPage = 2;

        $columns = $this->get_columns();
        $this->set_pagination_args([
            'total_items' => $this->get_total_row(), 
            'per_page'    => $perPage 
        ]);
            
        
        
        $hidden = array();
        $sortable = array();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $this->get_my_data($pageNum, $perPage);
    }

    function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'booktitle':
            case 'author':
            case 'isbn':
            case 'selim':
                return $item[$column_name];
            default:
                return print_r($item, true); 
        }
    }
    function column_selim( $item ){
        return '<a href="https://google.com/">'. $item['selim'] .'</a>';
    }

    function column_booktitle($item)
    {
        $actions = array(
            'edit' => sprintf('<a href="?page=%s&action=%s& hotel=%s">Edit</a>', $_REQUEST['page'], 'edit', $item['id']),
            'delete' => sprintf('<a href="?page=%s&action=%s&hotel=%s">Delete</a>', $_REQUEST['page'], 'delete', $item['id']),
        );
        return sprintf('%1$s %2$s', $item['booktitle'], $this->row_actions($actions));
    }

    function get_my_data($offset, $perPage){
        global $wpdb;
        $offset = ($offset - 1) * $perPage;
        return $wpdb->get_results("SELECT * FROM wp_testtable ORDER BY id DESC limit $offset, $perPage", 'ARRAY_A');
    }
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="bulk-delete[]" value="%s" />',
            $item['ID']
        );
    }
    function get_total_row(){
        global $wpdb;
        return $wpdb->get_var("SELECT COUNT(id) FROM wp_testtable");
    }
    
}
