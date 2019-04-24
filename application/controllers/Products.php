<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Products extends CIUIS_Controller {

	function __construct() {
		parent::__construct();
		$path = $this->uri->segment( 1 );
		if ( !$this->Privileges_Model->has_privilege( $path ) ) {
			$this->session->set_flashdata( 'ntf3', '' . lang( 'you_dont_have_permission' ) );
			redirect( 'panel/' );
			die;
		}
	}

	function index() {
		$data[ 'title' ] = lang( 'products' );  
		$this->load->view( 'inc/header', $data ); 
		$this->load->view( 'products/index', $data );
		$this->load->view( 'inc/footer', $data );
	}

	function create() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$code = $this->input->post( 'code' );
			$name = $this->input->post( 'name' );
			$categoryid = $this->input->post('categoryid');
			$description = $this->input->post( 'description' );
			$purchaseprice = $this->input->post( 'purchaseprice' );
			$sale_price = $this->input->post( 'saleprice' );
			$stock = $this->input->post( 'stock' );
			$vat = $this->input->post( 'tax' );
			$hasError = false;
			$data['message'] = '';
			if ($name == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('productname');
			} else if ($categoryid == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('productcategory');
			} else if ($purchaseprice == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('purchaseprice');
			} else if ($sale_price == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('salesprice');
			} else if ($description == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('description');
			}
			if ($hasError) {
				$data['success'] = false;
				echo json_encode($data);
			}
			if (!$hasError) {
				$params = array(
					'code' => $code,
					'categoryid' => $categoryid,
					'productname' => $name,
					'description' => $description,
					'purchase_price' => $purchaseprice,
					'sale_price' => $sale_price,
					'stock' => $stock,
					'vat' => $vat,
				);
				$products_id = $this->Products_Model->add_products( $params );
				if ( $this->input->post( 'custom_fields' ) ) {
					$custom_fields = array(
						'custom_fields' => $this->input->post( 'custom_fields' )
					);
					$this->Fields_Model->custom_field_data_add_or_update_by_type( $custom_fields, 'product', $products_id );
				}
				$data['success'] = true;
				$data['message'] = lang('product'). ' ' .lang('createmessage');
				$data['id'] = $products_id;
				echo json_encode($data);
			}
		}
	}

	function categories() {
		$data = $this->Products_Model->get_categories();
		
		echo json_encode( $data );
	}

	function add_category() {
		if (isset($_POST)) {
			$params = array(
				'name' => $this->input->post('name')
			);
			$this->db->insert( 'productcategories', $params );
			$id = $this->db->insert_id();
			if ($id) {
				$data['success'] = true;
				$data['message'] = lang('productcategory'). ' ' .lang('createmessage');
			}
			echo json_encode($data);
		}
	}

	function update_category( $id ) {
		$data[ 'category' ] = $this->Products_Model->get_category( $id );
		if ( isset( $data[ 'category' ][ 'id' ] ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$params = array(
					'name' => $this->input->post( 'name' ),
				);
				$this->Products_Model->update_category( $id, $params );
				$data['success'] = true;
				$data['message'] = lang('productcategory'). ' ' .lang('updatemessage');
				echo json_encode($data);
			}
		}
	}

	function remove_category( $id ) {
		$category = $this->Products_Model->get_category( $id );
		if ( isset( $category[ 'id' ] ) ) {
			$this->Products_Model->remove_category( $id );
			$data['success'] = true;
			$data['message'] = lang('productcategory'). ' ' .lang('deletemessage');
			echo json_encode($data);
		}
	}

	function get_product_categories() {
		$categories = $this->Products_Model->get_product_categories();
		$data_categories = array();
		foreach ( $categories as $category ) {
			$data_categories[] = array(
				'name' => $category[ 'name' ],
				'id' => $category[ 'id' ],
			);
		};
		echo json_encode( $data_categories );
		
	}

	function update( $id ) {
		if ( isset( $id ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$code = $this->input->post( 'code' );
				$name = $this->input->post( 'name' );
				$categoryid = $this->input->post('categoryid');
				$description = $this->input->post( 'description' );
				$purchaseprice = $this->input->post( 'purchaseprice' );
				$sale_price = $this->input->post( 'saleprice' );
				$stock = $this->input->post( 'stock' );
				$vat = $this->input->post( 'tax' );
				$hasError = false;
				$data['message'] = '';
				if ($name == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('productname');
				} else if ($categoryid == '') {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage'). ' ' .lang('productcategory');
				} else if ($purchaseprice == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('purchaseprice');
				} else if ($sale_price == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('salesprice');
				} else if ($description == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('description');
				}
				if ($hasError) {
					$data['success'] = false;
					echo json_encode($data);
				}
				if (!$hasError) {
					$params = array(
						'code' => $code,
						'categoryid' => $categoryid,
						'productname' => $name,
						'description' => $description,
						'purchase_price' => $purchaseprice,
						'sale_price' => $sale_price,
						'stock' => $stock,
						'vat' => $vat,
					);
					$this->Products_Model->update_products( $id, $params );
					// Custom Field Post
					if ( $this->input->post( 'custom_fields' ) ) {
						$custom_fields = array(
							'custom_fields' => $this->input->post( 'custom_fields' )
						);
						$this->Fields_Model->custom_field_data_add_or_update_by_type( $custom_fields, 'product', $id );
					}
					$data['success'] = true;
					$data['message'] = lang('product'). ' ' .lang('updatemessage');
					echo json_encode($data);
				}
			}
		}
	}

	function productsimport () {
		$this->load->library( 'import' );
		$data[ 'products' ] = $this->Products_Model->get_products_for_import();
		$data[ 'error' ] = '';
		$config[ 'upload_path' ] = './uploads/imports/';
		$config[ 'allowed_types' ] = 'csv';
		$config[ 'max_size' ] = '1000';
		$this->load->library( 'upload', $config );
		// If upload failed, display error
		if ( !$this->upload->do_upload() ) {
			$data[ 'error' ] = $this->upload->display_errors();
			$this->session->set_flashdata( 'ntf4', 'Csv Data not Imported' );
			redirect( 'products/index' );
		} else {
			$file_data = $this->upload->data();
			$file_path = './uploads/imports/' . $file_data[ 'file_name' ];
			if ( $this->import->get_array( $file_path ) ) {
				$csv_array = $this->import->get_array( $file_path );
				foreach ( $csv_array as $row ) {
					$insert_data = array(
						'code' => $row[ 'code' ],
						'productname' => $row[ 'productname' ],
						'description' => $row[ 'description' ],
						'purchase_price' => $row[ 'purchase_price' ],
						'sale_price' => $row[ 'sale_price' ],
						'stock' => $row[ 'stock' ],
						'vat' => $row[ 'vat' ],
						'status_id' => $row[ 'status_id' ]
					);
					$this->Products_Model->insert_products_csv( $insert_data );
				}
				$this->session->set_flashdata( 'ntf1', lang('csvimportsuccess') );
				redirect( 'products/index' );
			} else
				redirect( 'products/index' );
			$this->session->set_flashdata( 'ntf3', 'Error' );
		}
	}

	function exportdata() {
		$this->load->dbutil();
		$this->load->helper('file');
		$this->load->helper('download');

		$this->db->select('products.productname, products.description, products.code, products.purchase_price, products.sale_price, products.stock, products.vat, products.status_id, productcategories.name as category, productcategories.id as category_id, productimage');
		$this->db->join( 'productcategories', 'products.categoryid = productcategories.id', 'left' );
		$q = $this->db->get_where( 'products', array( '') );
		$delimiter = ",";
		$nuline    = "\r\n";
		force_download('Products.csv', $this->dbutil->csv_from_result($q, $delimiter, $nuline));
	}

	function product( $id ) {
		$data[ 'title' ] = lang( 'product' );
		$data[ 'product' ] = $this->Products_Model->get_product_by_id( $id );
		$this->load->view( 'inc/header', $data );
		$this->load->view( 'products/product', $data );
		$this->load->view( 'inc/footer', $data );
	}

	function remove( $id ) {
		$products = $this->Products_Model->get_product_by_id( $id );
		if ( isset( $products[ 'id' ] ) ) {
			$this->Products_Model->delete_products( $id );
			$data['success'] = true;
			$data['message'] = lang('product'). ' ' .lang('deletemessage');
			echo json_encode($data);
		} else {
			show_error( 'The products you are trying to delete does not exist.' );
		}
	}
}