<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Car_model extends CI_Controller {
    private $breadcrumbs;
    
    public function  __construct() {
        parent::__construct();
        $this->load->model('carmodel_model');//load the model used throughout the class
        $this->breadcrumbs = array('Home', 'brand');//initialize the $breadcrumbs array, so that
                                           //it starts with 'Home'
    }

    public function index()
    {
        redirect('brand');//when the '/car_model/' URI is requested, redirect the user to '/brand/'
    }

    //display all the car types for a specific car model
    //(this function executes according to the routing criteria written in routes.php)
    public function types()
    {
        if($this->uri->segment(2,0) == 'types'){
            redirect ('brand');// avoid accessing the types URI in the URL
        }

        $model_id = (int)$this->uri->segment(2,0);//grab the model's id from the URL
                                                  //and sanitize it

        if($model_id != 0){ //if this model id exists in the URL
            if($this->carmodel_model->model_exists($model_id)){ //if this car model stated in the URL, exists in
                                                                //the database
                // display the breadcrumbs only if the car model exists in the database
                $brand_model = $this->carmodel_model->getBrandModelName($model_id);//get the model's name
                                                                                   //and the model's brand name
                                                                                   //from the database
                //retain the brand name and the model name as elements of the
                //$breadcrumbs array
                array_push($this->breadcrumbs,
                           $brand_model[0]->brand_name,
                           'brand/'.$brand_model[0]->cbrand_id,
                           $brand_model[0]->model_name,
                           'car_model/'.$model_id
                          );

                $data['breadcrumbs'] = $this->breadcrumbs;

                $types = $this->carmodel_model->getModelTypes($model_id);//get the car types from
                                                                         //the database, for the car model
                if($types != NULL){ //if there are car types in the database, for this car model
                                    //create a table to display them
                    $this->table->set_heading(
                                              'Car Type',
                                              'Made between',
                                              'kW',
                                              'HP',
                                              'Capacity cm<sup>3</sup>',
                                              'Engine Code',
                                              'Fuel'
                                             );//the table's heading

                    foreach($types as $type){
                        $this->table->add_row(
                                               anchor('car_type/'.$type->ctype_id, $type->type_name),
                                               $type->fabrication_year_start.'-'.$type->fabrication_year_end,
                                               $type->kw,
                                               $type->hp,
                                               $type->capacity_cm3,
                                               $type->engine_code,
                                               $type->fuel_name
                                             );//the table's rows
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

                    $data['table_types'] = $this->table->generate();//generate the table
                
                    $data['content'] = 'template_content/types_content_view';//send everything to the view
                                                                             //handling the car types for a car model
                }
                else { //if the car model exists in the database, but it doesn't contain any car types
                    $data['missing_message'] = 'Sorry! Currently, no types can be found for this model.';
                    $data['content'] = 'template_content/missing_view';
                }
            }
            else { //if the car model doesn't exist in the database
                $data['missing_message'] = 'Curently, this car model cannot be found.';
                $data['breadcrumbs'] = $this->breadcrumbs; // maintain the layout
                $data['content'] = 'template_content/missing_view';
            }

            $this->load->view('template_view', $data);//send everything to the main view
        }
        /*else {
            redirect('brand'); //this functionality is already accomplished by the index() function above
        }*/
    }
}