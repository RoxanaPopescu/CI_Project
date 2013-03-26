<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Engine extends CI_Controller {
    private $index;
    
    public function  __construct()
    {
        parent::__construct();
        $this->load->model('cart_model');
    }

    private function _indexingDoc()
    {
        $this->load->library('zend', 'Zend/Search/Lucene/Document');
        $this->load->library('zend', 'Zend/Search/Lucene');
        $this->load->library('zend');
        $this->zend->load('Zend/Search/Lucene');

        //Create index.
        $index = new Zend_Search_Lucene('c:\wamp\www\dp\tmp\feeds_index', true);

        //$feeds = $this->cart_model->searchProduct();
        //print_r($feeds);
        Zend_Search_Lucene_Analysis_Analyzer::setDefault(new Zend_Search_Lucene_Analysis_Analyzer_Common_TextNum_CaseInsensitive());

        $feeds = $this->cart_model->searchProducts();
        //grab each feed.
        foreach($feeds as $feed)
        {
            //create an index doc.
            $document = new Zend_Search_Lucene_Document();
            
            $document->addField(Zend_Search_Lucene_Field::UnIndexed('categ_id', $feed->categ_id));
            $document->addField(Zend_Search_Lucene_Field::Text('categ_name',$feed->categ_name));
            $document->addField(Zend_Search_Lucene_Field::Text('pn_name',$feed->pn_name));
            $document->addField(Zend_Search_Lucene_Field::UnIndexed('pd_desc',$feed->pd_desc));
            $document->addField(Zend_Search_Lucene_Field::Text('producer_name',$feed->producer_name));
            $document->addField(Zend_Search_Lucene_Field::UnIndexed('final_id',$feed->final_id));
            $document->addField(Zend_Search_Lucene_Field::UnIndexed('product_image',$feed->product_image));
            $document->addField(Zend_Search_Lucene_Field::UnIndexed('product_price',$feed->product_price));
            $document->addField(Zend_Search_Lucene_Field::UnIndexed('product_status',$feed->product_status));
            $document->addField(Zend_Search_Lucene_Field::UnIndexed('available_qty',$feed->available_qty));
            $document->addField(Zend_Search_Lucene_Field::UnIndexed('ctype_id',$feed->ctype_id));
            $document->addField(Zend_Search_Lucene_Field::Text('type_name',$feed->type_name));
            $document->addField(Zend_Search_Lucene_Field::UnIndexed('cmodel_id',$feed->cmodel_id));
            $document->addField(Zend_Search_Lucene_Field::Text('model_name',$feed->model_name));
            $document->addField(Zend_Search_Lucene_Field::UnIndexed('cbrand_id',$feed->cbrand_id));
            $document->addField(Zend_Search_Lucene_Field::Text('brand_name',$feed->brand_name));
            $document->addField(Zend_Search_Lucene_Field::UnIndexed('is_part',$feed->is_part));

            //Add our document to the index using addDocument()
            $index->addDocument($document);
     
        }

        $feeds = $this->cart_model->searchUniversal();

        //grab each feed.
        foreach($feeds as $feed)
        {
            //create an index doc.
            $document = new Zend_Search_Lucene_Document();

            $document->addField(Zend_Search_Lucene_Field::UnIndexed('categ_id', $feed->categ_id));
            $document->addField(Zend_Search_Lucene_Field::Text('categ_name',$feed->categ_name));
            $document->addField(Zend_Search_Lucene_Field::Text('pn_name',$feed->pn_name));
            $document->addField(Zend_Search_Lucene_Field::UnIndexed('pd_desc',$feed->pd_desc));
            $document->addField(Zend_Search_Lucene_Field::Text('producer_name',$feed->producer_name));
            $document->addField(Zend_Search_Lucene_Field::UnIndexed('univ_id',$feed->univ_id));
            $document->addField(Zend_Search_Lucene_Field::UnIndexed('product_image',$feed->product_image));
            $document->addField(Zend_Search_Lucene_Field::UnIndexed('product_price',$feed->product_price));
            $document->addField(Zend_Search_Lucene_Field::UnIndexed('product_status',$feed->product_status));
            $document->addField(Zend_Search_Lucene_Field::UnIndexed('available_qty',$feed->available_qty));
            $document->addField(Zend_Search_Lucene_Field::UnIndexed('is_part',$feed->is_part));

            //Add our document to the index using addDocument()
            $index->addDocument($document);

        }

        //Once all documents have been added you must save the index with commit()
        $index->optimize();
        $index->commit();
        //echo $index->count() .' Documents indexed.<br />';
    }

    private function _sanitize($input)
    {
	return htmlentities(strip_tags($input));
    }

    
    public function search() {
        $source_page = $this->input->post('source_page', TRUE);

        if($source_page != 'engine/search'){
            $this->session->unset_userdata('value');
            $this->session->unset_userdata('engine/search');
            $searched_value = $this->input->post('search', TRUE);
            if($searched_value == 'Search...' || $searched_value == '' ){
                redirect($source_page);
            }
        }
        else if($this->session->userdata('page') == 'engine/search' && ($this->input->post('search') == 'Search...' || $this->input->post('search') == '')){
            $searched_value = $this->session->userdata('value');
            $this->session->unset_userdata('value');
            $this->session->unset_userdata('engine/search');
        }
        else{
            $searched_value = $this->input->post('search', TRUE);
        }

        //$uri = uri_string();

        if(isset($searched_value) AND $searched_value != 'Search...' AND $searched_value != ''){

            $this->_indexingDoc();

            /*$this->load->library('zend', 'Zend/Search/Lucene');
            $this->load->library('zend');
            $this->zend->load('Zend/Search/Lucene');*/

            $index = new Zend_Search_Lucene('c:\wamp\www\dp\tmp\feeds_index');
            Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('UTF-8');

            Zend_Search_Lucene_Analysis_Analyzer::setDefault(new Zend_Search_Lucene_Analysis_Analyzer_Common_TextNum_CaseInsensitive());
            $hits = $index->find($searched_value);

            $search_result = 'Search for "'. $searched_value .'" returned '. count($hits) .
            ' hits<br /><br />';

            $this->session->set_userdata('page', 'engine/search');
            $this->session->set_userdata('value', $searched_value);

            /*var_dump($this->session->userdata('searched_value'));
               die();*/
   // $h = array();
   // foreach($hits as $hit) {
        /*echo $hit->brand_name .'<br />';*/
        /*$scores[] = 'Score: '. sprintf('%.2f', $hit->score) .'<br />';
     }*/
        //echo sprintf('%.2f', $hit->score).', ';
        /*Zend_Search_Lucene_Search_QueryHit::highlight();
        $hit->highlight('car oil vic', $colour = '#66ffff');
        $h[] = $hit;*/
   // }
    //die();

            $data['search_result'] = $search_result;
            $data['products'] = $hits;
            $data['content'] = 'search/search_view';

            $this->load->view('template_view', $data);
       }

    }

}