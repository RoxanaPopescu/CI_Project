<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends CI_Controller {
    private $breadcrumbs = array();

    public function  __construct() {
        parent::__construct();
        $this->load->model('category_model');
    }

    public function index()
    {
        redirect('brand');
    }

    
    public function classified()
    {

        if($this->uri->segment(2,0) == 'classified'){
            redirect ('brand');// avoid accessing the classified URI in the URL
        }

        $category_id = (int)$this->uri->segment(2,0);//grab the category's id from the URL
                                                  //and sanitize it

        // if the category accessed does not contain machine parts, but universal products,
        // redirect the user
        /*if(!$this->category_model->is_part($category_id)){
            redirect ('universal/'.$category_id);
        }*/

        if($category_id != 0){// if this id exists in the URL
            if($this->category_model->category_exists($category_id)){ //if the category exists in the database
                
                // save the category's name as a breadcrumb
                $categ_name = $this->category_model->getCategoryName($category_id);
                $this->breadcrumbs[] = $categ_name[0]->categ_name;
                $data['breadcrumbs'] = $this->breadcrumbs;

                // get all the products from this category
                $products = $this->category_model->allFromCategory($category_id);

                if($products != NULL){ // if there are products to display
                    // send the data to the view
                    $data['products'] = $products;
                    $data['content'] = 'template_content/all_products_category_view';

                }
                else {
                    // send a missing message to the view
                    $data['missing_message'] = 'Sorry! Currently, there are no products available in this category.';
                    $data['content'] = 'template_content/missing_view';
                }
            }
            else { // if the category doesn't exist in the database
                   // send a missing message to the view
                    $data['missing_message'] = 'Sorry! Currently, this category of products doesn\'t exist.';
                    $data['breadcrumbs'] = $this->breadcrumbs;
                    $data['content'] = 'template_content/missing_view';
            }

            $this->load->view('template_view', $data); // send everything to the main view
        }
    }

    public function universal()
    {

        if($this->uri->segment(2,0) == 'universal'){
            redirect ('brand');// avoid accessing the universal URI in the URL
        }

        $category_id = (int)$this->uri->segment(2,0);//grab the category's id from the URL
                                                  //and sanitize it

        // if the category accessed does not contain machine parts, but universal products,
        // redirect the user
        /*if(!$this->category_model->is_part($category_id)){
            redirect ('universal/'.$category_id);
        }*/

        if($category_id != 0){// if this id exists in the URL

            if($this->category_model->category_exists($category_id)){ //if the category exists in the database

                // save the category's name as a breadcrumb
                $categ_name = $this->category_model->getCategoryName($category_id);
                $this->breadcrumbs[] = $categ_name[0]->categ_name;
                $data['breadcrumbs'] = $this->breadcrumbs;

                // get all the products from this category
                $products = $this->category_model->universalFromCategory($category_id);

                if($products != NULL){ // if there are products to display
                    // send the data to the view
                    $data['products'] = $products;
                    $data['content'] = 'template_content/all_universal_category_view';

                }
                else {
                    // send a missing message to the view
                    $data['missing_message'] = 'Sorry! Currently, there are no products available in this category.';
                    $data['content'] = 'template_content/missing_view';
                }
            }
            else { // if the category doesn't exist in the database
                   // send a missing message to the view
                    $data['missing_message'] = 'Sorry! Currently, this category of products doesn\'t exist.';
                    $data['breadcrumbs'] = $this->breadcrumbs;
                    $data['content'] = 'template_content/missing_view';
            }

            $this->load->view('template_view', $data); // send everything to the main view
        }
    }

    public function universal_products()
    {
        // get all the categories of the universal products
        $univ_categ = $this->category_model->getUnivCateg();

        if($univ_categ != NULL){ // if there are categories to display
            $data['univ_categ'] = $univ_categ;
            $data['content'] = 'template_content/universal_categ_view';
            $this->breadcrumbs[0] = 'Select a category with universal products';
        }
        else{
           $data['missing_message'] = 'Sorry! Currently, there are no universal products.';
           $data['content'] = 'template_content/missing_view';
        }
        $data['breadcrumbs'] = $this->breadcrumbs;

        $this->load->view('template_view', $data); // send everything to the main view
    }
}