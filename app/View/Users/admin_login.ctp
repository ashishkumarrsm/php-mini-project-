<section class="admin-auth-shell">
    <div class="admin-auth-copy">
        <span class="eyebrow">Admin access</span>
        <h1>Sign in to the MyShop control panel.</h1>
        <p>Manage the catalog, monitor orders, and keep the storefront running from a dedicated admin space.</p>
        <div class="admin-auth-points">
            <span>Catalog management</span>
            <span>Order visibility</span>
            <span>User overview</span>
        </div>
    </div>

    <div class="admin-auth-card panel">
        <?php echo $this->Form->create('User', array('class' => 'auth-form')); ?>
            <?php echo $this->Form->input('username', array(
                'label' => 'Admin username',
                'div' => false,
                'placeholder' => 'Enter admin username'
            )); ?>

            <?php echo $this->Form->input('password', array(
                'label' => 'Password',
                'div' => false,
                'placeholder' => 'Enter password'
            )); ?>

            <?php echo $this->Form->submit('Enter Dashboard', array('class' => 'button button-primary button-full')); ?>
        <?php echo $this->Form->end(); ?>

        <p class="auth-footer">
            Need an admin account?
            <?php echo $this->Html->link('Register admin', array('action' => 'register', 'admin' => true)); ?>
        </p>
    </div>
</section>
