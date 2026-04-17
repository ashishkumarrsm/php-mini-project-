<section class="panel">
    <div class="section-heading">
        <h1>Order #<?php echo (int)$order['Order']['id']; ?></h1>
        <p>Status: <?php echo h($order['Order']['status']); ?></p>
    </div>

    <div class="detail-stats">
        <div>
            <span>Total</span>
            <strong>$<?php echo number_format($order['Order']['total'], 2); ?></strong>
        </div>
        <div>
            <span>Payment</span>
            <strong><?php echo h($order['Order']['payment_method']); ?></strong>
        </div>
        <div>
            <span>Ship to</span>
            <strong><?php echo h($order['Order']['shipping_addr']); ?></strong>
        </div>
    </div>

    <div class="order-items">
        <?php foreach ($order['OrderItem'] as $item): ?>
            <article class="cart-item">
                <div>
                    <h2><?php echo h($item['Product']['name']); ?></h2>
                    <p><?php echo (int)$item['quantity']; ?> x $<?php echo number_format($item['price'], 2); ?></p>
                </div>
                <strong>$<?php echo number_format($item['quantity'] * $item['price'], 2); ?></strong>
            </article>
        <?php endforeach; ?>
    </div>

    <div class="product-actions">
        <?php echo $this->Html->link('Back to profile', array('controller' => 'users', 'action' => 'profile'), array('class' => 'button button-secondary')); ?>
        <?php echo $this->Html->link('Continue shopping', array('controller' => 'products', 'action' => 'index'), array('class' => 'button button-primary')); ?>
    </div>
</section>
