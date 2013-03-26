<div class="universal_products">
    <ul class="products">
        <?php foreach($products as $product): ?>
        <li>
            <?php
                //$js = 'onsubmit="return false;"';
                echo form_open('cart/add', 'class="add_universal"');
            ?>
            <div class="prod_left">
                <?php echo form_hidden('product_id', $product->univ_id); ?>
                <?php echo form_hidden('product_category', $product->categ_id); ?>
                <?php echo '<h2>'.$product->pn_name.'</h2>'; ?>
                <img src="<?php echo base_url(); ?>assets/pictures/<?php echo $product->product_image; ?>" /><br /><br />
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
                Price:
                <?php echo '<span class="prod_price">'.$product->product_price.'RON</span><br />'; ?>
                Status:
                <?php echo '<span>'.$product->product_status.'</span><br />'; ?>

                <?php if($product->available_qty != 0): ?>
                    <?php echo form_label('Choose quantity:', 'quantity_'.$product->univ_id.'_'.$product->categ_id); ?>
                    <?php
                        $data = array(
                                        'name' => 'quantity',
                                        'id' => 'quantity_'.$product->univ_id.'_'.$product->categ_id,
                                        'value' => '1',
                                        'maxlength' => '4',
                                        'size' => '6'
                                     );
                        echo form_input($data);
                    ?>

                    <?php
                     echo '<br />'.form_submit('add', 'Add to cart');
                    ?><br /><br />

                    <span class="error_qty_class" id="error_qty_<?php echo $product->univ_id.'_'.$product->categ_id; ?>" style="color:red;"></span>
                    <span class="success_qty_class" id="success_qty_<?php echo $product->univ_id.'_'.$product->categ_id; ?>" style="color:green;"></span>
                    <?php echo anchor('cart/request', 'Request more items', 'class="add_more" id="add_more_'.$product->univ_id.'_'.$product->categ_id.'"'); ?>
                    
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
