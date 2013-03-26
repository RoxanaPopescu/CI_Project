<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('sidebar_brands')) // make sure the file cannot be included and ran from outside the CodeIgniter scope
{
    // Getting all the brands existing in the database and put them in the upper side of the sidebar
    function sidebar_brands()
    {
        $CI = & get_instance(); // get the instance of Codeigniter

        // get the data from the brand_model
        $CI->load->model('brand_model');
        $sidebarBrands = $CI->brand_model->getAllBrands();
        return $sidebarBrands;
    }

}

if ( ! function_exists('sidebar_car')) // make sure the file cannot be included and ran from outside the CodeIgniter scope
{
    // Getting the details of the last car type visited on the website
    function sidebar_car()
    {
        $CI = & get_instance(); // get the instance of Codeigniter

        // get the car type id from the session
        $car_type_id = $CI->session->userdata('car_type_id');

        // only if the session data exists, load the cartype model and get the car's details
        if($car_type_id != NULL)
        {
            $CI->load->model('cartype_model');
            $car_details = $CI->cartype_model->getLatestCar($car_type_id);

            //$CI->table->set_caption('Latest car viewed');

            // row 1:
            $cell1 = array(
                           'data' => 'Car:',
                           'class' => 'left_cell_car'
                          );
            $cell2 = array(
                            'data' => anchor('brand/'.$car_details[0]->cbrand_id, $car_details[0]->brand_name).
                                      '<br />'.
                                      anchor('car_model/'.$car_details[0]->cmodel_id, $car_details[0]->model_name).
                                      '<br />'.
                                      anchor('car_type/'.$car_details[0]->ctype_id, $car_details[0]->type_name),
                             'class' => 'right_cell_car'
                          );
            
            $CI->table->add_row($cell1, $cell2);

            // row 2:
            $cell1 = array(
                           'data' => 'Made between:',
                           'class' => 'left_cell_car'
                          );
            $cell2 = array(
                            'data' => $car_details[0]->fabrication_year_start.' - '.$car_details[0]->fabrication_year_end,
                            'class' => 'right_cell_car'
                           );
            
            $CI->table->add_row($cell1, $cell2);

            // row 3:
            $cell1 = array(
                          'data' => 'Body:',
                          'class' => 'left_cell_car'
                         );
            $cell2 = array(
                          'data' => $car_details[0]->body_name,
                          'class' => 'right_cell_car'
                         );
            
            $CI->table->add_row($cell1, $cell2);

            // row 4:
            $cell1 = array(
                          'data' => 'Fuel:',
                          'class' => 'left_cell_car'
                         );
            $cell2 = array(
                          'data' => $car_details[0]->fuel_name,
                          'class' => 'right_cell_car'
                         );
            
            $CI->table->add_row($cell1, $cell2);

            // $row 5:
            $cell1 = array(
                           'data' => 'Engine Code:',
                           'class' => 'left_cell_car'
                          );
            $cell2 = array(
                           'data' => $car_details[0]->engine_code,
                           'class' => 'right_cell_car'
                          );

            $CI->table->add_row($cell1, $cell2);

            // row 6:
            $cell1 = array(
                           'data' => 'Capacity cm<sup>3</sup>:',
                           'class' => 'left_cell_car'
                          );
             $cell2 = array(
                           'data' => $car_details[0]->capacity_cm3,
                           'class' => 'right_cell_car'
                          );
             
            $CI->table->add_row($cell1, $cell2);

            // row 7:
            $cell1 = array(
                          'data' => 'kW:',
                          'class' => 'left_cell_car'
                         );
            $cell2 = array(
                          'data' => $car_details[0]->kw,
                          'class' => 'right_cell_car'
                         );

            $CI->table->add_row($cell1, $cell2);

            // row 8:
            $cell1 = array(
                           'data' => 'HP:',
                           'class' => 'left_cell_car'
                          );
            $cell2 = array(
                           'data' => $car_details[0]->hp,
                           'class' => 'right_cell_car'
                          );

            $CI->table->add_row($cell1, $cell2);

            // row 9:
            $cell1 = array(
                           'data' => 'Cylinders:',
                           'class' => 'left_cell_car'
                          );
            $cell2 = array(
                           'data' => $car_details[0]->cylinders,
                           'class' => 'right_cell_car'
                          );
            
            $CI->table->add_row($cell1, $cell2);

            // row 10:
            $cell1 = array(
                           'data' => 'Admission:',
                           'class' => 'left_cell_car'
                          );
            $cell2 = array(
                           'data' => $car_details[0]->adm_name,
                           'class' => 'right_cell_car'
                          );
            
            $CI->table->add_row($cell1, $cell2);

            // row 11:
            $cell1 = array(
                          'data' => 'Traction:',
                          'class' => 'left_cell_car'
                          );
            $cell2 = array(
                           'data' => $car_details[0]->tr_name,
                           'class' => 'right_cell_car'
                          );
            $CI->table->add_row($cell1, $cell2);

            $tmpl = array ( 'table_open'  => '<table rules="all">' );
            $CI->table->set_template($tmpl);

            $table_car = $CI->table->generate();

            return $table_car;
        }
    }

    if ( ! function_exists('sidebar_categories')) // make sure the file cannot be included and ran from outside the CodeIgniter scope
    {
        // get all the categories available in the database
        function sidebar_categories($is_part)
        {
            $CI = & get_instance(); // get the instance of Codeigniter

            $CI->load->model('category_model');
            // in the sidebar, dispay only the categories containing machine parts
            if($is_part == 1){
                $categories = $CI->category_model->getAllCategories($is_part);
                return $categories;
            }
        }
    }

}
