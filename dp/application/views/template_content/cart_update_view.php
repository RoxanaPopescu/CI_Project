<div class="update_content">
    <ul class="products">
        
        <?php if($this->session->flashdata('delete_item')): // when an item is deleted from the cart, display a message ?>
            <div class="flashdata">
                <?php echo $this->session->flashdata('delete_item'); ?>
            </div><br />
        <?php endif; ?>

        <?php foreach($cart_contents as $item): ?>
        <li>
             <?php if(count($item['options']) == 12): // if the item is a machine part or an universal product ?>
             <?php
                echo form_open('cart/update', 'class="update"');
                $sendTo = 'category';
             ?>
             <?php else: ?>
             <?php
                echo form_open('cart/update_universal', 'class="update_universal"');
                $sendTo = 'universal';
             ?>
             <?php endif; ?>
             <div class="prod_left">
                <?php echo form_hidden('product_id', $item['id']); ?>
                <?php echo form_hidden('product_category', $item['options']['categ_id']); // send also the categ id for a potential order (to see if it is a machine part or not)?>
                <?php echo '<h2>'.$item['name'].'</h2>'; ?>
                <img src="<?php echo base_url(); ?>assets/pictures/<?php echo $item['options']['product_image']; ?>" /><br />
                <strong>Category:</strong><?php echo anchor($sendTo.'/'.$item['options']['categ_id'], $item['options']['category']); ?><br /><br />
                <strong>Produced by:</strong><?php echo $item['options']['producer']; ?>
                <p><strong>Details:</strong><br />
                <?php
                    $desc = explode('|', $item['options']['description']);
                    foreach($desc as $d){
                        echo $d.'<br />';
                    }
                ?>
                </p><br />
                <?php if(count($item['options']) == 12): ?>
                <strong>Compatible with:</strong><br />
                Brand: <?php echo anchor('brand/'.$item['options']['cbrand_id'], $item['options']['brand_name']).', '; ?>
                Model: <?php echo anchor('car_model/'.$item['options']['cmodel_id'], $item['options']['model_name']).', '; ?>
                Type: <?php echo anchor('car_type/'.$item['options']['ctype_id'], $item['options']['type_name']); ?>
                <br />
                <?php endif; ?>
             </div>

             <div class="prod_right">
                Price:
                <?php echo '<span class="prod_price">'.$item['price'].' RON</span><br />'; ?>
          
                    <?php echo form_label('Update quantity:', 'quantity_'.$item['id'].'_'.$item['options']['categ_id']); ?>
                    <?php
                        $data = array(
                                        'name' => 'quantity',
                                        'id' => 'quantity_'.$item['id'].'_'.$item['options']['categ_id'],
                                        'value' => $item['qty'],
                                        'maxlength' => '4',
                                        'size' => '6'
                                     );
                        echo form_input($data);
                    ?>
                    <?php echo anchor('cart/delete/'.$item['rowid'], '[Delete]'); ?><br />
                    
                    <?php
                     //$js = 'onclick="addItem('.$product->final_id.')"';
                     echo '<br />'.form_submit('update', 'Update Quantity'/*, $js*/);
                    ?><br /><br />

                    <span class="error_qty_class" id="error_qty_<?php echo $item['id'].'_'.$item['options']['categ_id']; ?>" style="color:red;"></span>
                    <span class="update_qty_class" id="update_qty_<?php echo $item['id'].'_'.$item['options']['categ_id']; ?>" style="color:green;"></span>
                    <?php echo anchor('cart/request', 'Request more items', 'class="add_more" id="add_more_'.$item['id'].'_'.$item['options']['categ_id'].'"'); ?>
              </div>
            
            <?php echo form_close(); ?>
        </li>
        <div class="clear_float"></div>
        <div class="split_bar"><img src="<?php echo base_url(); ?>assets/img/split.png" /></div>
        <?php endforeach; ?>
    </ul><br />
    <div class="total_sum_main">
        <strong>Total sum:</strong> <span class="prod_price"><?php echo $this->cart->total().' RON'; ?></span>
        <?php echo ' | '.anchor('cart/order', 'Order'); ?>
        <?php echo ' | '.anchor('cart/empty_cart', 'Empty cart'); ?>
    </div>
</div>