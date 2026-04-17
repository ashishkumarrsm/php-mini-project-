<section class="panel profile-card">
    <span class="eyebrow">Your account</span>
    <h1><?php echo h($user['User']['username']); ?></h1>
    <p>Keep your account details handy while you shop.</p>

    <div class="detail-stats">
        <div>
            <span>Email</span>
            <strong><?php echo h($user['User']['email']); ?></strong>
        </div>
        <div>
            <span>Role</span>
            <strong><?php echo !empty($user['User']['role']) ? h($user['User']['role']) : 'customer'; ?></strong>
        </div>
        <div>
            <span>Member ID</span>
            <strong>#<?php echo (int)$user['User']['id']; ?></strong>
        </div>
        <div>
            <span>Recent orders</span>
            <strong><?php echo count($orders); ?></strong>
        </div>
        <div>
            <span>Recent spend</span>
            <strong>$<?php echo number_format($lifetimeSpend, 2); ?></strong>
        </div>
    </div>
</section>

<section class="panel">
    <div class="section-heading">
        <div>
            <span class="eyebrow">Order Activity</span>
            <h2>Recent orders</h2>
        </div>
        <p>Your latest purchases in one place.</p>
    </div>

    <?php if (!empty($orders)): ?>
        <div class="account-order-list">
            <?php foreach ($orders as $order): ?>
                <article class="account-order-card">
                    <div class="account-order-top">
                        <div>
                            <h3>Order #<?php echo (int)$order['Order']['id']; ?></h3>
                            <p><?php echo !empty($order['Order']['created']) ? date('M j, Y', strtotime($order['Order']['created'])) : 'Recent order'; ?></p>
                        </div>
                        <span class="admin-status-pill <?php echo $order['Order']['status'] === 'pending' ? 'is-hidden' : 'is-live'; ?>">
                            <?php echo h(ucfirst($order['Order']['status'])); ?>
                        </span>
                    </div>

                    <div class="admin-product-meta">
                        <span>Total: $<?php echo number_format((float)$order['Order']['total'], 2); ?></span>
                        <span>Items: <?php echo count($order['OrderItem']); ?></span>
                    </div>

                    <div class="product-actions">
                        <?php echo $this->Html->link('View order', array('controller' => 'orders', 'action' => 'view', $order['Order']['id']), array('class' => 'button button-primary')); ?>
                        <?php echo $this->Html->link('Shop more', array('controller' => 'products', 'action' => 'index'), array('class' => 'button button-secondary')); ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <h2>No orders yet.</h2>
            <p>Once you place an order, it will show up here for quick tracking.</p>
            <?php echo $this->Html->link('Start shopping', array('controller' => 'products', 'action' => 'index'), array('class' => 'button button-primary')); ?>
        </div>
    <?php endif; ?>
</section>
