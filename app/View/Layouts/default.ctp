<?php
$pageTitle = !empty($title_for_layout) ? $title_for_layout . ' | MyShop' : 'MyShop';
$currentUser = !empty($currentUser) ? $currentUser : $this->Session->read('Auth.User');
$isAdminArea = !empty($isAdminArea);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo h($pageTitle); ?></title>
    <?php echo $this->Html->meta('icon'); ?>
    <?php echo $this->Html->css('app'); ?>
</head>
<body>
    <div class="page-shell">
        <header class="site-header">
            <div class="container header-bar">
                <div class="brand-group">
                    <?php echo $this->Html->link('MyShop', '/', array('class' => 'brand')); ?>
                    <span class="brand-tag">
                        <?php echo $isAdminArea ? 'Admin control center for catalog and orders' : 'Modern essentials for everyday life'; ?>
                    </span>
                </div>

                <nav class="main-nav">
                    <?php if ($isAdminArea): ?>
                        <?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action' => 'index', 'admin' => true)); ?>
                        <?php echo $this->Html->link('Products', array('controller' => 'products', 'action' => 'index', 'admin' => true)); ?>
                        <?php echo $this->Html->link('Shop Home', array('controller' => 'products', 'action' => 'index', 'admin' => false)); ?>
                    <?php else: ?>
                        <?php echo $this->Html->link('Shop', array('controller' => 'products', 'action' => 'index')); ?>
                        <?php echo $this->Html->link('Cart', array('controller' => 'carts', 'action' => 'view')); ?>
                    <?php endif; ?>

                    <?php if ($currentUser): ?>
                        <?php echo $this->Html->link('Profile', array('controller' => 'users', 'action' => 'profile', 'admin' => false)); ?>
                        <?php if (!empty($currentUser['role']) && $currentUser['role'] === 'admin'): ?>
                            <?php echo $this->Html->link('Admin', array('controller' => 'dashboard', 'action' => 'index', 'admin' => true)); ?>
                        <?php endif; ?>
                        <?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout', 'admin' => false)); ?>
                    <?php else: ?>
                        <?php if ($isAdminArea): ?>
                            <?php echo $this->Html->link('Admin Login', array('controller' => 'users', 'action' => 'login', 'admin' => true)); ?>
                            <?php echo $this->Html->link('Admin Register', array('controller' => 'users', 'action' => 'register', 'admin' => true), array('class' => 'nav-cta')); ?>
                        <?php else: ?>
                            <?php echo $this->Html->link('Login', array('controller' => 'users', 'action' => 'login')); ?>
                            <?php echo $this->Html->link('Register', array('controller' => 'users', 'action' => 'register'), array('class' => 'nav-cta')); ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </nav>
            </div>
        </header>

        <section class="top-strip">
            <div class="container top-strip-inner">
                <div>
                    <strong><?php echo $isAdminArea ? 'Admin access' : 'Fresh arrivals'; ?></strong>
                    <span>
                        <?php echo $isAdminArea ? 'Manage products, users, and order activity from one place.' : 'Clean CakePHP 2 storefront with working auth and shopping flow basics.'; ?>
                    </span>
                </div>
                <div class="top-strip-meta">
                    <?php if (!$isAdminArea): ?>
                        <span>Cart: <?php echo (int)$cartCount; ?></span>
                    <?php endif; ?>
                    <?php if (!empty($currentUser) && !$isAdminArea): ?>
                        <span>Alerts: <?php echo (int)$unreadCount; ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <main class="container site-main">
            <?php echo $this->Session->flash(); ?>
            <?php echo $content_for_layout; ?>
        </main>

        <footer class="site-footer">
            <div class="container footer-inner">
                <div>
                    <strong>MyShop</strong>
                    <p>Built on CakePHP 2 with a cleaner storefront experience.</p>
                </div>
                <p>&copy; <?php echo date('Y'); ?> MyShop</p>
            </div>
        </footer>
    </div>

    <?php echo $this->Html->script('app'); ?>
</body>
</html>
