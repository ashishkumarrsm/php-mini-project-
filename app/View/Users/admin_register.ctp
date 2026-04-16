<section class="admin-auth-shell">
    <div class="admin-auth-copy">
        <span class="eyebrow">Create admin</span>
        <h1>Register a new administrator account.</h1>
        <p>This creates a user with admin privileges so the account can sign in at the admin panel and reach the protected routes.</p>
        <div class="admin-auth-points">
            <span>Admin-only role</span>
            <span>Works with `/admin` routes</span>
            <span>Redirects into dashboard flow</span>
        </div>
    </div>

    <div class="admin-auth-card panel">
        <?php echo $this->Form->create('User', array('class' => 'auth-form')); ?>
            <?php echo $this->Form->input('username', array(
                'label' => 'Admin username',
                'div' => false,
                'placeholder' => 'Choose an admin username'
            )); ?>

            <?php echo $this->Form->input('email', array(
                'label' => 'Email',
                'div' => false,
                'placeholder' => 'Admin email address'
            )); ?>

            <?php echo $this->Form->input('password', array(
                'label' => 'Password',
                'div' => false,
                'placeholder' => 'Create a secure password'
            )); ?>

            <?php echo $this->Form->submit('Create Admin Account', array('class' => 'button button-primary button-full')); ?>
        <?php echo $this->Form->end(); ?>

        <p class="auth-footer">
            Already have admin access?
            <?php echo $this->Html->link('Go to admin login', array('action' => 'login', 'admin' => true)); ?>
        </p>
    </div>
</section>
