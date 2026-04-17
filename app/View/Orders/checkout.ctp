<section class="panel checkout-layout">
    <div>
        <div class="section-heading">
            <h1>Checkout</h1>
            <p>Review your delivery details and place the order with a final stock check.</p>
        </div>

        <div class="panel checkout-customer-card">
            <span class="eyebrow">Signed In As</span>
            <h2><?php echo h($currentUser['username']); ?></h2>
            <?php if (!empty($currentUser['email'])): ?>
                <p><?php echo h($currentUser['email']); ?></p>
            <?php endif; ?>
        </div>

        <?php echo $this->Form->create('Order', array('class' => 'auth-form')); ?>
            <?php echo $this->Form->input('shipping_addr', array(
                'label' => 'Shipping address',
                'type' => 'textarea',
                'div' => false,
                'placeholder' => 'Street, city, state, ZIP'
            )); ?>

            <?php echo $this->Form->input('payment_method', array(
                'label' => 'Payment method',
                'div' => false,
                'type' => 'select',
                'empty' => 'Choose payment method',
                'options' => array(
                    'cod' => 'Cash on delivery',
                    'card' => 'Card on delivery'
                )
            )); ?>

            <?php echo $this->Form->submit('Place order', array('class' => 'button button-primary button-full')); ?>
        <?php echo $this->Form->end(); ?>
    </div>

    <aside class="panel order-summary">
        <h2>Order summary</h2>
        <?php foreach ($cart as $item): ?>
            <div class="summary-line">
                <span><?php echo h($item['name']); ?> x <?php echo (int)$item['qty']; ?></span>
                <strong>$<?php echo number_format($item['price'] * $item['qty'], 2); ?></strong>
            </div>
        <?php endforeach; ?>
        <div class="summary-line total-line">
            <span>Total</span>
            <strong>$<?php echo number_format($total, 2); ?></strong>
        </div>
        <div class="product-actions">
            <?php echo $this->Html->link('Back to cart', array('controller' => 'carts', 'action' => 'view'), array('class' => 'button button-secondary')); ?>
        </div>
    </aside>
</section>
