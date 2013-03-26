<?php
/* This model handles all the database requests that involve mainly,
 * the car_brands table and its relations */
class Brand_model extends CI_Model{

    public function  __construct() {
        parent::__construct();
    }

    /* get all the car brands from the database, in the alphabetic order */
    public function getAllBrands()
    {
        $this->db->select('cbrand_id, brand_name')
                ->from('car_brands')
                ->order_by('brand_name', 'asc');

        $query = $this->db->get();

        return $query->result();
    }

    /* get all the car models in the alphabetic order, for the car brand that has the id = $id */
    public function getBrandModels($id)
    {
       $this->db->select('brand_name, car_models.cmodel_id as id, model_name')
                ->select_min('fabrication_year_start')
                ->select_max('fabrication_year_end')
                ->from('car_brands')
                ->join('car_models', 'car_brands.cbrand_id = car_models.cbrand_id')
                ->join('car_types', 'car_models.cmodel_id = car_types.cmodel_id')
                ->where('car_models.cbrand_id', $id)
                ->group_by('car_models.cmodel_id')
                ->order_by('model_name', 'asc');

        $query = $this->db->get();

        $models = array();
        foreach($query->result() as $row){
            //create a multidimensional array and fill it with each row's brand, model and period of time
            //within which the model has been manufactured
            $models[] = array(
                            //'id' => $row->id,
                            'brand' => $row->brand_name,
                            'model' => anchor('car_model/'.$row->id, $row->model_name),
                            'period' => $row->fabrication_year_start.'-'.$row->fabrication_year_end
                        );
        }

        return $models;

    }

    /* get the name of a car brand with the id = $id */
    public function getBrandName($id)
    {
        $this->db->select('brand_name')
                 ->from('car_brands')
                 ->where('cbrand_id', $id);

        $query = $this->db->get();

        return $query->result();
    }

    /* check if a certain brand exists in the database */
    public function brand_exists($id)
    {
        $this->db->select('cbrand_id')
                 ->from('car_brands')
                 ->where('cbrand_id', $id);

        $query = $this->db->get();

        if($query->num_rows() > 0){
            return true;
        }

        return false;
    }
}
