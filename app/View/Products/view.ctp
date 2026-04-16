<?php
$image = !empty($product['Product']['image']) ? '/img/products/' . h($product['Product']['image']) : '/img/cake.icon.png';
$avgRating = !empty($ratingData[0]['avg_rating']) ? number_format($ratingData[0]['avg_rating'], 1) : 'New';
$reviewCount = !empty($ratingData[0]['review_count']) ? (int)$ratingData[0]['review_count'] : 0;
?>

<section class="product-detail panel">
    <div class="detail-media">
        <img src="<?php echo $image; ?>" alt="<?php echo h($product['Product']['name']); ?>">
    </div>

    <div class="detail-copy">
        <span class="eyebrow"><?php echo !empty($product['Category']['name']) ? h($product['Category']['name']) : 'Featured'; ?></span>
        <h1><?php echo h($product['Product']['name']); ?></h1>
        <p class="detail-description"><?php echo h($product['Product']['description']); ?></p>

        <div class="detail-stats">
            <div>
                <span>Price</span>
                <strong>$<?php echo number_format($product['Product']['price'], 2); ?></strong>
            </div> 
            <div>
                <span>Rating</span>
                <strong><?php echo h($avgRating); ?></strong>
            </div>
            <div>  
                <span>Reviews </span>
                <strong><?php echo $reviewCount; ?></strong>
            </div>
            <div>
                <span>Stock</span>
                <strong><?php echo (int)$product['Product']['stock']; ?></strong>
            </div>
        </div>

        <div class="product-actions">
            <?php echo $this->Html->link(
                'Add to cart',
                array('controller' => 'carts', 'action' => 'add', $product['Product']['id']),
                array('class' => 'button button-primary')
            ); ?>
            <?php echo $this->Html->link(
                'Back to shop',
                array('controller' => 'products', 'action' => 'index'),
                array('class' => 'button button-secondary')
            ); ?>
        </div>
    </div>
</section>

<?php if (!empty($product['Review'])): ?>
    <section class="panel review-list">
        <div class="section-heading">
            <h2>Customer reviews</h2>
            <p>Feedback from shoppers who bought this product.</p>
        </div>

        <?php foreach ($product['Review'] as $review): ?>
            <?php if (!empty($review['is_approved']) || !array_key_exists('is_approved', $review)): ?>
                <article class="review-card">
                    <div class="review-top">
                        <strong><?php echo h($review['title']); ?></strong>
                        <span><?php echo (int)$review['rating']; ?>/5</span>
                    </div>
                    <p><?php echo h($review['body']); ?></p>
                </article>
            <?php endif; ?>
        <?php endforeach; ?>
    </section>
<?php endif; ?>
