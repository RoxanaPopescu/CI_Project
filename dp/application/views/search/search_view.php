<div class="search_view">
    <ul class="products">
        
        <?php foreach($products as $item): ?>
        <li>
             <?php if($item->is_part == 1): // if the item is a machine part or an universal product ?>
             <?php
                echo form_open('cart/add', 'class="add"');
                $sendTo = 'category';
                $id = $item->final_id;
             ?>
             <?php else: ?>
             <?php
                echo form_open('cart/add_universal', 'class="add_universal"');
                $sendTo = 'universal';
                $id = $item->univ_id;
             ?>
             <?php endif; ?>
             <div class="prod_left">
                <?php echo form_hidden('product_id', $id); ?>
                <?php echo form_hidden('product_category', $item->categ_id); // send also the categ id for a potential order (to see if it is a machine part or not)?>
                <?php echo '<h2>'.$item->pn_name.'</h2>'; ?>
                <img src="<?php echo base_url(); ?>assets/pictures/<?php echo $item->product_image; ?>" /><br />
                <strong>Category:</strong><?php echo anchor($sendTo.'/'.$item->categ_id, $item->categ_name); ?><br /><br />
                <strong>Produced by:</strong><?php echo $item->producer_name; ?>
                <p><strong>Details:</strong><br />
                <?php
                    $desc = explode('|', $item->pd_desc);
                    foreach($desc as $d){
                        echo $d.'<br />';
                    }
                ?>
                </p><br />
                <?php if($item->is_part == 1): ?>
                <strong>Compatible with:</strong><br />
                Brand: <?php echo anchor('brand/'.$item->cbrand_id, $item->brand_name).', '; ?>
                Model: <?php echo anchor('car_model/'.$item->cmodel_id, $item->model_name).', '; ?>
                Type: <?php echo anchor('car_type/'.$item->ctype_id, $item->type_name); ?>
                <br />
                <?php endif; ?>
             </div>

             <div class="prod_right">
                Price:
                <?php echo '<span class="prod_price">'.$item->product_price.' RON</span><br />'; ?>
                Status:
                <?php echo '<span>'.$item->product_status.'</span><br />'; ?>

                <?php if($item->available_qty != 0): ?>

                    <?php echo form_label('Choose quantity:', 'quantity_'.$id.'_'.$item->categ_id); ?>
                    <?php
                        $data = array(
                                        'name' => 'quantity',
                                        'id' => 'quantity_'.$id.'_'.$item->categ_id,
                                        'value' => '1',
                                        'maxlength' => '4',
                                        'size' => '6'
                                     );
                        echo form_input($data);
                    ?>
                    <?php
                     //$js = 'onclick="addItem('.$product->final_id.')"';
                     echo '<br />'.form_submit('add', 'Add to cart'/*, $js*/);
                    ?>
                    <br /><br />
                    <span class="error_qty_class" id="error_qty_<?php echo $id.'_'.$item->categ_id; ?>" style="color:red;"></span>
                    <span class="success_qty_class" id="success_qty_<?php echo $id.'_'.$item->categ_id; ?>" style="color:green;"></span>
                    <?php echo anchor('cart/request', 'Request more items', 'class="add_more" id="add_more_'.$id.'_'.$item->categ_id.'"'); ?>

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
