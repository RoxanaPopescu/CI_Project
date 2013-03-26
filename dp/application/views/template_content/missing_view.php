<div>
    <?php if($this->session->flashdata('delete_item')): ?>
        <div class="flashdata">
                    <?php echo $this->session->flashdata('delete_item'); ?>
        </div><br />
    <?php endif; ?>

    <?php if($this->session->flashdata('empty_cart')): // if the user is redirected from the 'order' page, when the cart is empty ?>
            <div class="flashdata" style="color: red;">
                <?php echo $this->session->flashdata('empty_cart'); ?>
            </div><br />
    <?php endif; ?>

    <div class="missing_message">
        <?php echo $missing_message; ?>
    </div>
</div>
