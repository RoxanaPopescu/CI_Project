
<div class="validation_error">

    <span><?php echo validation_errors(); // when either email or password are wrong ?></span>

    <?php if(isset($message) && !empty($message)): // if the user didn't reach to log in ?>
        <span><?php echo $message; // 'Email and password didn't match' message ?></span>
    <?php endif; ?>

    <?php if($this->session->flashdata('message')): // when you log out successfully ?>
      <span class="flashdata_logout">
            <?php echo $this->session->flashdata('message'); ?>
      </span>
    <?php endif; ?>
        
</div>

<?php $login_form = array('class' => 'form'); ?>

<?php echo form_open('user/login', $login_form); ?>

<?php $attributes = array('id' => 'login_form'); ?>

<?php echo form_fieldset('User Information', $attributes); ?>

<?php echo form_label('Email:', 'email'); ?>

<br />

<?php $email_input = array('name' => 'email', 'id' => 'email', 'value' => set_value('email', '')); ?>

<?php echo form_input($email_input); ?>

<br />

<?php echo form_label('Password:', 'password'); ?>

<br />

<?php $password_input = array('name' => 'password', 'id' => 'password', 'value' => set_value('password', '')); ?>

<?php echo form_password($password_input); ?>

<br /><br />

<div class="form_button">

<?php echo form_submit('submit', 'Log In'); ?>

</div>

<?php echo form_fieldset_close(); ?>

<?php echo form_close(); ?>
