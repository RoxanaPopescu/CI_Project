<?php

class Order_model extends CI_Model{
    public function  __construct() {
        parent::__construct();
    }

    /* get the last id inserted */
    public function lastId()
    {
        return $this->db->insert_id();
    }

    /* insert a row in order_part table */
    public function insertOrderPart($order_id, $product_id, $qty_bought, $item_price)
    {
        $data = array(
                        'order_id' => $order_id,
                        'final_id' => $product_id,
                        'qty_bought' => $qty_bought,
                        'item_price' => $item_price,
                        'total_price' => $item_price * $qty_bought
                     );

        $this->db->insert('order_part', $data);
    }

    /* insert a row in order_universal table */
    public function insertOrderUniversal($order_id, $product_id, $qty_bought, $item_price)
    {
        $data = array(
                        'order_id' => $order_id,
                        'univ_id' => $product_id,
                        'qty_bought' => $qty_bought,
                        'item_price' => $item_price,
                        'total_price' => $item_price * $qty_bought
                     );

        $this->db->insert('order_universal', $data);
    }

    /* update the total_price field in the orders table */
    public function updateOrderTotal($order_id, $total_sum)
    {
        $data = array(
                       'total_price' => $total_sum
                     );

        $this->db->where('order_id', $order_id);
        $this->db->update('orders', $data);
    }

    /* get the products (machine parts) which correspond to the order with the id = $order_id */
    public function getOrderProducts($order_id)
    {
        $this->db->select('
                            orders.order_id,
                            qty_bought,
                            item_price,
                            order_part.total_price as subtotal,
                            type_name,
                            model_name,
                            brand_name,
                            producer_name,
                            pn_name
                          ')
                 ->from('orders')
                 ->join('order_part', 'orders.order_id = order_part.order_id')
                 ->join('ct_p_n_d', 'order_part.final_id = ct_p_n_d.final_id')
                 ->join('car_types', 'ct_p_n_d.ctype_id = car_types.ctype_id')
                 ->join('car_models', 'car_types.cmodel_id = car_models.cmodel_id')
                 ->join('car_brands', 'car_models.cbrand_id = car_brands.cbrand_id')
                 ->join('prodr_name_desc', 'ct_p_n_d.pp_code = prodr_name_desc.pp_code')
                 ->join('producers', 'prodr_name_desc.producer_id = producers.producer_id')
                 ->join('name_desc', 'prodr_name_desc.product_code = name_desc.product_code')
                 ->join('product_names', 'name_desc.pn_id = product_names.pn_id')
                 ->where('orders.order_id', $order_id);

        $query = $this->db->get();

        return $query->result();
    }

    /* get the products (universal products) which correspond to the order with the id = $order_id */
    public function getOrderUniversals($order_id)
    {
        $this->db->select('
                            orders.order_id,
                            qty_bought,
                            item_price,
                            order_universal.total_price as subtotal,
                            producer_name,
                            pn_name
                          ')
                 ->from('orders')
                 ->join('order_universal', 'orders.order_id = order_universal.order_id')
                 ->join('universal_products', 'order_universal.univ_id = universal_products.univ_id')
                 ->join('prodr_name_desc', 'universal_products.pp_code = prodr_name_desc.pp_code')
                 ->join('producers', 'prodr_name_desc.producer_id = producers.producer_id')
                 ->join('name_desc', 'prodr_name_desc.product_code = name_desc.product_code')
                 ->join('product_names', 'name_desc.pn_id = product_names.pn_id')
                 ->where('orders.order_id', $order_id);

        $query = $this->db->get();

        return $query->result();
    }

    /* check if the order with the id = $order_id exists in the database */
    public function order_exists($order_id)
    {
        $this->db->select('order_id')
                 ->from('orders')
                 ->where('order_id', $order_id);

        $query = $this->db->get();

        if($query->num_rows() > 0){
            return true;
        }

        return false;
    }

    /* get all the orders from the database in a date-descending order*/
    public function getAllOrders()
    {
        $this->db->select('
                            first_name,
                            last_name,
                            email,
                            orders.order_id as order_id,
                            total_price,
                            date
                         ')
                 ->from('users')
                 ->join('orders', 'users.user_id = orders.user_id')
                 ->order_by('date', 'desc');

        $query = $this->db->get();

        return $query->result();
    }
}