<div class="home_content">
    <ul class="products">
        <?php foreach($recent_products as $product): ?>
        <li>
            <?php
                //$js = 'onsubmit="return false;"';
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
                </p><br />
                <strong>Compatible with:</strong><br />
                Brand: <?php echo anchor('brand/'.$product->cbrand_id, $product->brand_name).', '; ?>
                Model: <?php echo anchor('car_model/'.$product->cmodel_id, $product->model_name).', '; ?>
                Type: <?php echo anchor('car_type/'.$product->ctype_id, $product->type_name); ?><br />
            </div>

            <div class="prod_right">
                Price:
                <?php echo '<span class="prod_price">'.$product->product_price.' RON</span><br />'; ?>
                Status:
                <?php echo '<span>'.$product->product_status.'</span><br />'; ?><br />

                <?php if($product->available_qty != 0): ?>
                    <?php echo form_label('Choose quantity:', 'quantity_'.$product->final_id.'_'.$product->categ_id); ?>
                    <?php
                        $data = array(
                                        'name' => 'quantity',
                                        'id' => 'quantity_'.$product->final_id.'_'.$product->categ_id,
                                        'value' => '1',
                                        'maxlength' => '4',
                                        'size' => '6'
                                     );
                        echo form_input($data);
                    ?>

                    <?php
                     //$js = 'onclick="addItem('.$product->final_id.')"';
                     echo '<br />'.form_submit('add', 'Add to cart'/*, $js*/);
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
