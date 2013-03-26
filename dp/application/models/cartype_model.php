<?php
/* This model handles all the database requests that involve mainly,
 * the car_types table and its relations */
class Cartype_model extends CI_Model{
    public function  __construct() {
        parent::__construct();
    }

    /* get all the categories of products that exist in the database for the car type with id = $id */
    public function getTypeCategories($id)
    {
        $this->db->select('categories.categ_id, categ_name')
                 ->from('categories')
                 ->join('product_names', 'categories.categ_id = product_names.categ_id')
                 ->join('name_desc', 'product_names.pn_id = name_desc.pn_id')
                 ->join('prodr_name_desc', 'name_desc.product_code = prodr_name_desc.product_code')
                 ->join('ct_p_n_d', 'prodr_name_desc.pp_code = ct_p_n_d.pp_code')
                 ->where('ct_p_n_d.ctype_id', $id)
                 ->group_by('categories.categ_id')
                 ->order_by('categories.categ_name', 'asc');

        $query = $this->db->get();

        return $query->result();
    }

    /* get the brand name, the car model name and the car type name for car type with id = $id */
    public function getBrandModelTypeName($id)
    {
        $this->db->select('brand_name, model_name, type_name, car_brands.cbrand_id, car_models.cmodel_id')
                 ->from('car_brands')
                 ->join('car_models', 'car_brands.cbrand_id = car_models.cbrand_id')
                 ->join('car_types', 'car_models.cmodel_id = car_types.cmodel_id')
                 ->where('car_types.ctype_id', $id);

        $query = $this->db->get();

        return $query->result();
    }

    /* check if the car type with the id = $id, exists in the database */
    public function type_exists($id)
    {
        $this->db->select('ctype_id')
                 ->from('car_types')
                 ->where('ctype_id', $id);

        $query = $this->db->get();

        if($query->num_rows() > 0 ){
            return true;
        }

        return false;
    }

    /* get all the products from a category with the id = $categ_id, which belongs to the
      car type with the id = $type_id and sort the products in alphabetical order */
    public function getCategoryProducts($type_id, $categ_id)
    {
        $this->db->select('categories.categ_id, product_names.pn_id, pn_name')
                 ->from('categories')
                 ->join('product_names', 'categories.categ_id = product_names.categ_id')
                 ->join('name_desc', 'product_names.pn_id = name_desc.pn_id')
                 ->join('prodr_name_desc', 'name_desc.product_code = prodr_name_desc.product_code')
                 ->join('ct_p_n_d', 'prodr_name_desc.pp_code = ct_p_n_d.pp_code')
                 ->where('ct_p_n_d.ctype_id', $type_id)
                 ->where('categories.categ_id', $categ_id)
                 ->group_by('product_names.pn_id')
                 ->order_by('pn_name');

        $query = $this->db->get();

        return $query->result();
    }

    /* get all the products from a sub-category with the id = $subcateg_id, which belongs to the
      car type with the id = $type_id and sort the products by their producer's name (in alphabetical order)
     * (the "sub-category" is just another term for referencing the "product name" of the products)
     */
    public function getSubcategProducts($type_id, $subcateg_id)
    {
        $this->db->select('
                           categories.categ_id,
                           categ_name,
                           pn_name,
                           pd_desc,
                           producer_name,
                           product_image,
                           product_price,
                           product_status,
                           available_qty,
                           final_id
                         ')
                 ->from('categories')
                 ->join('product_names', 'categories.categ_id = product_names.categ_id')
                 ->join('name_desc', 'product_names.pn_id = name_desc.pn_id')
                 ->join('product_descriptions', 'name_desc.pd_id = product_descriptions.pd_id')
                 ->join('prodr_name_desc', 'name_desc.product_code = prodr_name_desc.product_code')
                 ->join('producers', 'prodr_name_desc.producer_id = producers.producer_id')
                 ->join('ct_p_n_d', 'prodr_name_desc.pp_code = ct_p_n_d.pp_code')
                 /*->join('car_types', 'ct_p_n_d.ctype_id = car_types.ctype_id')
                 ->join('car_models', 'car_types.cmodel_id = car_models.cmodel_id')
                 ->join('car_brands', 'car_models.cbrand_id = car_brands.cbrand_id')*/
                 ->where('ct_p_n_d.ctype_id', $type_id)
                 ->where('product_names.pn_id', $subcateg_id)
                 ->order_by('producers.producer_name asc, added_date desc');

        $query = $this->db->get();

        return $query->result();
    }

    /* get the most recently added 6 products from the database to display them on the homepage */
    public function getRecentProducts()
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
                 ->order_by('added_date desc')
                 ->limit(6);


        $query = $this->db->get();

        return $query->result();
    }

    /* get the details of the latest car type viewed by the user of the website */
    public function getLatestCar($id)
    {
        $this->db->select('
                           type_name,
                           car_types.ctype_id as ctype_id,
                           engine_code,
                           capacity_cm3,
                           kw,
                           hp,
                           cylinders,
                           fabrication_year_start,
                           fabrication_year_end,
                           adm_name,
                           body_name,
                           tr_name,
                           fuel_name,
                           model_name,
                           car_models.cmodel_id as cmodel_id,
                           brand_name,
                           car_brands.cbrand_id as cbrand_id
                         ')
                 ->from('car_types')
                 ->join('admissions', 'car_types.adm_id = admissions.adm_id')
                 ->join('bodies', 'car_types.body_id = bodies.body_id')
                 ->join('tractions', 'car_types.tr_id = tractions.tr_id')
                 ->join('fuel', 'car_types.fuel_id = fuel.fuel_id')
                 ->join('car_models', 'car_types.cmodel_id = car_models.cmodel_id')
                 ->join('car_brands', 'car_models.cbrand_id = car_brands.cbrand_id')
                 ->where('car_types.ctype_id', $id);

        $query = $this->db->get();

        return $query->result();
    }

}