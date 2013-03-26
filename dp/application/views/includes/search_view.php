<?php echo form_open('engine/search'); ?>

<?php echo form_hidden('source_page', uri_string()); ?>

<?php echo form_input('search', 'Search...'); ?>

<?php

    $data = array(
                    'name' => 'submit',
                    'type' => 'image',
                    'src'  => base_url().'assets/img/searchicon.png',
                    'id'   => 's_icon'
                 );

    echo form_submit($data);
?>

<?php echo form_close(); ?>