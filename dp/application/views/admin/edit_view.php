<div class="admin_edit_product">
    <?php $flash = $this->session->flashdata('success_update'); ?>
    <?php if(isset($flash)): ?>
            <?php echo '<span>'.$flash.'</span>'; ?>
    <?php endif;?>

    <h2>Edit Product</h2><br />
    <?php echo form_open_multipart('admin/edit/category/'.$products[0]->categ_id.'/product/'.$products[0]->final_id); ?>

    <?php echo form_hidden('pn_id', $products[0]->pn_id); ?>
    <?php echo form_hidden('id', $products[0]->final_id); ?>

    <?php echo form_label('Product Name: ', 'product_name'); ?>

    <?php
          $name_input = array(
                               'name' => 'product_name',
                               'id' => 'product_name',
                               'value' => $products[0]->pn_name
                             );

          echo form_input($name_input);
    ?>
    <?php echo form_error('product_name'); ?>
    <br /><br />

    <?php echo form_label('Product Category: ', 'category_name'); ?>

    <?php
          $category_input = array(
                               'name' => 'category_name',
                               'id' => 'category_name',
                               'value' => $products[0]->categ_name
                             );

          echo form_input($category_input);
    ?><br /><br />

    <?php echo form_label('Producer: ', 'producer_name'); ?>

    <?php
          $producer_input = array(
                               'name' => 'producer_name',
                               'id' => 'producer_name',
                               'value' => $products[0]->producer_name
                             );

          echo form_input($producer_input);
    ?><br /><br /><br />

    <div class="description">
    <p>Product Details:</p><br />
    <?php foreach($details as $detail): ?>
    <?php
        $checked = FALSE;
        $value_checked = '';
    ?>

        <?php foreach($detailAndValue as $dv): ?>
          <?php if(in_array(strtolower($detail->detail_name), $dv )): ?>
                <?php
                    $checked = TRUE;
                    $value_checked = $dv[1];
                    break;
                ?>
          <?php endif; ?>
        <?php endforeach; ?>

    <div class="product_detail">
        <?php
              $detail_box = array(
                                   'name' => 'product_details[]',
                                   'class' => 'details_list',
                                   'id' => 'product_detail_'.$detail->detail_id,
                                   'value' => $detail->detail_id,
                                   'checked' => $checked
                                 );

              echo form_checkbox($detail_box);

        ?>

        <?php echo form_label(ucfirst($detail->detail_name), 'product_detail_'.$detail->detail_id); ?>

        <?php
            //if($checked == TRUE){
                  $data = array(
                                  'name' => 'value_field_'.$detail->detail_id,
                                  'id' => 'value_field_'.$detail->detail_id,
                                  'value' => trim($value_checked),
                                  'size' => 6,
                                  'maxlength' => 6
                                );

                  echo form_input($data);
              //}
        ?>

    </div>
    <br />

    <?php endforeach; ?>
 </div>
    <p><span id="add_more" style="cursor: pointer;">Add Another Detail</span></p>
    <br /><br />


    <?php echo form_label('Product Price: ', 'product_price'); ?>

    <?php
          $price_input = array(
                               'name' => 'product_price',
                               'id' => 'product_price',
                               'value' => $products[0]->product_price
                             );

          echo form_input($price_input);
    ?><br /><br />

    <?php echo form_label('Available Quantity: ', 'available_qty'); ?>

    <?php
          $qty_input = array(
                               'name' => 'available_qty',
                               'id' => 'available_qty',
                               'value' => $products[0]->available_qty
                             );

          echo form_input($qty_input);
    ?><br /><br />

     <?php echo form_label('Product Status: ', 'product_status'); ?>

    <?php
          $status_input = array(
                               'name' => 'product_status',
                               'id' => 'product_status',
                               'value' => $products[0]->product_status
                             );

          echo form_input($status_input);
    ?><br /><br />

    <?php echo form_label('Car Brand: ', 'car_brand'); ?>

    <?php
          $brand_input = array(
                               'name' => 'car_brand',
                               'id' => 'car_brand',
                               'value' => $products[0]->brand_name
                             );

          echo form_input($brand_input);
    ?><br /><br />

    <?php echo form_label('Car Model: ', 'car_model'); ?>

    <?php
          $model_input = array(
                               'name' => 'car_model',
                               'id' => 'car_model',
                               'value' => $products[0]->model_name
                             );

          echo form_input($model_input);
    ?><br /><br />
    
    <?php echo form_label('Car Type: ', 'car_type'); ?>

    <?php
          $type_input = array(
                               'name' => 'car_type',
                               'id' => 'car_type',
                               'value' => $products[0]->type_name
                             );

          echo form_input($type_input);
    ?><br /><br />

    <?php echo form_submit('edit_submit', 'Update'); ?>
    <?php echo form_close(); ?>
</div>
