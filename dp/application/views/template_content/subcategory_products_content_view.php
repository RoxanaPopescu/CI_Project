<div class="type_categ_subcateg">
    <ul class="products">
        <?php foreach($subcateg_products as $product): ?>
        <li>
            <?php 
                echo form_open('cart/add', 'class="add"');
            ?>
            <div class="prod_left">
                <?php echo form_hidden('product_id', $product->final_id); ?>
                <?php echo form_hidden('product_category', $product->categ_id); ?>
                <?php echo '<h2>'.$product->pn_name.'</h2>'; ?>
                <img src="<?php echo base_url(); ?>assets/pictures/<?php echo $product->product_image; ?>" /><br />
                <strong>Category:</strong><?php echo anchor('category/'.$product->categ_id, $product->categ_name); ?><br /><br />
                <strong>Produced by:</strong><?php echo $product->producer_name.'<br />'; ?>
                <p><strong>Details:</strong><br />
                <?php
                    $desc = explode('|', $product->pd_desc);
                    foreach($desc as $d){
                        echo $d.'<br />';
                    }
                ?>
                </p>
            </div>

            <div class="prod_right">
                <div class="admin_modify_product">
                <?php if ($this->session->userdata('is_logged_in') == TRUE && $this->config->item('admin_level') == $this->session->userdata('user_level')): // if the user is logged in and he is the admin?>

                        <div class="modify_header">
                            Modify Product
                            <span class="clickable">+</span>
                        </div>

                        <div class="modify_options">
                            <div class="modify_edit">
                                <?php echo anchor('admin/edit/category/'.$product->categ_id.'/product/'.$product->final_id, 'Edit Product'); ?>
                            </div>

                            <div class="modify_delete">
                                <?php echo anchor('admin/remove/category/'.$product->categ_id.'/product/'.$product->final_id, 'Remove Product'); ?>
                            </div>
                        </div>

                <?php endif;?>
                </div>

                Price:
                <?php echo '<span class="prod_price">'.$product->product_price.' RON</span><br />'; ?>
                Status:
                <?php echo '<span>'.$product->product_status.'</span><br />'; ?><br />

                <?php if($product->available_qty != 0): ?>
                    <?php echo form_label('Choose quantity:', 'quantity_'.$product->final_id.'_'.$product->categ_id); ?>
                    <?php
                        if ($this->session->userdata('is_logged_in') == TRUE && $this->config->item('admin_level') == $this->session->userdata('user_level')):
                                $data = array(
                                                'name' => 'quantity',
                                                'id' => 'quantity_'.$product->final_id.'_'.$product->categ_id,
                                                'value' => '1',
                                                'maxlength' => '4',
                                                'size' => '6',
                                                'disabled' => 'disabled'
                                             );
                        else:
                                $data = array(
                                                'name' => 'quantity',
                                                'id' => 'quantity_'.$product->final_id.'_'.$product->categ_id,
                                                'value' => '1',
                                                'maxlength' => '4',
                                                'size' => '6'
                                             );
                        endif;
                        
                        echo form_input($data);
                    ?>

                    <?php
                    if ($this->session->userdata('is_logged_in') == TRUE && $this->config->item('admin_level') == $this->session->userdata('user_level')):

                                $data = array(
                                                'name' => 'add',
                                                'value' => 'Add to cart',
                                                'disabled' => 'disabled'
                                             );
                                echo '<br />'.form_submit($data);

                     else:
                                echo '<br />'.form_submit('add', 'Add to cart');
                     endif;
                    ?><br /><br />

                    <span class="error_qty_class" id="error_qty_<?php echo $product->final_id.'_'.$product->categ_id; ?>" style="color:red;"></span>
                    <span class="success_qty_class" id="success_qty_<?php echo $product->final_id.'_'.$product->categ_id; ?>" style="color:green;"></span>
                    <?php echo anchor('cart/request', 'Request more items', 'class="add_more" id="add_more_'.$product->final_id.'_'.$product->categ_id.'"'); ?>
                    

                <?php else: ?>
                    <?php echo anchor('cart/request', 'Request this product'); ?>
                <?php endif; ?>
            </div>

            <?php echo form_close(); ?>
        </li>
        <div class="clear_float"></div>
        <div class="split_bar"><img src="<?php echo base_url(); ?>assets/img/split.png" /></div>
        <?php endforeach; ?>
    </ul>
</div>
