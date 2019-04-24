<?php

class Products_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function get_products( $id ) {
		$this->db->select('productcategories.name, productcategories.id as categoryid, products.id as id, products.code, products.productname, products.description, products.purchase_price, products.sale_price, products.stock, products.vat, products.status_id, products.productimage');
		$this->db->join( 'productcategories', 'products.categoryid = productcategories.id', 'inner' );
		return $this->db->get_where( 'products', array( 'products.id' => $id ) )->row_array();
	}

	function get_product_by_id( $id ) {
		$this->db->select('*');
		return $this->db->get_where( 'products', array( 'id' => $id ) )->row_array();
	}

	function get_all_products() { 
		$this->db->select('productcategories.name, productcategories.id as categoryid, products.id as id, products.code, products.productname, products.description, products.purchase_price, products.sale_price, products.stock, products.vat, products.status_id, products.productimage');
		$this->db->join( 'productcategories', 'products.categoryid = productcategories.id', 'left' );
		$this->db->order_by( 'products.id', 'desc' );
		return $this->db->get_where( 'products', array( '' ) )->result_array();
	}

	function getallproductsjson() {
		$this->db->select( 'id id,code code,productname label,sale_price sale_price,vat vat' );
		$this->db->from( 'products' );
		return $this->db->get()->result();
	}

	function add_products( $params ) {
		$this->db->insert( 'products', $params );
		$product = $this->db->insert_id();
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $this->session->usr_id . '"> ' . $this->session->staffname . '</a> ' . lang( 'addedanewproduct' ) . ' <a href="products/product/' . $product . '"> ' . lang( 'product' ) . '' . $product . '</a>' ),
			'staff_id' => $this->session->usr_id
		) );
		return $product;
	}

	function insert_products_csv($data) {
        $this->db->insert('products', $data);
    }

	function update_products( $id, $params ) {
		$this->db->where( 'id', $id );
		$response = $this->db->update( 'products', $params );
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $this->session->usr_id . '"> ' . $this->session->staffname . '</a> ' . lang( 'updated' ) . ' <a href="products/product/' . $id . '"> ' . lang( 'product' ) . '' . $id . '</a>' ),
			'staff_id' => $this->session->usr_id
		) );
	}

	function get_products_for_import() {     
        $query = $this->db->get('products');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

	function get_product_categories() {
		$this->db->order_by( 'id', 'desc' );
		return $this->db->get_where( 'productcategories', array( '' ) )->result_array();
	}

	function get_categories() {
		$this->db->select('productcategories.name as name, COUNT(productcategories.name) as y');
		$this->db->join( 'productcategories', 'products.categoryid = productcategories.id', 'left' );
		$this->db->group_by('productcategories.name'); 
		//$this->db->order_by('total', 'desc'); 
		return $this->db->get_where( 'products', array( '' ) )->result_array();
	}

	function get_category( $id ) {
		return $this->db->get_where( 'productcategories', array( 'id' => $id ) )->row_array();
	}

	function update_category( $id, $params ) {
		$this->db->where( 'id', $id );
		return $this->db->update( 'productcategories', $params );
	}

	function remove_category( $id ) {
		$response = $this->db->delete( 'productcategories', array( 'id' => $id ) );
	}

	function delete_products( $id ) {
		$response = $this->db->delete( 'products', array( 'id' => $id ) );
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $this->session->usr_id . '"> ' . $this->session->staffname . '</a> ' . lang( 'deleted' ) . ' ' . lang( 'product' ) . '-' . $id . '' ),
			'staff_id' => $this->session->usr_id
		) );
		$response = $this->db->delete( 'custom_fields_data', array( 'relation_type' => 'product', 'relation' => $id ) );
	}
}