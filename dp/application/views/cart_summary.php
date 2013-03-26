    <div class="cart_heading">
        <span class="cart_desc"><?php
                //$contents = $this->cart->contents();
                $total_items = $this->cart->total_items();
                echo ($total_items == 0) ? 'Your cart is empty' : 'Products in your cart ';
            ?></span> (<span class="cart_nr"><?php
            $contents = $this->cart->contents();// $this->cart->contents() and $this->cart->total_items()
            $total_items = $this->cart->total_items(); // each need to be kept in a variable in order to be
                                                       // checked with the isset() method
            if(isset($contents)){
                   $total_items = $this->cart->total_items();
                   echo $total_items;
            }
            else{
                echo 0;
            }
            ?></span>)
       
       <span class="view_cart"><?php echo ' | '.anchor('cart/view', 'View Cart'); ?></span>
       <span class="order_cart"><?php echo ' | '.anchor('cart/order', 'Order'); ?></span>
    </div>
    
    <div class="cart_products">
        <?php
            $contents = $this->cart->contents();
            if(isset($contents)):
        ?>
        <ul>
            <?php foreach($contents as $item): ?>
            <li id="display_<?php echo $item['id'].'_'.$item['options']['categ_id']; ?>">
                <?php echo $item['name'].' '.$item['options']['producer']; ?>
                <span class="display_qty"><?php echo $item['qty']; ?></span><?php echo 'x'.$item['price'].' RON='; ?><span class="display_subtotal"><?php echo $item['subtotal'].' RON'; ?></span>
            </li>
            <?php endforeach; ?>
        </ul><br />
        <?php endif; ?>
        
        <div class="total_sum_summary">
            <strong>Total sum:</strong> <span class="total_sum"><?php echo $this->cart->total().' RON'; ?></span>
        </div>
   </div>
