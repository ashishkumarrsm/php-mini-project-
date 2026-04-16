<section class="admin-hero panel">
    <div>
        <span class="eyebrow">Dashboard</span>
        <h1>Admin control panel</h1>
        <p>Keep an eye on store health, inventory movement, and customer activity from one clean view.</p>
    </div>
    <div class="admin-hero-actions">
        <?php echo $this->Html->link('View storefront', array('controller' => 'products', 'action' => 'index', 'admin' => false), array('class' => 'button button-secondary')); ?>
        <?php echo $this->Html->link('Admin logout', array('controller' => 'users', 'action' => 'logout', 'admin' => false), array('class' => 'button button-primary')); ?>
    </div>
</section>

<section class="admin-stats-grid">
    <article class="stat-card panel">
        <span>Total orders</span>
        <strong><?php echo (int)$stats['total_orders']; ?></strong>
    </article>
    <article class="stat-card panel">
        <span>Pending orders</span>
        <strong><?php echo (int)$stats['pending_orders']; ?></strong>
    </article>
    <article class="stat-card panel">
        <span>Total revenue</span>
        <strong>$<?php echo number_format((float)$stats['total_revenue'], 2); ?></strong>
    </article>
    <article class="stat-card panel">
        <span>Total users</span>
        <strong><?php echo (int)$stats['total_users']; ?></strong>
    </article>
    <article class="stat-card panel">
        <span>Total products</span>
        <strong><?php echo (int)$stats['total_products']; ?></strong>
    </article>
    <article class="stat-card panel">
        <span>Low stock</span>
        <strong><?php echo (int)$stats['low_stock']; ?></strong>
    </article>
</section>

<section class="admin-links-grid">
    <article class="panel quick-link-card">
        <h2>Catalog</h2>
        <p>Review available products and keep the storefront catalog healthy.</p>
        <?php echo $this->Html->link('Browse shop', array('controller' => 'products', 'action' => 'index', 'admin' => false), array('class' => 'button button-secondary')); ?>
    </article>

    <article class="panel quick-link-card">
        <h2>Admin auth</h2>
        <p>Use the dedicated admin login and registration flow for protected access.</p>
        <div class="product-actions">
            <?php echo $this->Html->link('Admin login', array('controller' => 'users', 'action' => 'login', 'admin' => true), array('class' => 'button button-secondary')); ?>
            <?php echo $this->Html->link('Admin register', array('controller' => 'users', 'action' => 'register', 'admin' => true), array('class' => 'button button-primary')); ?>
        </div>
    </article>
</section>
