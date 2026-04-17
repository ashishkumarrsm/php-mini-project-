<section class="admin-hero panel">
    <div>
        <span class="eyebrow">Product Admin</span>
        <h1>Manage the storefront catalog.</h1>
        <p>Add new products, monitor stock, and remove items that should no longer appear in the shop.</p>
    </div>
    <div class="admin-hero-actions">
        <?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action' => 'index', 'admin' => true), array('class' => 'button button-secondary')); ?>
        <?php echo $this->Html->link('Add Product', array('action' => 'add', 'admin' => true), array('class' => 'button button-primary')); ?>
    </div>
</section>

<section class="admin-stats-grid">
    <article class="stat-card panel">
        <span>Total products</span>
        <strong><?php echo (int)$stats['total']; ?></strong>
    </article>
    <article class="stat-card panel">
        <span>Live products</span>
        <strong><?php echo (int)$stats['active']; ?></strong>
    </article>
    <article class="stat-card panel">
        <span>Hidden products</span>
        <strong><?php echo (int)$stats['inactive']; ?></strong>
    </article>
    <article class="stat-card panel">
        <span>Low stock</span>
        <strong><?php echo (int)$stats['low_stock']; ?></strong>
    </article>
</section>

<?php if (!empty($products)): ?>
    <section class="panel admin-table-panel">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Catalog List</span>
                <h2>Products</h2>
            </div>
            <p><?php echo count($products); ?> item(s) on this page</p>
        </div>

        <div class="admin-product-list">
            <?php foreach ($products as $product): ?>
                <?php
                $image = !empty($product['Product']['image']) ? 'products/' . $product['Product']['image'] : null;
                $isActive = !empty($product['Product']['is_active']);
                $isLowStock = isset($product['Product']['stock']) && (int)$product['Product']['stock'] <= 5;
                $description = (string)$product['Product']['description'];
                ?>
                <article class="admin-product-row">
                    <div class="admin-product-main">
                        <div class="admin-product-thumb">
                            <?php if ($image): ?>
                                <?php echo $this->Html->image($image, array('alt' => $product['Product']['name'])); ?>
                            <?php else: ?>
                                <span>No image</span>
                            <?php endif; ?>
                        </div>

                        <div>
                            <div class="admin-product-heading">
                                <h3><?php echo h($product['Product']['name']); ?></h3>
                                <span class="admin-status-pill <?php echo $isActive ? 'is-live' : 'is-hidden'; ?>">
                                    <?php echo $isActive ? 'Live' : 'Hidden'; ?>
                                </span>
                            </div>
                            <p class="product-description"><?php echo h(strlen($description) > 120 ? substr($description, 0, 117) . '...' : $description); ?></p>
                            <div class="admin-product-meta">
                                <span>Category: <?php echo !empty($product['Category']['name']) ? h($product['Category']['name']) : 'Unassigned'; ?></span>
                                <span>Price: $<?php echo number_format((float)$product['Product']['price'], 2); ?></span>
                                <span class="<?php echo $isLowStock ? 'is-warning' : ''; ?>">Stock: <?php echo (int)$product['Product']['stock']; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="admin-row-actions">
                        <?php echo $this->Html->link('Edit', array('action' => 'edit', 'admin' => true, $product['Product']['id']), array('class' => 'button button-secondary')); ?>
                        <?php
                        echo $this->Form->postLink(
                            'Delete',
                            array('action' => 'delete', 'admin' => true, $product['Product']['id']),
                            array('class' => 'button button-primary'),
                            'Delete "' . $product['Product']['name'] . '"?'
                        );
                        ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="pagination-wrap">
            <div class="pagination-copy">Page <?php echo $this->Paginator->counter('{{page}} of {{pages}}'); ?></div>
            <div class="pagination-links">
                <?php echo $this->Paginator->prev('Previous', array('class' => 'button button-secondary'), null, array('class' => 'button button-disabled')); ?>
                <?php echo $this->Paginator->numbers(array('separator' => '')); ?>
                <?php echo $this->Paginator->next('Next', array('class' => 'button button-secondary'), null, array('class' => 'button button-disabled')); ?>
            </div>
        </div>
    </section>
<?php else: ?>
    <section class="empty-state panel">
        <span class="eyebrow">No Products Yet</span>
        <h2>Your catalog is empty.</h2>
        <p>Add the first product to get the admin catalog flowing.</p>
        <?php echo $this->Html->link('Create Product', array('action' => 'add', 'admin' => true), array('class' => 'button button-primary')); ?>
    </section>
<?php endif; ?>
