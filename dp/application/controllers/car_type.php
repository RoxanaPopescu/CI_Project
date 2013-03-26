<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Car_type extends CI_Controller {
    private $breadcrumbs;

    public function  __construct() {
        parent::__construct();
        $this->load->model('cartype_model');//load the models used throughout the class
        $this->load->model('category_model');
        $this->breadcrumbs = array('Home', 'brand');//initialize the $breadcrumbs array, so that
                                           //it starts with 'Home'
    }

    public function index()
    {
        redirect('brand');//when the '/car_type/' URI is requested, redirect the user to '/brand/'
    }

    //display all the categories of products for a specific car model
    //(this function executes according to the routing criteria written in routes.php)
    public function categories()
    {
        if($this->uri->segment(2,0) == 'categories'){
            redirect('brand'); // avoid accessing the categories URI in the URL
        }

        $type_id = (int)$this->uri->segment(2,0);//grab the car type's id from the URL
                                                 //and sanitize it

        if($type_id != 0){ //if this type id exists in the URL

            if($this->cartype_model->type_exists($type_id)){ //if this car type stated in the URL, exists in
                                                             //the database

                $this->session->set_userdata('car_type_id', $type_id); // each time '/car_type/id' URI is accessed, retain the car type's id

                //display the breadcrumbs only if the car type exists in the database
                $brand_model_type = $this->cartype_model->getBrandModelTypeName($type_id);//get the brand name,
                                                                                          //the model name and
                                                                                          //the type name from
                                                                                         //the database
                //retain the brand name, the model name and the type name as elements of the
                //$breadcrumbs array
                array_push($this->breadcrumbs,
                           $brand_model_type[0]->brand_name,
                           'brand/'.$brand_model_type[0]->cbrand_id,
                           $brand_model_type[0]->model_name,
                           'car_model/'.$brand_model_type[0]->cmodel_id,
                           $brand_model_type[0]->type_name,
                           'car_type/'.$type_id
                          );

                $data['breadcrumbs'] = $this->breadcrumbs;
                
                $categories = $this->cartype_model->getTypeCategories($type_id);//get the categories of products from
                                                                                //the database, for the car type

                if($categories != NULL){ //if there are products and categories in the database,
                                         //for this car type
                    $data['categories'] = $categories;
                    $data['type_id'] = $type_id; // grab the id of the current type,
                                                 // to use it in the category anchor
                                                 // (to create the URLs for the type's categories)

                    $data['content'] = 'template_content/type_category_content_view';//send everything to the
                                                                                     //view displaying the categories
                                                                                     //for a car type
                }
                else { //if the car type exists in the database, but it doesn't contain any categories of products
                       //so - it doesn't contain products also
                    $data['missing_message'] = 'Sorry! Currently, no categories can be found for this type.';
                    $data['content'] = 'template_content/missing_view';
                }
            }
            else { //if the car type doesn't exist in the database
               $data['missing_message'] = 'Currently, this car type cannot be found.';
               $data['breadcrumbs'] = $this->breadcrumbs; // maintain the layout
               $data['content'] = 'template_content/missing_view';
            }

             $this->load->view('template_view', $data);//send everything to the main view
        }
        /*else {
            redirect('brand');
        }*/
    }

    public function type_products()
    {
        if($this->uri->segment(2,0) == 'type_products'){
            redirect('brand'); // avoid accessing the 'categories'type_products' URI in the URL
        }

        $type_id = (int)$this->uri->segment(2,0);
        $categ_id = (int)$this->uri->segment(4,0);

        if($type_id != 0 && $categ_id != 0){
            // get the car type's products which are classified in categories
            // for example: for the Filters category, display Air Filter, Water Filter etc.
            
            // if the car type and category exist in the database (independently)
            if($this->cartype_model->type_exists($type_id) && $this->category_model->category_exists($categ_id)){

                $this->session->set_userdata('car_type_id', $type_id); // each time '/car_type/id' URI is accessed, retain the car type's id

                //display the breadcrumbs only if the car type and the category exist in the database
                $brand_model_type = $this->cartype_model->getBrandModelTypeName($type_id);//get the brand name,
                                                                                          //the model name and
                                                                                          //the type name from
                                                                                         //the database
                $category_name = $this->category_model->getCategoryName($categ_id);// get the name of the category
                                                                                   // from the database

                //retain the brand name, the model name, the type name and the category name as elements of the
                //$breadcrumbs array
                array_push($this->breadcrumbs,
                           $brand_model_type[0]->brand_name,
                           'car_type/'.$brand_model_type[0]->cbrand_id,
                           $brand_model_type[0]->model_name,
                           'car_model/'.$brand_model_type[0]->cmodel_id,
                           $brand_model_type[0]->type_name,
                           'car_type/'.$type_id,
                           $category_name[0]->categ_name,
                           'category/'.$categ_id
                          );

                $data['breadcrumbs'] = $this->breadcrumbs;

                // get all the products from the category with id = $categ_id,
                // for the car type with the id = $type_id
                $categ_products = $this->cartype_model->getCategoryProducts($type_id, $categ_id);

                // if there are products for the car type within the specified category,
                // send them to the view, together with the car type's id
                if($categ_products != NULL){
                    $data['categ_products'] = $categ_products;
                    $data['type_id'] = $type_id;
                    $data['content'] = 'template_content/category_products_content_view';
                }
                else { // otherwise, throw an error message
                    $data['missing_message'] = 'Sorry! Currently, there are no products for this car type in this category.';
                    $data['content'] = 'template_content/missing_view';
                }
                
            }
            // if the car type exists in the database, but the category doesn't, throw an error message
            else if($this->cartype_model->type_exists($type_id) && !$this->category_model->category_exists($categ_id)){
                $data['missing_message'] = 'Sorry! No such category of products.';
                $data['breadcrumbs'] = $this->breadcrumbs; // maintain the layout
                $data['content'] = 'template_content/missing_view';
            }

            // if the car type doesn't exist in the database, but the category does, throw an error message
            else if(!$this->cartype_model->type_exists($type_id) && $this->category_model->category_exists($categ_id)){
                $data['missing_message'] = 'Sorry! No such car type.';
                $data['breadcrumbs'] = $this->breadcrumbs; // maintain the layout
                $data['content'] = 'template_content/missing_view';
            }
            else { // if both the car type and the category don't exist in the database, throw an error message
                $data['missing_message'] = 'Sorry! No such car type and no such category of products.';
                $data['breadcrumbs'] = $this->breadcrumbs; // maintain the layout
                $data['content'] = 'template_content/missing_view';
            }

            $this->load->view('template_view', $data); // send everything to the main view
        }
        else if($type_id != 0 && $categ_id == 0){
            redirect('car_type/'.$type_id); // if the type id is specified in the URL, redirect
                                            // 'car_type/id/category' to 'car_type/id'
        }

        /*else {
            redirect('brand');
        }*/

        
    }

    public function type_items()
    {
        if($this->uri->segment(2,0) == 'type_items'){
            redirect('brand'); // avoid accessing the 'type_items' URI in the URL
        }

        $type_id = (int)$this->uri->segment(2,0);
        $subcateg_id = (int)$this->uri->segment(4,0); // fx: Air Filter subcategory

        if($type_id != 0 && $subcateg_id != 0){
            // get the car type's products which are classified in sub-categories
            // for example: for the Filters category and Air Filter sub-category, display a list of products

            // if the car type and the sub-category (or the "product name") exist in the database (independently)
            if($this->cartype_model->type_exists($type_id) && $this->category_model->subcategory_exists($subcateg_id)){

                $this->session->set_userdata('car_type_id', $type_id); // each time '/car_type/id' URI is accessed, retain the car type's id

                //display the breadcrumbs only if the car type and the sub-category exist in the database
                $brand_model_type = $this->cartype_model->getBrandModelTypeName($type_id);//get the brand name,
                                                                                          //the model name and
                                                                                          //the type name from
                                                                                         //the database
                $category_name = $this->category_model->getSubcategCategoryName($subcateg_id);// get the name of the category
                                                                                    // and the name of the sub-category
                                                                                   // from the database

                //retain the brand name, the model name, the type name, the category name
                //and the sub-category name as elements of the
                //$breadcrumbs array
                array_push($this->breadcrumbs,
                           $brand_model_type[0]->brand_name,
                           'brand/'.$brand_model_type[0]->cbrand_id,
                           $brand_model_type[0]->model_name,
                           'car_model/'.$brand_model_type[0]->cmodel_id,
                           $brand_model_type[0]->type_name,
                           'car_type/'.$type_id,
                           $category_name[0]->categ_name,
                           'car_type/'.$type_id.'/category/'.$category_name[0]->categ_id,
                           $category_name[0]->pn_name // the sub-category name
                          );

                $data['breadcrumbs'] = $this->breadcrumbs;

                // get all the products from the sub-category with id = $subcateg_id,
                // for the car type with the id = $type_id
                $subcateg_products = $this->cartype_model->getSubcategProducts($type_id, $subcateg_id);

                // if there are products for the car type within the specified subcategory,
                // send them to the view, together with the car type's id
                if($subcateg_products != NULL){
                    $data['subcateg_products'] = $subcateg_products;
                    $data['type_id'] = $type_id;
                    $data['content'] = 'template_content/subcategory_products_content_view';
                }
                else { // otherwise, throw an error message
                    $data['missing_message'] = 'Sorry! Currently, there are no products with this name, for this car type.';
                    $data['content'] = 'template_content/missing_view';
                }

            }
            // if the car type exists in the database, but the sub-category doesn't, throw an error message
            else if($this->cartype_model->type_exists($type_id) && !$this->category_model->subcategory_exists($subcateg_id)){
                $data['missing_message'] = 'Sorry! No such products with this name.';
                $data['breadcrumbs'] = $this->breadcrumbs; // maintain the layout
                $data['content'] = 'template_content/missing_view';
            }

            // if the car type doesn't exist in the database, but the sub-category does, throw an error message
            else if(!$this->cartype_model->type_exists($type_id) && $this->category_model->subcategory_exists($subcateg_id)){
                $data['missing_message'] = 'Sorry! No such car type.';
                $data['breadcrumbs'] = $this->breadcrumbs; // maintain the layout
                $data['content'] = 'template_content/missing_view';
            }
            else { // if both the car type and the sub-category don't exist in the database, throw an error message
                $data['missing_message'] = 'Sorry! No such car type and no such products with this name.';
                $data['breadcrumbs'] = $this->breadcrumbs; // maintain the layout
                $data['content'] = 'template_content/missing_view';
            }

            $this->load->view('template_view', $data); // send everything to the main view
        }
        else if($type_id != 0 && $subcateg_id == 0){
            redirect('car_type/'.$type_id); // if the type id is specified in the URL, redirect
                                            // 'car_type/id/products' to 'car_type/id'
        }

        /*else {
            redirect('brand');
        }*/

    }
}