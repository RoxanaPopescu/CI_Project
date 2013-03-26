<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Brand extends CI_Controller {
    private $breadcrumbs;
    
    public function  __construct() {
        parent::__construct();
        $this->load->model('brand_model'); //load the models used throughout the class
        $this->load->model('cartype_model');
        $this->breadcrumbs = array('Home', 'brand'); //initialize the $breadcrumbs array, so that
                                            //it starts with 'Home'
    }

    public function index()
    {
        $this->home(); //when the base URL is requested, send the user to '/home/'
    }

    //display all car brands if they exist
    public function home()
    {
        $recent_products = $this->cartype_model->getRecentProducts(); //get all the car brands from the database
        
        if($recent_products != NULL){ // if there are products in the database
            $data['recent_products'] = $recent_products;

            $data['content'] = 'template_content/home_content_view'; //get the view that creates the content of
                                                                //the homepage
            $data['breadcrumbs'] = array('Home'); //retain the $breadcrumbs array
        }
        else{
            $data['missing_message'] = 'Sorry! Currently there are no products available.';
            $data['breadcrumbs'] = $this->breadcrumbs;
            $data['content'] = 'template_content/missing_view';
        }

        $this->load->view('template_view', $data); //send all the data to the main template
    }

    //display all car models for a specific car brand
    //(this function executes according to the routing criteria written in routes.php)
    public function models()
    {
        if($this->uri->segment(2,0) == 'models'){
            redirect('brand');// avoid accessing the models URI in the URL
        }
        $brand_id = (int)$this->uri->segment(2,0); //grab the brand's id from the URL
                                                   //and sanitize it
        if($brand_id != 0){ //if this brand id exists in the URL

            if($this->brand_model->brand_exists($brand_id)){ //if this brand stated in the URL, exists in
                                                             //the database
                // display the breadcrumbs only if the car brand exists in the database
                $brand = $this->brand_model->getBrandName($brand_id);//get the brand's name from
                                                                     //the database
                $this->breadcrumbs[] = $brand[0]->brand_name;//add the brand's name to the $breadcrumbs array
                $this->breadcrumbs[] = 'brand/'.$brand_id;
                $data['breadcrumbs'] = $this->breadcrumbs;

                $models = $this->brand_model->getBrandModels($brand_id);//get the brand's car models from the database
                if($models != NULL){ //if there are car models in the database, for this brand,
                                     //create a table to display them
                    $this->table->set_heading(
                                              'Producing Brand',
                                              'Model',
                                              'Made between'
                                             );// the table's heading

                    foreach($models as $model){
                        $this->table->add_row(
                                              $model['brand'],
                                              $model['model'],
                                              $model['period']
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

                    $data['table_models'] = $this->table->generate();//generate the table

                    $data['content'] = 'template_content/models_content_view';//send everything to the view
                                                                              //handling the car models for a brand

                }
                else{ //if the brand exists in the database, but it doesn't contain any car models
                     $data['missing_message'] = 'Sorry! Currently, no models can be found for this brand.';
                     $data['content'] = 'template_content/missing_view';
                }
            }
            else { //if the brand doesn't exist in the database
                $data['missing_message'] = 'Currently, this car brand cannot be found.';
                $data['breadcrumbs'] = $this->breadcrumbs;
                $data['content'] = 'template_content/missing_view';
            }
            
            $this->load->view('template_view', $data);//send everything to the main view
        }
        /*else {
            redirect('brand');
        }*/
        
    }
}