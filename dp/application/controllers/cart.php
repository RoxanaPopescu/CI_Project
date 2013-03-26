<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cart extends CI_Controller {
    public function  __construct() {
        parent::__construct();
        $this->load->model('cart_model');
        $this->load->model('category_model');
        $this->load->model('user_model');
        $this->load->model('order_model');
    }

    public function index(){
        
    }

    public function add()
    {
        $id = $this->input->post('product_id'); // assign the posted product_id to $id
        $qty = $this->input->post('quantity'); // assign the posted quantity to $qty
        $category = $this->input->post('category'); // assign the posted category to $category
        
        //$cct = $this->input->post('csrf_test_name');

        // check if the product the user wants to add, exists in the database and if it has a valid category
        if(isset($id) && isset($category) && $this->category_model->is_part($category) && $this->cart_model->product_exists($id)){
            if(!empty($qty)){ //if the user hasn't chosen any quantity, throw an error
                if(is_numeric($qty) && $qty == (int)$qty && (int)($qty) > 0){ // check if the quantity specified is a positive, integer number
                    
                    // check if the quantity specified surpasses the product's available quantity
                    $avail_qty = $this->cart_model->getAvailableQty($id); // get the available quantity
                    if((int)$qty > $avail_qty[0]->available_qty){
                        echo $avail_qty[0]->available_qty;
                    }
                    else {
                        $product = $this->cart_model->getProduct($id);
                        // insert the product item(s) in the shopping cart
                        $data = array(
                                       'id' => $id,
                                       'name' => $product[0]->pn_name,
                                       'price' => $product[0]->product_price,
                                       'qty' => $qty,
                                       'options' => array(
                                                           'product_image' => $product[0]->product_image,
                                                           'type_name' => $product[0]->type_name,
                                                           'ctype_id' => $product[0]->ctype_id,
                                                           'model_name' => $product[0]->model_name,
                                                           'cmodel_id' => $product[0]->cmodel_id,
                                                           'brand_name' => $product[0]->brand_name,
                                                           'cbrand_id' => $product[0]->cbrand_id,
                                                           'producer' => $product[0]->producer_name,
                                                           'description' => $product[0]->pd_desc,
                                                           'category' => $product[0]->categ_name,
                                                           'categ_id' => $product[0]->categ_id,
                                                           'current_qty' => $product[0]->available_qty
                                                         )
                                     );

                        $qtyToAdd = (int)$data['qty'];
                        $error = '';
                        foreach($this->cart->contents() as $item){
                            if($item['id'] === $data['id'] && $item['options']['categ_id'] === $data['options']['categ_id']){ // if the product has been added before to the cart
                                $qtyToAdd += (int)$item['qty'];// add the new quantity to the existing one 

                                if($item['options']['current_qty'] == 0){
                                    $error = 'No more items available for this product';
                                }
                                else if((int)$data['qty'] > (int)$item['options']['current_qty']){
                                    $error = 'You can add only '.$item['options']['current_qty'].
                                         ' more items from this product';
                                }
                                else{
                                    $current_qty = (string)((int)$item['options']['current_qty'] - (int)$data['qty']);
                                    $update_data = array(
                                                            'rowid' => $item['rowid'],
                                                            'qty' => (string)$qtyToAdd,
                                                            'options' => array(
                                                                               'current_qty' => (string)$current_qty
                                                                              )
                                                        );
                                    /*var_dump($update_data);
                                    die();*/
                                }
                                break;
                            }
                        }

                        if(!empty($error)){
                            echo $error;
                        }
                        else{
                            if($qtyToAdd != (int)$data['qty']){
                                $this->cart->update($update_data);
                               // $data['options']['current_qty'] -= (int)$data['qty'];

                                // if a product's quantity gets updated within the cart,
                                // send the product's id, the new quantity required and the subtotal price
                                // to be dispalyed with jquery
                                $sendToCart = $data['id'].'|'.$update_data['qty'].'|'.(int)$update_data['qty']*$data['price'];
                            }
                            else{
                                $data['options']['current_qty'] = (string)((int)$product[0]->available_qty - (int)$data['qty']);
                                $this->cart->insert($data);
                                //$data['options']['current_qty'] = (int)$product[0]->available_qty - (int)$data['qty'];

                                // if a product is inserted for the first time in the cart,
                                // send the product's id, name and producer,
                                // together with the product's quantity and price
                                $sendToCart = $data['id'].'|'.$data['name'].'|'.$data['options']['producer'].'|'.$data['qty']
                                              .'|'.$data['price'].'|'.$data['qty']*$data['price'];

                            }
                        // update the remaining quantity in the database
                            $total_items = $this->cart->total_items(); // the current number of items added to the cart
                            $total_sum = $this->cart->total(); // the current total amount in the cart
                            $data = 'true|'.$total_items.'|'.$sendToCart.'|'.$total_sum.'|'.$category;// send 'true', the current number of items and the details for the added product
                            
                            echo $data;
                       }
                    }


                    //echo 'true';
                }
                else {
                    echo 'Please enter a numeric value for the quantity chosen!';
                }
            }
            else {
                echo 'Please specify a quantity!';
            }
        }
        else {
            redirect('brand');
        }

    }

    public function add_universal()
    {
        $id = $this->input->post('product_id'); // assign the posted product_id to $id
        $qty = $this->input->post('quantity'); // assign the posted quantity to $qty
        $category = $this->input->post('category'); // assign the posted category to $category
        //$cct = $this->input->post('csrf_test_name');

        // check if the universal product the user wants to add, exists in the database and if it has a valid category
        if(isset($id) && isset($category) && $this->category_model->is_universal($category) && $this->cart_model->universal_exists($id)){
            if(!empty($qty)){ //if the user hasn't chosen any quantity, throw an error
                if(is_numeric($qty) && $qty == (int)$qty && (int)($qty) > 0){ // check if the quantity specified is a positive, integer number

                    // check if the quantity specified surpasses the product's available quantity
                    $avail_qty = $this->cart_model->universalAvailableQty($id); // get the available quantity
                    if((int)$qty > $avail_qty[0]->available_qty){
                        echo $avail_qty[0]->available_qty;
                    }
                    else {
                        $product = $this->cart_model->getUniversal($id); // get the universal product with the id = $id
                        // insert the product item(s) in the shopping cart
                        $data = array(
                                       'id' => $id,
                                       'name' => $product[0]->pn_name,
                                       'price' => $product[0]->product_price,
                                       'qty' => $qty,
                                       'options' => array(
                                                           'product_image' => $product[0]->product_image,
                                                           'producer' => $product[0]->producer_name,
                                                           'description' => $product[0]->pd_desc,
                                                           'category' => $product[0]->categ_name,
                                                           'categ_id' => $product[0]->categ_id,
                                                           'current_qty' => $product[0]->available_qty
                                                         )
                                     );


                        $qtyToAdd = (int)$data['qty'];
                        $error = '';
                        foreach($this->cart->contents() as $item){
                            if($item['id'] === $data['id'] && $item['options']['categ_id'] === $data['options']['categ_id']){ // if the product has been added before to the cart
                                $qtyToAdd += (int)$item['qty'];// add the new quantity to the existing one

                                if($item['options']['current_qty'] == 0){
                                    $error = 'No more items available for this product';
                                }
                                else if((int)$data['qty'] > (int)$item['options']['current_qty']){
                                    $error = 'You can add only '.$item['options']['current_qty'].
                                         ' more items from this product';
                                }
                                else{
                                    $current_qty = (string)((int)$item['options']['current_qty'] - (int)$data['qty']);
                                    $update_data = array(
                                                            'rowid' => $item['rowid'],
                                                            'qty' => (string)$qtyToAdd,
                                                            'options' => array(
                                                                               'current_qty' => (string)$current_qty
                                                                              )
                                                        );
                                    /*var_dump($update_data);
                                    die();*/
                                }
                                break;
                            }
                        }

                        if(!empty($error)){
                            echo $error;
                        }
                        else{
                            if($qtyToAdd != (int)$data['qty']){
                                $this->cart->update($update_data);
                               // $data['options']['current_qty'] -= (int)$data['qty'];

                                // if a product's quantity gets updated within the cart,
                                // send the product's id, the new quantity required and the subtotal price
                                // to be dispalyed with jquery
                                $sendToCart = $data['id'].'|'.$update_data['qty'].'|'.(int)$update_data['qty']*$data['price'];
                            }
                            else{
                                $data['options']['current_qty'] = (string)((int)$product[0]->available_qty - (int)$data['qty']);
                                $this->cart->insert($data);
                                //$data['options']['current_qty'] = (int)$product[0]->available_qty - (int)$data['qty'];

                                // if a product is inserted for the first time in the cart,
                                // send the product's id, name and producer,
                                // together with the product's quantity and price
                                $sendToCart = $data['id'].'|'.$data['name'].'|'.$data['options']['producer'].'|'.$data['qty']
                                              .'|'.$data['price'].'|'.$data['qty']*$data['price'];

                            }
                        // update the remaining quantity in the database
                            $total_items = $this->cart->total_items(); // the current number of items added to the cart
                            $total_sum = $this->cart->total(); // the current total amount in the cart
                            $data = 'true|'.$total_items.'|'.$sendToCart.'|'.$total_sum.'|'.$category;// send 'true', the current number of items and the details for the added product

                            echo $data;
                       }
                    }


                    //echo 'true';
                }
                else {
                    echo 'Please enter a numeric value for the quantity chosen!';
                }
            }
            else {
                echo 'Please specify a quantity!';
            }
        }
        else {
            redirect('brand');
        }
    }

    public function view()
    {
        $breadc = array('View your cart');
        $cart_contents = $this->cart->contents();
        $data['breadcrumbs'] = $breadc;

        if(!$cart_contents){ // if the cart doesn't contain any products, display a message
            $data['missing_message'] = 'You don\'t have any products in your cart.';
            $data['content'] = 'template_content/missing_view';
        }

        else {
            $data['cart_contents'] = $cart_contents;
            $data['content'] = 'template_content/cart_update_view';
        }

        $this->load->view('template_view', $data); // send the content of the shopping cart to the view
    }

    public function update()
    {
        $id = $this->input->post('product_id'); // assign the posted product_id to $id
        $qty = $this->input->post('quantity'); // assign the posted quantity to $qty
        $category = $this->input->post('category'); // assign the posted category to $category
        //$cct = $this->input->post('csrf_test_name');

        // check if the product the user wants to update, exists in the database and in the cart
        if(isset($id) && isset($category) && $this->category_model->is_part($category) && $this->cart_model->product_exists($id) && $this->_existsInCart($id, $category)){
            if(!empty($qty)){ //if the user hasn't chosen any quantity, throw an error
                if(is_numeric($qty) && $qty == (int)$qty && (int)($qty) > 0){ // check if the quantity specified is a positive, integer number

                    // check if the quantity specified surpasses the product's available quantity
                    $avail_qty = $this->cart_model->getAvailableQty($id); //get the quantity of the product, from the database
                    if((int)$qty > $avail_qty[0]->available_qty){
                        echo $avail_qty[0]->available_qty;
                    }
                    else {
                        
                        $qtyToAdd = (int)$qty;
                        $error = '';
                        foreach($this->cart->contents() as $item){
                            if($item['id'] === $id && $item['options']['categ_id'] === $category){ // if the product has been added before to the cart

                                 $current_qty = (string)((int)$avail_qty[0]->available_qty - (int)$qty);
                                 $update_data = array(
                                                         'rowid' => $item['rowid'],
                                                         'qty' => (string)$qtyToAdd,
                                                         'options' => array(
                                                                             'current_qty' => (string)$current_qty
                                                                           )
                                                        );                                   
                                break;
                            }
                        }

                        if(!empty($error)){
                            echo $error;
                        }
                        else{
                            if(isset($update_data) && !empty($update_data)){
                                $this->cart->update($update_data);

                               // if a product's quantity gets updated within the cart,
                                // send the product's id, the new quantity required and the subtotal price
                                // to be dispalyed with jquery
                                $sendToCart = $id.'|'.$update_data['qty'].'|'.(int)$update_data['qty']*$this->_itemPrice($id, $category);
                            }
                            
                            $total_items = $this->cart->total_items(); // the current number of items added to the cart
                            $total_sum = $this->cart->total(); // the current total amount in the cart
                            $data = 'true|'.$total_items.'|'.$sendToCart.'|'.$total_sum.'|'.$category;// send 'true', the current number of items and the details for the added product

                            echo $data;
                       }
                    }
                }
                else {
                    echo 'Please enter a numeric value for the quantity chosen!';
                }
            }
            else {
                echo 'Please specify a quantity!';
            }
        }
        else {

            redirect('brand');
        }
    }

    public function update_universal()
    {
        $id = $this->input->post('product_id'); // assign the posted product_id to $id
        $qty = $this->input->post('quantity'); // assign the posted quantity to $qty
        $category = $this->input->post('category'); // assign the posted category to $category
        //$cct = $this->input->post('csrf_test_name');

        // check if the product the user wants to update, exists in the database and in the cart
        if(isset($id) && isset($category) && $this->category_model->is_universal($category) && $this->cart_model->universal_exists($id) && $this->_existsInCart($id, $category)){
            if(!empty($qty)){ //if the user hasn't chosen any quantity, throw an error
                if(is_numeric($qty) && $qty == (int)$qty && (int)($qty) > 0){ // check if the quantity specified is a positive, integer number

                    // check if the quantity specified surpasses the product's available quantity
                    $avail_qty = $this->cart_model->universalAvailableQty($id); //get the quantity of the product, from the database
                    if((int)$qty > $avail_qty[0]->available_qty){
                        echo $avail_qty[0]->available_qty;
                    }
                    else {

                        $qtyToAdd = (int)$qty;
                        $error = '';
                        foreach($this->cart->contents() as $item){
                            if($item['id'] === $id && $item['options']['categ_id'] === $category){ // if the product has been added before to the cart

                                 $current_qty = (string)((int)$avail_qty[0]->available_qty - (int)$qty);
                                 $update_data = array(
                                                         'rowid' => $item['rowid'],
                                                         'qty' => (string)$qtyToAdd,
                                                         'options' => array(
                                                                             'current_qty' => (string)$current_qty
                                                                           )
                                                        );
                                break;
                            }
                        }

                        if(!empty($error)){
                            echo $error;
                        }
                        else{
                            if(isset($update_data) && !empty($update_data)){
                                $this->cart->update($update_data);

                               // if a product's quantity gets updated within the cart,
                                // send the product's id, the new quantity required and the subtotal price
                                // to be dispalyed with jquery
                                $sendToCart = $id.'|'.$update_data['qty'].'|'.(int)$update_data['qty']*$this->_itemPrice($id, $category);
                            }

                            $total_items = $this->cart->total_items(); // the current number of items added to the cart
                            $total_sum = $this->cart->total(); // the current total amount in the cart
                            $data = 'true|'.$total_items.'|'.$sendToCart.'|'.$total_sum.'|'.$category;// send 'true', the current number of items and the details for the added product

                            echo $data;
                       }
                    }
                }
                else {
                    echo 'Please enter a numeric value for the quantity chosen!';
                }
            }
            else {
                echo 'Please specify a quantity!';
            }
        }
        else {

            redirect('brand');
        }
    }

    private function _existsInCart($id, $category)
    {
        foreach($this->cart->contents() as $item){
            if($item['id'] === $id && $item['options']['categ_id'] === $category){ // if we find a match within the shopping cart
                return $item;
            }
        }
        return false;
    }

    private function _itemPrice($id, $category)
    {
        $item = $this->_existsInCart($id, $category);
        if($item){
            return $item['price'];
        }
        
    }

    public function delete($rowid)
    {
        /*$avail_qty = $this->cart_model->getAvailableQty($id);
        $contents = $this->cart->contents();
        foreach($contents as $item){
          if($item['id'] === $id){
            if($avail_qty[0]->available_qty == $item['qty']){
                
                $update_data = array(
                                   'rowid' => $item['rowid'],
                                   'qty' => $avail_qty[0]->available_qty-1,
                                   'options' => array(
                                                        'current_qty' => 0
                                                     )
                                );
                echo '<pre>';
                var_dump($update_data);
                die();
                $this->cart->update($update_data);
                
            }*/
          
            $update_data = array(
                                   'rowid' => $rowid,
                                   'qty' => 0,
                                   'options' => array(
                                                        'current_qty' => 'empty'
                                                     )
                                );
               
           $this->cart->update($update_data); // remove the item from the cart

           $this->session->set_flashdata('delete_item', 'Product deleted');
                
           redirect('cart/view');
        
            
          //}
       // }
    }

    // empty the cart
    public function empty_cart()
    {
        $this->cart->destroy();
        redirect('cart/view');
    }

    // make an order
    public function order()
    {
        $this->auth->is_logged_in(); // if the user is not logged in, redirect him to the login page

        if ($this->config->item('admin_level') == $this->session->userdata('user_level')) :
        // if the user is the admin, redirect him to the list of orders made by all the users
		redirect('admin/orders', 'location');

	endif;

        if(!$this->cart->contents()){ // if the cart is empty, redirect the user to the 'view cart' page
                                      // where the message: "Your cart is empty!" is displayed
             $this->session->set_flashdata('empty_cart', 'Please, add products to your cart before making an order!');
             redirect('cart/view', 'location');
        }
        else {
            // if the user is logged in and has items in the cart,
            // allow him to add his delivery address and send the order
            
            $user_id = $this->session->userdata('user_id'); // get the user's id from the session

            // breadcrumbs section
            $breadc = array('Order products');
            $data['breadcrumbs'] = $breadc;

            $initial_address = $this->user_model->getUserAddress($user_id); // get the user's initial address
                                                                            // to display it by default in the delivery section
            // and set validation rules for the address (to be required)
            // validate data
            $this->form_validation->set_rules('delivery_address', 'Delivery Address', 'required|trim|max_length[400]|xss_clean');

            // run validation
	    if ($this->form_validation->run() == FALSE && !$this->form_validation->error_exists('delivery_address')) {
               // if the page is displayed for the first time and therefore there are no validation errors
               $data['initial_address'] = $initial_address[0]->address;
               $data['content'] = 'users/delivery_details';
	       $this->load->view('template_view', $data);

            }
            else if($this->form_validation->run() == FALSE && $this->form_validation->error_exists('delivery_address')){
               // if the data doesn't get sent (isn't valid), display the login page (with the error messages)
               $data['content'] = 'users/delivery_details';
               $this->load->view('template_view', $data);
            }
            else {
                    // if the validation takes place and the data is sent
                    $address = $this->input->post('delivery_address'); // take the posted address

                    // update the user's address with the new address
                    $this->user_model->updateAddress($user_id, $address);

                    // insert the order into the orders table
                    $this->user_model->insertOrder($user_id);

                    // get the last order id inserted
                    $order_id = $this->order_model->lastId();

                    // go through all the cart items, take their categ_id option,
                    // check to see if the category with the id equal to this categ_id
                    // contains machine parts or universal products
                    // and depending on this, insert the item's id and the order id
                    // in the order_part table or in the order_universal
                    foreach($this->cart->contents() as $item){

                        $product_id = $item['id'];
                        $categ_id = $item['options']['categ_id'];
                        $qty_bought = $item['qty'];
                        $item_price = $item['price'];

                        // check if the item's category contains machine parts or universal products
                        if($this->category_model->is_part($categ_id)){

                            // insert a row in the order_part table
                            $this->order_model->insertOrderPart($order_id, $product_id, $qty_bought, $item_price);
                        }
                        else if($this->category_model->is_universal($categ_id)){

                            // insert a row in the order_universal table
                            $this->order_model->insertOrderUniversal($order_id, $product_id, $qty_bought, $item_price);
                        }
                    }

                    // update the order's total_price
                    $total_sum = $this->cart->total();
                    $this->order_model->updateOrderTotal($order_id, $total_sum);

                    // when the user has made an order, destroy the cart and redirect him to the
                    // list of orders he has done so far
                    $this->cart->destroy();
                    redirect('user/orders');
		}
         }
    }

    public function insert()
    {
        $data = array(
                       /* array(
                            'first_name' => 'Peter',
                            'last_name' => 'Pettigrew',
                            'email' => 'peter.p@gmail.com',
                            'phone' => '336898',
                            'password' => sha1('d^*aN|iPPo$@**nS%20zeCCe12345'),
                            'user_level' => 1
                        ),*/
                        //array(
                            'first_name' => 'Emi',
                            'last_name' => 'Berbec',
                            'email' => 'berbec.emanuel@gmail.com',
                            'phone' => '6693124',
                            'password' => sha1('d^*aN|iPPo$@**nS%20zeCCeadmin'),
                            'user_level' => 70
                        //)
                     );

        $this->db->update('users', $data, "user_id = 2");

        //$this->db->insert('users', $data);
    }
}
