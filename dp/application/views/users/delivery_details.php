<div>
  <?php
    if(isset($initial_address) && $initial_address != NULL){
        $address = $initial_address;
    }
    else {
        $initial_address = ''; // if the form validation on 'cart/order' fails, the user is redirected to the same page,
                               // but with the textarea field empty and with an error message displayed
    }
  ?>

  <?php echo form_open('cart/order'); ?>
  <div class="cart_order">
          <div class="delivery_address_error">
              <span>
                <?php echo form_error('delivery_address'); ?>
              </span>
          </div>

          <?php echo form_label('Deliver the products to this address:', 'delivery_address'); ?>
          <br /><br />
          <?php
            $data = array(
                      'name'        => 'delivery_address',
                      'id'          => 'delivery_address',
                      'value'       => $initial_address,
                      'cols'        => '40',
                      'rows'        => '6'
                    );

            echo form_textarea($data);
          ?><br />

          <?php echo form_submit('send_order', 'Send Order'); ?>
  </div>

  <?php echo form_close(); ?>
</div>
