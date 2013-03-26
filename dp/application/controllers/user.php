<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

    public function  __construct() {
        parent::__construct();

        // if the user is logged in and he is the admin, redirect him to the admin's section (Admin controller)
        /*if ($this->session->userdata('is_logged_in') == TRUE && $this->config->item('admin_level') == $this->session->userdata('user_level')) :

		redirect('admin', 'location');

	endif;

	/*if ($this->session->userdata('is_logged_in') == TRUE && $this->config->item('admin_level') != $this->session->userdata('user_level')) :

		redirect('secure', 'location');

	endif;*/

        $this->load->model('user_model');
        $this->load->model('order_model');
    }

    public function index()
    {
        redirect('brand'); //avoid accessing the '/user/' URI
    }

    function login() {

        // breadcrumbs section
        $breadc = array('Sign In');
        $data['breadcrumbs'] = $breadc;

        // validate data
        $this->form_validation->set_rules('email', 'Email', 'required|trim|max_length[40]|xss_clean|valid_email|callback__check_email_exist_login');
	$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[4]|max_length[20]|xss_clean');

	if ($this->form_validation->run() == FALSE) {
             // if the data doesn't get sent (isn't valid), display the login page (with the error messages)
             $data['content'] = 'users/login_view';
	     $this->load->view('template_view', $data);

	} else {
	     $result = $this->auth->login($this->input->post('email'), sha1($this->config->item('encryption_key').$this->input->post('password')));

	     if ($result['is_true'] == TRUE &&  $result['is_admin'] == TRUE) {
		 // if the user is the admin, redirect him to the admin's section (the Admin controller)

                 $this->_destroy_cart(); //destroy the cart, first
                 // the welcome message appearing in top-right, when the user is logged in
                 //$this->session->set_userdata('message', $result['message']);
                 redirect('admin', 'location');

	     } elseif ($result['is_true'] == TRUE && $result['is_admin'] == FALSE) {
                 // if the user reaches to log in, redirect him to the home page

                 // the welcome message appearing in top-right, when the user is logged in
                 //$this->session->set_userdata('message', $result['message']);
		 redirect('brand', 'location');
                 
	     } else {
                 
		$data['message'] = $result['message'];
				
                $data['content'] = 'users/login_view';

		$this->load->view('template_view', $data);
	    }

		}

    }

    // display the orders a users has done so far
    public function orders()
    {
        $this->auth->is_logged_in(); // if the user is not logged in, redirect him to the login page

        if ($this->config->item('admin_level') == $this->session->userdata('user_level')) :
        // if the user is the admin, redirect him to the list of orders made by all the users
		redirect('admin/orders', 'location');

	endif;

        $user_id = $this->session->userdata('user_id'); // get the user's id from the session

        // breadcrumbs section
        $breadc = array('Your personal orders');
        $data['breadcrumbs'] = $breadc;

        // get the all the user's orders
        $orders = $this->user_model->getOrders($user_id);

        // display the order number, the total price and the date of the order,
        // for each order, a user has made so far
        $this->table->set_heading('Order Nr.', 'Total Price', 'Date');

        foreach($orders as $order){
            $this->table->add_row(
                                   anchor('user/order/'.$order->order_id, '#'.$order->order_id ),
                                   $order->total_price,
                                   $order->date
                                 );
        }

        // set a template for the table
                    $tmpl = array (
                                    'table_open' => '<table border="0" cellpadding="4" cellspacing="0">',

                                    'heading_row_start'  => '<tr>',
                                    'heading_row_end'    => '</tr>',
                                    'heading_cell_start' => '<th>',
                                    'heading_cell_end'   => '</th>',

                                    'row_start'          => '<tr class="row_grey">',
                                    'row_end'            => '</tr>',
                                    'cell_start'         => '<td>',
                                    'cell_end'           => '</td>',

                                    'row_alt_start'       => '<tr class="row_white">',
                                    'row_alt_end'         => '</tr>',
                                    'cell_alt_start'      => '<td>',
                                    'cell_alt_end'        => '</td>',

                                    'table_close'         => '</table>'
                                  );

                                    $this->table->set_template($tmpl);

        $table_orders = $this->table->generate();

        $data['table_orders'] = $table_orders;

        $data['content'] = 'users/orders_view';

        $this->load->view('template_view', $data);


    }

    public function order($order_id)
    {
        $this->auth->is_logged_in(); // if the user is not logged in, redirect him to the login page

        /*if ($this->config->item('admin_level') == $this->session->userdata('user_level')) :
        // if the user is the admin, redirect him to the list of orders made by all the users
		redirect('admin/orders', 'location');

	endif;*/

        $order_id = (int)$order_id; // sanitize data

        $user_id = $this->session->userdata('user_id'); // get the user's id from the session

        // breadcrumbs section
        $breadc = array('Your personal orders', 'user/orders', 'Order Nr. #'.$order_id);
        $data['breadcrumbs'] = $breadc;

        if($order_id != 0 && $this->order_model->order_exists($order_id)){ // if the order exists in the database

            if($this->user_model->userOrderMatch($user_id, $order_id) ||
               $this->config->item('admin_level') == $this->session->userdata('user_level')){ // if the order belongs to he current user
                                                                                              // or the user is the admin, thus he has access
                                                                                              // to all the orders

               // display the name, bought quantity, price per product,
               // and subtotal, for each product displayed on the order
                $this->table->set_heading(
                                           'Product Name',
                                           'Car Brand',
                                           'Car Model',
                                           'Car Type',
                                           'Producer',
                                           'Quantity Bought',
                                           'Product Price',
                                           'Subtotal'
                                         );

                $products = $this->order_model->getOrderProducts($order_id); // get the machine parts products from the database

                foreach($products as $product){
                    $this->table->add_row(
                                           $product->pn_name,
                                           $product->brand_name,
                                           $product->model_name,
                                           $product->type_name,
                                           $product->producer_name,
                                           $product->qty_bought,
                                           $product->item_price,
                                           $product->subtotal
                                         );
                }

                $universals = $this->order_model->getOrderUniversals($order_id); // get the universal products from the database

                foreach($universals as $universal){
                    $this->table->add_row(
                                           $universal->pn_name,
                                           '-',
                                           '-',
                                           '-',
                                           $universal->producer_name,
                                           $universal->qty_bought,
                                           $universal->item_price,
                                           $universal->subtotal
                                         );
                }

                // set a template for the table
                    $tmpl = array (
                                    'table_open' => '<table border="0" cellpadding="4" cellspacing="0">',

                                    'heading_row_start'  => '<tr>',
                                    'heading_row_end'    => '</tr>',
                                    'heading_cell_start' => '<th>',
                                    'heading_cell_end'   => '</th>',

                                    'row_start'          => '<tr class="row_grey">',
                                    'row_end'            => '</tr>',
                                    'cell_start'         => '<td>',
                                    'cell_end'           => '</td>',

                                    'row_alt_start'       => '<tr class="row_white">',
                                    'row_alt_end'         => '</tr>',
                                    'cell_alt_start'      => '<td>',
                                    'cell_alt_end'        => '</td>',

                                    'table_close'         => '</table>'
                                  );

                                    $this->table->set_template($tmpl);

                $table_order_products = $this->table->generate();

                $data['table_order_products'] = $table_order_products;

                $data['content'] = 'users/order_products_view';

                if($this->config->item('admin_level') == $this->session->userdata('user_level')){
                    $data['admin_welcome'] = 'includes/welcome_message';
                    $data['admin_menu'] = 'admin/menu_view';
                    
                    $this->load->view('admin/admin_view', $data);
                }
                else{
                    $this->load->view('template_view', $data);
                }

            }
            else {
                redirect('user/orders');
            }
        }
        else { // if the order doesn't exist in the database
            if($this->config->item('admin_level') == $this->session->userdata('user_level')){
                redirect('admin/orders'); // if the user is the admin, redirect him to his 'orders' page
            }
            redirect('user/orders'); // if the order doesn't exist in the database,
                                     //redirect the user to his list of orders
        }
    }

    function logout()
    {
        $this->auth->logout();

	$this->session->set_flashdata('message', 'You successfully logged out');

	redirect('user/login', 'location');
    }
    
    //required callback -- codeigniter forces these to be in the controller -- this function is private

    function _check_email_exist_login($email) {

		$this->load->model('user_model');

		$result = $this->user_model->check_email_exist($email);

		

		if ($result == FALSE) {

			$this->form_validation->set_message('_check_email_exist_login', 'The email "'.$email .'" doesn\'t exist!');

			return FALSE;

		} else {

			return TRUE;

		}

	}

   // used when the admin logs in
   private function _destroy_cart()
    {
        $contents = $this->cart->contents();
        if(isset($contents) && !empty($contents)){
            $this->cart->destroy();
        }
    }





}
