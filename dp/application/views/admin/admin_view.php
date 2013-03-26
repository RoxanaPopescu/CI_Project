<?php $this->load->view('admin/header_admin'); ?>

<div class="admin_content">
    <?php $this->load->view($admin_welcome); ?>

    <?php $this->load->view($admin_menu); ?><br />

    <?php if(isset($content)): ?>
        <?php $this->load->view($content); ?>
    <?php endif; ?>
</div>

<?php $this->load->view('includes/footer_view')?>
