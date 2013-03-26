<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
    public function  __construct() {
        parent::__construct();

        $this->auth->is_logged_in(); // if the user is not logged in, redirect him to the login page
        $this->auth->is_admin(); // if the user is not the admin, redirect him to the main page

        $this->load->model('order_model');
        $this->load->model('cart_model');
        $this->load->model('category_model');
        $this->load->model('detail_model');
    }

    public function index()
    {
        $data['admin_welcome'] = 'includes/welcome_message';
        $data['admin_menu'] = 'admin/menu_view';

        $this->load->view('admin/admin_view', $data);
    }

    public function orders()
    {
        $data['admin_welcome'] = 'includes/welcome_message';
        $data['admin_menu'] = 'admin/menu_view';
        
        // get the all the orders from the database
        $orders = $this->order_model->getAllOrders();

        // display the order number, the total price and the date of the order,
        // for each order, a user has made so far
        $this->table->set_heading(
                                   'Order Nr.',
                                   'Total Price',
                                   'Date',
                                   'Made by',
                                   'Email'
                                 );

        foreach($orders as $order){
            $this->table->add_row(
                                   anchor('user/order/'.$order->order_id, '#'.$order->order_id ),
                                   $order->total_price,
                                   $order->date,
                                   $order->first_name.' '.$order->last_name,
                                   $order->email
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

        $table_all_orders = $this->table->generate();

        $data['table_all_orders'] = $table_all_orders;

        $data['content'] = 'admin/all_orders_view';

        $this->load->view('admin/admin_view', $data);

    }

    public function edit_product() {

        // the product that has to be edited is defined by its id and by category id
        $id = (int)$this->uri->segment(6);
        $categ_id = (int)$this->uri->segment(4);

        if($id != 0 && $categ_id != 0){ //if these exist in the URL
            // if the category exists in the database and contains machine parts
            if($this->category_model->category_exists($categ_id) && $this->category_model->is_part($categ_id)){
                if($this->cart_model->product_exists($id)){//if the product exists as a machine part in the database
                    $products = $this->cart_model->getProduct($id);
                    if((int)$products[0]->categ_id === $categ_id){ // if the product's id corresponds to the category's id
                                                             // meaning that the category contains this product (they match)

                      $this->form_validation->set_rules('product_name', 'Product Name', 'trim|required|xss_clean');

                      if($this->form_validation->run() == FALSE){
                        $data['products'] = $products;
                        $data['is_part'] = 1;
                        $data['content'] = 'admin/edit_view';

                        // get all the desciptive details related to the product's name (which represents also
                        // the type of the product - for example: the type might by an air filter
                        $details = $this->category_model->getProductDetails($products[0]->pn_id);

                        $detailAndValue = array(); // the array with detail name and detail value which is sent to the view

                        //$lala = "Inner diameter: 165mm|Outer diameter: 212mm|Height: 57mm|Zen|";

                        $description_lines = explode('|', $products[0]->pd_desc);
                        foreach($description_lines as $line){
                            $detailToCheck = explode(':', $line);
                            if(count($detailToCheck) == 1){
                                $detailAndValue[] = array(strtolower($detailToCheck[0]), '');
                                //$detailToCheck[1] = ''; // if there is no detail value, use an empty string instead
                            }
                            else{
                                $detailAndValue[] = array(strtolower($detailToCheck[0]), $detailToCheck[1]);
                            }
                        }
                        $data['detailAndValue'] = $detailAndValue;
                        $data['details'] = $details;
                      }
                      else {
                          // create the product description:
                          $product_details = $this->input->post('product_details');

                          $final_description = '';
                          foreach($product_details as $key => $p_detail_id){
                              $value_field = $this->input->post('value_field_'.$p_detail_id);
                              
                              // get the detail name with the id = $p_detail_id
                              $p_detail = $this->detail_model->getDetailName($p_detail_id);

                              if(!empty($value_field)){
                                  $final_description .= ucfirst($p_detail[0]->detail_name).': '.$value_field.'|';
                              }
                              else {
                                  $final_description .= ucfirst($p_detail[0]->detail_name).'|';
                              }
                          }

                          $desc_id;
                          // update product
                          if(!$this->cart_model->desc_exists($final_description)){ //if the description doesn't exist in the database

                              // insert the description
                              $this->cart_model->insertDesc($final_description);

                              $desc_id = $this->detail_model->last_id(); // take its id
                          }
                          else{
                              $desc_id = $this->cart_model->desc_exists($final_description);
                          }

                          //PRESUPUNEM ca category, pn_name, producer_name, car type/model/brand exista deja
                          // deci le iau direct id-ul (daca nu presupun asta, fac pentru fiecare exact ca la if-ul de deasupra
                          $pn_id = $this->input->post('pn_id');

                          $product_code;
                          if(!$this->cart_model->ndRow_exists($pn_id, $desc_id)){
                              // insert the row into name_desc, if it doesn't exist
                              $this->cart_model->insertNdRow($pn_id, $desc_id);
                              $product_code = $this->detail_model->last_id(); // take its id
                          }
                          else {
                              $product_code = $this->cart_model->ndRow_exists($pn_id, $desc_id);
                          }

                          $producer_name = $this->input->post('producer_name');

                          // get the producer id corresponding to the producer name
                          $producer_id = $this->cart_model->getProducerId($producer_name);

                          $pp_code;
                          if(!$this->cart_model->pndRow_exists($producer_id, $product_code)){
                              // insert the row into prodr_name_desc, if it doesn't exist
                              $this->cart_model->insertPndRow($producer_id, $product_code);
                              $pp_code = $this->detail_model->last_id(); // take its id
                          }
                          else {
                              $pp_code = $this->cart_model->pndRow_exists($producer_id, $product_code);
                          }

                          $final_id = $this->input->post('id');

                          // finally, update the product
                          $this->cart_model->updateDesc($final_id, $pp_code);


                          //echo $final_description;
                         // die();

                          $this->session->set_flashdata('success_update', 'The product has been successfully updated!');
                          redirect('admin/edit/category/'.$categ_id.'/product/'.$id);
                      }
                    }
                    else{
                        $data['missing_message'] = 'Sorry! No such product within this category!';
                        $data['content'] = 'template_content/missing_view';
                    }
                }
                else{
                    $data['missing_message'] = 'This product doesn\'t exist!';
                    $data['content'] = 'template_content/missing_view';
                }
            }

            // if the category exists in the database and contains universal products
            else if($this->category_model->category_exists($categ_id) && $this->category_model->is_universal($categ_id)){
                if($this->cart_model->universal_exists($id)){//if the product exists as a machine part in the database
                    $products = $this->cart_model->getUniversal($id);
                    if((int)$products[0]->categ_id === $categ_id){ // if the product's id corresponds to the category's id
                                                             // meaning that the category contains this product (they match)
                        $data['products'] = $products;
                        $data['is_part'] = 0;
                        $data['content'] = 'admin/edit_view';
                    }
                    else{
                        $data['missing_message'] = 'Sorry! No such product within this category!';
                        $data['content'] = 'template_content/missing_view';
                    }
                }
                else{
                    $data['missing_message'] = 'This product doesn\'t exist!';
                    $data['content'] = 'template_content/missing_view';
                }
            }
            else { // if the category doesn't exist in the database nor contains machine parts or universal products
                $data['missing_message'] = 'No such category of products!';
                $data['content'] = 'template_content/missing_view';
            }

            $data['admin_welcome'] = 'includes/welcome_message';
            $data['admin_menu'] = 'admin/menu_view';
            
            $this->load->view('admin/admin_view', $data);
        
        }
        
    }

    public function add_detail()
    {
        $new_detail = $this->input->post('new_detail');
        $pn_id = $this->input->post('pn_id');

        if($new_detail && $pn_id){
            // if the detail is already in the details table AND belongs already to this type of product(product name)
            // take the detail's id and throw a message of warning to the user
           if($this->detail_model->is_detail($new_detail) !=FALSE){
               $detail_id = $this->detail_model->is_detail($new_detail);

               if($this->detail_model->forProductName($detail_id, $pn_id)){
                    echo $detail_id.'|This detail already exists for this type of product!';
               }
               else{// if the detail exists but it doesn't belong to this type of product
                    // take the detail's id and display the product detail on the page with the js file

                   // first, insert the detail_id and pn_id in the pn_details table
                   $this->detail_model->insertRow($detail_id, $pn_id);

                   // then, send the $detail id to the page
                   echo $detail_id;
               }
           }
           else{ // if the detail doesn't exist in the database
                 // add it to both details and pn_details tables
                 $this->detail_model->insertDetail($new_detail);
                 $detail_id = $this->detail_model->last_id();

                 $this->detail_model->insertRow($detail_id, $pn_id);

                 echo 'true|'.$detail_id;
           }

        }
        else{
            redirect('admin');
        }
    }

    public function postVal()
    {
        '<pre>'.
        var_dump($this->input->post('product_details'))
        .'</pre>';
    }

}
