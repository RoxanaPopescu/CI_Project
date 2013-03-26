<?php
/* This model handles all the database requests that involve mainly,
 * the car_models table and its relations */
class Carmodel_model extends CI_Model{

    public function  __construct() {
        parent::__construct();
    }

    /* get all the types from the database, that belong to the car model with the id = $id*/
    public function getModelTypes($id)
    {
        $this->db->select('ctype_id,
                           type_name,
                           engine_code,
                           capacity_cm3,
                           kw,
                           hp,
                           cylinders,
                           fabrication_year_start,
                           fabrication_year_end,
                           fuel_name,
                           tr_name,
                           body_name,
                           adm_name'
                        )
                ->from('car_types')
                ->join('fuel', 'car_types.fuel_id = fuel.fuel_id')
                ->join('tractions', 'car_types.tr_id = tractions.tr_id')
                ->join('bodies', 'car_types.body_id = bodies.body_id')
                ->join('admissions', 'car_types.adm_id = admissions.adm_id')
                ->where('cmodel_id', $id)
                ->order_by('car_types.type_name', 'asc');

        $query = $this->db->get();

        return $query->result();
    }

    /* get the names of the car brand and car model (for the car model with the id = $id) */
    public function getBrandModelName($id)
    {
        $this->db->select('brand_name, model_name, car_brands.cbrand_id')
                 ->from('car_brands')
                 ->join('car_models', 'car_brands.cbrand_id = car_models.cbrand_id')
                 ->where('cmodel_id', $id);

        $query = $this->db->get();

        return $query->result();
    }

    /* check if the car model with the id = $id exists in the database */
    public function model_exists($id){
        $this->db->select('cmodel_id')
                 ->from('car_models')
                 ->where('cmodel_id', $id);

        $query = $this->db->get();

        if($query->num_rows() > 0){
            return true;
        }

        return false;
    }
}