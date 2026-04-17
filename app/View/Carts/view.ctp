<section class="panel">
    <div class="section-heading">
        <h1>Your cart</h1>
        <p>Review items before moving to checkout.</p>
    </div>

    <?php if (!empty($cart)): ?>
        <?php echo $this->Form->create(false, array('url' => array('controller' => 'carts', 'action' => 'update'))); ?>
            <div class="cart-list">
                <?php foreach ($cart as $productId => $item): ?>
                    <article class="cart-item">
                        <div>
                            <h2><?php echo h($item['name']); ?></h2>
                            <p>$<?php echo number_format($item['price'], 2); ?> each</p>
                        </div>
                        <div class="cart-actions">
                            <?php echo $this->Form->input("qty.$productId", array(
                                'label' => 'Qty',
                                'div' => false,
                                'value' => (int)$item['qty'],
                                'min' => 0
                            )); ?>
                            <?php echo $this->Form->button('Remove', array('class' => 'button button-secondary', 'name' => 'remove_id', 'value' => $productId, 'type' => 'submit')); ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <div class="checkout-bar">
                <strong>Total: $<?php echo number_format($total, 2); ?></strong>
                <div class="product-actions">
                    <?php echo $this->Form->button('Clear cart', array('class' => 'button button-secondary', 'name' => 'clear_cart', 'value' => '1', 'type' => 'submit')); ?>
                    <?php echo $this->Form->submit('Update cart', array('class' => 'button button-secondary')); ?>
                    <?php echo $this->Html->link('Checkout', array('controller' => 'orders', 'action' => 'checkout'), array('class' => 'button button-primary')); ?>
                </div>
            </div>
        <?php echo $this->Form->end(); ?>
    <?php else: ?>
        <div class="empty-state">
            <h2>Your cart is empty.</h2>
            <p>Add a few products to get started.</p>
            <?php echo $this->Html->link('Browse products', array('controller' => 'products', 'action' => 'index'), array('class' => 'button button-primary')); ?>
        </div>
    <?php endif; ?>
</section>
