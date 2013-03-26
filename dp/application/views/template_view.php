<?php $this->load->view('includes/header_view'); ?>

 <div class="header">
     <div class="container">
         <div class="row">
             <div class="fourcol logo">
                <p>LOGO - DANYPOT-TRANSYS</p>
             </div>
         </div>
     </div>

     <div class="header_right">
         <div class="container">
             <div class="welcome_message"><?php $this->load->view('includes/welcome_message'); ?></div>
         </div>

         <div class="container">
             <div class="search_bar">
                <?php $this->load->view('includes/search_view');?>
             </div>
         </div>
     </div>
 </div>

<!--<div class="container">
    <div class="row">
        <div class="welcome_message">
            <?php //$this->load->view('includes/welcome_message'); ?>
        </div>
    </div>
</div>-->

<div class="container">
    <div class="row">
        <div class="twocol menu_item">
            <?php echo anchor('brand', 'Home'); ?>
        </div>

        <div class="twocol menu_item">
            <?php echo anchor('universal_products', 'Universal Products'); ?>
        </div>

        <div class="twocol menu_item">
            <?php echo anchor('how_to_buy', 'How To Buy'); ?>
        </div>

        <div class="twocol menu_item">
            <?php echo anchor('cart/request', 'Request'); ?>
        </div>

        <div class="twocol menu_item">
            <?php echo anchor('about_us', 'About Us'); ?>
        </div>

        <div class="twocol menu_item">
           <?php echo anchor('contact', 'Contact Us'); ?>
        </div>
    </div>
</div>

<div class="container">
    <div class="row" id="container">
        <div class="threecol sidebar">

            <div class="title_sidebar"><strong>Select a brand:</strong></div>

            <div class="content_sidebar">
                    <?php $this->load->view('sidebar/sidebar_brands_view'); ?>

                    <?php if($this->session->userdata('car_type_id')):?>
                        <?php $this->load->view('sidebar/sidebar_car_view'); ?>
                    <?php endif; ?>
            
                    <?php $this->load->view('sidebar/sidebar_categories_view'); ?>
            </div>
        </div>

        <div class="sixcol main_content">
                <?php if(isset($breadcrumbs)):?>
                <div class="breadc">
                    <?php $this->load->view('bc_section/breadcrumbs'); ?>
                </div><br />
                <?php endif; ?>

                <?php if(isset($search_result)):?>
                <div class="breadc">
                    <?php echo '<span class="search_result_text">'.$search_result.'</span>'; ?>
                </div><br />
                <?php endif; ?>
            
            <?php $this->load->view($content); ?>
        </div>

        <div class="threecol cart_summary last" id="menu_wrap">
            <div id="menu">
                <?php $this->load->view('cart_summary'); ?>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="twelvecol footer_color">
            <div class="footer_links" style="width: 800px; height: 30px; margin: auto; margin-top: 10px;">
                    <div style="float: left;"><?php echo anchor('/', 'Home'); ?></div>
                    <div style="float: left;"><?php echo anchor('universal_products', 'Universal Products'); ?></div>
                    <div style="float: left;"><?php echo anchor('how_to_buy', 'How To Buy'); ?></div>
                    <div style="float: left;"><?php echo anchor('request', 'Request'); ?></div>
                    <div style="float: left;"><?php echo anchor('about_us', 'About Us'); ?></div>
                    <div style="float: left;"><?php echo anchor('contact', 'Contact Us'); ?></div>
            </div>

            <div class="footer_copyright">
                <strong>DANYPOT-TRANSYS</strong> | Copyright © 2012 | All rights reserved
            </div>
        </div>
    </div>
</div>

    <?php $this->load->view('includes/footer_view')?>

