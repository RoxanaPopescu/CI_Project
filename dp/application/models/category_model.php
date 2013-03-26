<?php
/* This model handles all the database requests that involve mainly,
 * the categories table and its relations */
class Category_model extends CI_Model{
    public function  __construct() {
        parent::__construct();
    }

    /* get the categories from the database that are part (or not) from machine parts
     (fx: categories not containing machine parts, such as car oil etc.) */
    public function getAllCategories($is_part)
    {
        $this->db->select('
                            categ_name,
                            categ_id
                         ')
                 ->from('categories')
                 ->where('is_part', $is_part);

        $query = $this->db->get();

        return $query->result();
    }

    /* check if the category with category id = $id, exists in the database */
    public function category_exists($id)
    {
        $this->db->select('categ_id')
                 ->from('categories')
                 ->where('categ_id', $id);

        $query = $this->db->get();

        if($query->num_rows > 0){
            return true;
        }

        return false;
    }

    /* get the category name for the category with id = $id */
    public function getCategoryName($id)
    {
        $this->db->select('categ_name')
                 ->from('categories')
                 ->where('categ_id', $id);

        $query = $this->db->get();

        return $query->result();
    }

    /* get the name of the category and the name of the product(s)/sub-category
     that has the id = $id */
    public function getSubcategCategoryName($id)
    {
        $this->db->select('categ_name, pn_name, categories.categ_id')
                 ->from('categories')
                 ->join('product_names', 'categories.categ_id = product_names.categ_id')
                 ->where('pn_id', $id);

        $query = $this->db->get();

        return $query->result();
    }

    /* check if the product name with the id = $id, exists in the database */
    public function subcategory_exists($id)
    {
        $this->db->select('pn_id')
                 ->from('product_names')
                 ->where('pn_id', $id);

        $query = $this->db->get();

        if($query->num_rows > 0){
            return true;
        }

        return false;
    }

    /* check if the category with the id = $id contains machine parts */
    public function is_part($id)
    {
        $this->db->select('is_part')
                 ->from('categories')
                 ->where('categ_id', $id)
                 ->where('is_part', 1);

        $query = $this->db->get();

        if($query->num_rows() > 0)
        {
            return true;
        }

        return false;
    }

    /* check if the category with the id = $id contains universal products */
    public function is_universal($id)
    {
        $this->db->select('is_part')
                 ->from('categories')
                 ->where('categ_id', $id)
                 ->where('is_part', 0);

        $query = $this->db->get();

        if($query->num_rows() > 0)
        {
            return true;
        }

        return false;
    }

    /* get all the products from the category with the id = $id, for all the car types from the database */
    public function allFromCategory($id)
    {
        $this->db->select('
                            categ_name,
                            categories.categ_id as categ_id,
                            pn_name,
                            pd_desc,
                            producer_name,
                            final_id,
                            product_image,
                            product_price,
                            product_status,
                            available_qty,
                            type_name,
                            car_types.ctype_id as ctype_id,
                            model_name,
                            car_models.cmodel_id as cmodel_id,
                            brand_name,
                            car_brands.cbrand_id as cbrand_id
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
                 ->where('categories.categ_id', $id)
                 ->order_by('pn_name asc, brand_name asc');

        $query = $this->db->get();

        return $query->result();
    }

    /* get all the universal products from the category with the id = $category_id */
    public function universalFromCategory($id)
    {
        $this->db->select('
                            categ_name,
                            categories.categ_id as categ_id,
                            pn_name,
                            pd_desc,
                            producer_name,
                            univ_id,
                            product_image,
                            product_price,
                            product_status,
                            available_qty
                         ')
                 ->from('categories')
                 ->join('product_names', 'categories.categ_id = product_names.categ_id')
                 ->join('name_desc', 'product_names.pn_id = name_desc.pn_id')
                 ->join('product_descriptions', 'name_desc.pd_id = product_descriptions.pd_id')
                 ->join('prodr_name_desc', 'name_desc.product_code = prodr_name_desc.product_code')
                 ->join('producers', 'prodr_name_desc.producer_id = producers.producer_id')
                 ->join('universal_products', 'prodr_name_desc.pp_code = universal_products.pp_code')
                 ->where('categories.categ_id', $id)
                 ->order_by('pn_name asc, producer_name asc');

        $query = $this->db->get();

        return $query->result();
    }

    public function getUnivCateg()
    {
        $this->db->select('categ_id, categ_name')
                 ->from('categories')
                 ->where('is_part', 0);

        $query = $this->db->get();

        return $query->result();

    }

    public function getProductDetails($pn_id)
    {
        $this->db->select('
                            details.detail_id as detail_id,
                            detail_name
                          ')
                 ->from('details')
                 ->join('pn_details', 'details.detail_id = pn_details.detail_id')
                 ->where('pn_id', $pn_id)
                 ->order_by('detail_name asc');

        $query = $this->db->get();

        return $query->result();

    }
}