<section class="auth-shell">
    <div class="auth-copy">
        <span class="eyebrow">Create account</span>
        <h1>Join MyShop and save your shopping flow.</h1>
        <p>Register once so you can log in, manage your profile, and place orders more easily.</p>
    </div>

    <div class="auth-card panel">
        <?php echo $this->Form->create('User', array('class' => 'auth-form')); ?>
            <?php echo $this->Form->input('username', array(
                'label' => 'Username',
                'div' => false,
                'placeholder' => 'Choose a username'
            )); ?>

            <?php echo $this->Form->input('email', array(
                'label' => 'Email',
                'div' => false,
                'placeholder' => 'Enter your email'
            )); ?>

            <?php echo $this->Form->input('password', array(
                'label' => 'Password',
                'div' => false,
                'placeholder' => 'Create a password'
            )); ?>

            <?php echo $this->Form->submit('Create Account', array('class' => 'button button-primary button-full')); ?>
        <?php echo $this->Form->end(); ?>

        <p class="auth-footer">
            Already registered?
            <?php echo $this->Html->link('Sign in', array('action' => 'login')); ?>
        </p>
    </div>
</section>
