<?php

class Cart_model extends CI_Model{
    public function  __construct() {
        parent::__construct();
    }

    /* check if the product with the id = $id exists in the database */
    public function product_exists($id)
    {
        $this->db->select('final_id')
                 ->from('ct_p_n_d')
                 ->where('final_id', $id);

        $query = $this->db->get();

        if($query->num_rows() > 0){
            return true;
        }
        return false;
    }

    /* check if the universal product exists in the database */
    public function universal_exists($id)
    {
        $this->db->select('univ_id')
                 ->from('universal_products')
                 ->where('univ_id', $id);

        $query = $this->db->get();

        if($query->num_rows() > 0){
            return true;
        }
        return false;
    }

    /* get the available quantity of the product with the final id = $id */
    public function getAvailableQty($id)
    {
        $this->db->select('available_qty')
                 ->from('ct_p_n_d')
                 ->where('final_id', $id);

        $query = $this->db->get();

        return $query->result();
    }

    /* get the available quantity of the universal product with the univ_id = $id */
    public function universalAvailableQty($id)
    {
         $this->db->select('available_qty')
                 ->from('universal_products')
                 ->where('univ_id', $id);

        $query = $this->db->get();

        return $query->result();
    }

    /* get the details of the product with the final id = $id */
    public function getProduct($id)
    {
        $this->db->select('
                           pn_name,
                           product_names.pn_id as pn_id,
                           categ_name,
                           categories.categ_id as categ_id,
                           pd_desc,
                           producer_name,
                           product_image,
                           product_price,
                           product_status,
                           available_qty,
                           final_id,
                           type_name,
                           car_types.ctype_id as ctype_id,
                           model_name,
                           car_models.cmodel_id as cmodel_id,
                           brand_name,
                           car_brands.cbrand_id as cbrand_id
                         ')
                 ->from('product_names')
                 ->join('categories', 'product_names.categ_id = categories.categ_id')
                 ->join('name_desc', 'product_names.pn_id = name_desc.pn_id')
                 ->join('product_descriptions', 'name_desc.pd_id = product_descriptions.pd_id')
                 ->join('prodr_name_desc', 'name_desc.product_code = prodr_name_desc.product_code')
                 ->join('producers', 'prodr_name_desc.producer_id = producers.producer_id')
                 ->join('ct_p_n_d', 'prodr_name_desc.pp_code = ct_p_n_d.pp_code')
                 ->join('car_types', 'ct_p_n_d.ctype_id = car_types.ctype_id')
                 ->join('car_models', 'car_types.cmodel_id = car_models.cmodel_id')
                 ->join('car_brands', 'car_models.cbrand_id = car_brands.cbrand_id')
                 ->where('final_id', $id);

        $query = $this->db->get();

        return $query->result();

    }

    /* get the details of the universal product with the  univ_id = $id */
    public function getUniversal($id)
    {
        $this->db->select('
                           pn_name,
                           categ_name,
                           categories.categ_id as categ_id,
                           pd_desc,
                           producer_name,
                           product_image,
                           product_price,
                           product_status,
                           available_qty,
                           univ_id
                         ')
                 ->from('product_names')
                 ->join('categories', 'product_names.categ_id = categories.categ_id')
                 ->join('name_desc', 'product_names.pn_id = name_desc.pn_id')
                 ->join('product_descriptions', 'name_desc.pd_id = product_descriptions.pd_id')
                 ->join('prodr_name_desc', 'name_desc.product_code = prodr_name_desc.product_code')
                 ->join('producers', 'prodr_name_desc.producer_id = producers.producer_id')
                 ->join('universal_products', 'prodr_name_desc.pp_code = universal_products.pp_code')
                 ->where('univ_id', $id);

        $query = $this->db->get();

        return $query->result();

    }

    /* retrieve machine parts after user search */
    public function searchProducts()
    {
        $this->db->select('
                           categ_name,
                           categories.categ_id,
                           is_part,
                           pn_name,
                           pd_desc,
                           producer_name,
                           product_image,
                           product_price,
                           product_status,
                           available_qty,
                           type_name,
                           car_types.ctype_id,
                           model_name,
                           car_models.cmodel_id,
                           brand_name,
                           car_brands.cbrand_id,
                           final_id
                         ')
                 ->from('categories')
                 ->join('product_names', 'categories.categ_id = product_names.categ_id')
                 ->join('name_desc', 'product_names.pn_id = name_desc.pn_id')
                 ->join('product_descriptions', 'name_desc.pd_id = product_descriptions.pd_id')
                 ->join('prodr_name_desc', 'name_desc.product_code = prodr_name_desc.product_code')
                 ->join('producers', 'prodr_name_desc.producer_id = producers.producer_id')
                 ->join('ct_p_n_d', 'prodr_name_desc.pp_code = ct_p_n_d.pp_code')
                 ->join('car_types', 'ct_p_n_d.ctype_id = car_types.ctype_id')
                 ->join('car_models', 'car_types.cmodel_id = car_models.cmodel_id')
                 ->join('car_brands', 'car_models.cbrand_id = car_brands.cbrand_id')
                 ->order_by('added_date desc');


        $query = $this->db->get();

        return $query->result();
    }

    /* retrieve universal products after user search */
    public function searchUniversal()
    {
        $this->db->select('
                           categ_name,
                           categories.categ_id,
                           is_part,
                           pn_name,
                           pd_desc,
                           producer_name,
                           product_image,
                           product_price,
                           product_status,
                           available_qty,
                           univ_id
                         ')
                 ->from('categories')
                 ->join('product_names', 'categories.categ_id = product_names.categ_id')
                 ->join('name_desc', 'product_names.pn_id = name_desc.pn_id')
                 ->join('product_descriptions', 'name_desc.pd_id = product_descriptions.pd_id')
                 ->join('prodr_name_desc', 'name_desc.product_code = prodr_name_desc.product_code')
                 ->join('producers', 'prodr_name_desc.producer_id = producers.producer_id')
                 ->join('universal_products', 'prodr_name_desc.pp_code = universal_products.pp_code')
                 ->order_by('added_date desc');


        $query = $this->db->get();

        return $query->result();
    }

    public function desc_exists($final_description)
    {
        $this->db->select('pd_id')
                 ->from('product_descriptions')
                 ->where('pd_desc', $final_description);

        $query = $this->db->get();

        if($query->num_rows > 0){
            $data = $query->result();

            return $data[0]->pd_id;
        }
        return false;
    }

    public function insertDesc($final_description)
    {
        $data = array(
                       'pd_desc' => $final_description
                     );

        $this->db->insert('product_descriptions', $data);
    }

    public function ndRow_exists($pn_id, $desc_id)
    {
        $this->db->select('product_code')
                 ->from('name_desc')
                 ->where('pn_id', $pn_id)
                 ->where('pd_id', $desc_id);

        $query = $this->db->get();

        if($query->num_rows > 0){
            $data = $query->result();

            return $data[0]->product_code;
        }
        return false;
    }

    public function insertNdRow($pn_id, $desc_id)
    {
        $data = array(
                       'pn_id' => $pn_id,
                       'pd_id' => $desc_id
                     );

        $this->db->insert('name_desc', $data);
    }

    public function getProducerId($producer_name)
    {
        $this->db->select('producer_id')
                 ->from('producers')
                 ->where('producer_name', $producer_name);

        $query = $this->db->get();

        $data = $query->result();

        return $data[0]->producer_id;
    }

    public function pndRow_exists($producer_id, $product_code)
    {
        $this->db->select('pp_code')
                 ->from('prodr_name_desc')
                 ->where('producer_id', $producer_id)
                 ->where('product_code', $product_code);

        $query = $this->db->get();

        if($query->num_rows > 0){
            $data = $query->result();

            return $data[0]->pp_code;
        }
        return false;
    }

    public function insertPndRow($producer_id, $product_code)
    {
        $data = array(
                       'producer_id' => $producer_id,
                       'product_code' => $product_code
                     );

        $this->db->insert('prodr_name_desc', $data);
    }

    public function updateDesc($final_id, $pp_code)
    {
        $data = array(
                        'pp_code' => $pp_code
                     );

        $this->db->where('final_id', $final_id);
        $this->db->update('ct_p_n_d', $data);
    }
}