<!--<div class="welcome_message">-->
    <?php if($this->session->userdata('is_logged_in')) :?>
        <span>Welcome, <?php echo $this->session->userdata('first_name'); ?>!</span><br />
        <?php echo anchor('user/logout', 'Log out'); ?>

        <?php if($this->config->item('admin_level') != $this->session->userdata('user_level')): ?>
            <?php echo ' | '.anchor('user/orders', 'Personal Orders'); // display this link only if the user is not an admin ?>
        <?php else: ?>
            <?php if(stristr(uri_string(), 'admin') != FALSE || stristr(uri_string(), 'user') != FALSE): ?>
                <?php echo '<br /><br />'?>
            <?php else: ?>
                <?php if(stristr(uri_string(), 'admin') === FALSE || stristr(uri_string(), 'user') === FALSE): ?>
                    <?php echo ' | '.anchor('admin', 'Admin Panel'); ?>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
        
    <?php else: ?>
    <?php echo '<p class="user_login_register">'.anchor('user/login', 'Sign in').' or '.anchor('user/register', 'Register').'</p>'; ?>
    <?php endif; ?>

<!--</div>-->
