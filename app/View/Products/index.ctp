<section class="hero-panel">
    <div class="hero-copy">
        <span class="eyebrow">Curated Storefront</span>
        <h1>Find well-designed products without the clutter.</h1>
        <p>Browse the latest catalog, filter by category, and jump into a cleaner shopping flow.</p>
    </div>
    <div class="hero-card">
        <p>Live catalog</p>
        <strong><?php echo count($products); ?></strong>
        <span>items on this page</span>
    </div>
</section>

<section class="panel">
    <?php echo $this->Form->create(false, array('type' => 'get', 'class' => 'filters')); ?>
        <div class="field">
            <?php echo $this->Form->input('q', array(
                'label' => 'Search',
                'div' => false,
                'placeholder' => 'Search by product name or description',
                'value' => isset($this->request->query['q']) ? $this->request->query['q'] : ''
            )); ?>
        </div>

        <div class="field">
            <?php echo $this->Form->input('category', array(
                'label' => 'Category',
                'div' => false,
                'empty' => 'All categories',
                'options' => $categories,
                'default' => isset($this->request->query['category']) ? $this->request->query['category'] : ''
            )); ?>
        </div>

        <div class="actions">
            <?php echo $this->Form->submit('Apply', array('class' => 'button button-primary')); ?>
            <?php echo $this->Html->link('Reset', array('controller' => 'products', 'action' => 'index'), array('class' => 'button button-secondary')); ?>
        </div>
    <?php echo $this->Form->end(); ?>
</section>

<?php if (!empty($products)): ?>
    <section class="product-grid">
        <?php foreach ($products as $product): ?>
            <?php
            $image = !empty($product['Product']['image']) ? '/img/products/' . h($product['Product']['image']) : '/img/cake.icon.png';
            $categoryName = !empty($product['Category']['name']) ? $product['Category']['name'] : 'General';
            $description = !empty($product['Product']['description']) ? $product['Product']['description'] : 'No description available yet.';
            if (strlen($description) > 110) {
                $description = substr($description, 0, 107) . '...';
            }
            ?>
            <article class="product-card">
                <div class="product-media">
                    <img src="<?php echo $image; ?>" alt="<?php echo h($product['Product']['name']); ?>">
                </div>
                <div class="product-body">
                    <span class="product-category"><?php echo h($categoryName); ?></span>
                    <h2><?php echo h($product['Product']['name']); ?></h2>
                    <p class="product-description">
                        <?php echo h($description); ?>
                    </p>
                    <div class="product-meta">
                        <strong>$<?php echo number_format($product['Product']['price'], 2); ?></strong>
                        <span><?php echo (int)$product['Product']['stock']; ?> in stock</span>
                    </div>
                    <div class="product-actions">
                        <?php echo $this->Html->link(
                            'View details',
                            array('action' => 'view', $product['Product']['slug']),
                            array('class' => 'button button-primary')
                        ); ?>
                        <?php echo $this->Form->postLink(
                            'Add to cart',
                            array('controller' => 'carts', 'action' => 'add', $product['Product']['id']),
                            array('class' => 'button button-secondary')
                        ); ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </section>

    <div class="pagination-wrap panel">
        <div class="pagination-links">
            <?php echo $this->Paginator->prev('Previous', array('class' => 'button button-secondary'), null, array('class' => 'button button-disabled')); ?>
            <?php echo $this->Paginator->numbers(array('separator' => ' ')); ?>
            <?php echo $this->Paginator->next('Next', array('class' => 'button button-secondary'), null, array('class' => 'button button-disabled')); ?>
        </div>
        <p class="pagination-copy">
            <?php echo $this->Paginator->counter('Page {:page} of {:pages} | {:count} products total'); ?>
        </p>
    </div>
<?php else: ?>
    <section class="empty-state panel">
        <h2>No products matched your search.</h2>
        <p>Try a different keyword or clear the filters to see the full catalog.</p>
        <?php echo $this->Html->link('View all products', array('controller' => 'products', 'action' => 'index'), array('class' => 'button button-primary')); ?>
    </section>
<?php endif; ?>
