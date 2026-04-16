<section class="auth-shell">
    <div class="auth-copy">
        <span class="eyebrow">Welcome back</span>
        <h1>Sign in to manage your cart and orders.</h1>
        <p>Use your account credentials to continue shopping with a smoother checkout flow.</p>
    </div>

    <div class="auth-card panel">
        <?php echo $this->Form->create('User', array('class' => 'auth-form')); ?>
            <?php echo $this->Form->input('username', array(
                'label' => 'Username',
                'div' => false,
                'placeholder' => 'Enter your username'
            )); ?>

            <?php echo $this->Form->input('password', array(
                'label' => 'Password',
                'div' => false,
                'placeholder' => 'Enter your password'
            )); ?>

            <?php echo $this->Form->submit('Sign In', array('class' => 'button button-primary button-full')); ?>
        <?php echo $this->Form->end(); ?>

        <p class="auth-footer">
            New here?
            <?php echo $this->Html->link('Create an account', array('action' => 'register')); ?>
        </p>
    </div>
</section>
