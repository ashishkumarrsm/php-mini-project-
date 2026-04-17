<?php
$currentImage = !empty($product['Product']['image']) ? $product['Product']['image'] : null;
$isEditing = !empty($product['Product']['id']);
?>

<section class="admin-auth-shell admin-product-shell">
    <div class="admin-auth-copy admin-product-copy">
        <span class="eyebrow"><?php echo $isEditing ? 'Catalog update' : 'Catalog create'; ?></span>
        <h1><?php echo h($formTitle); ?></h1>
        <p><?php echo h($formCopy); ?></p>
        <div class="admin-auth-points">
            <span>Category required</span>
            <span>Stock aware</span>
            <span>Image ready</span>
        </div>
    </div>

    <div class="admin-auth-card panel admin-form-card">
        <?php
        echo $this->Form->create('Product', array(
            'class' => 'auth-form admin-product-form',
            'type' => 'file'
        ));

        echo $this->Form->input('name', array(
            'label' => 'Product name',
            'div' => false,
            'placeholder' => 'Example: Stoneware Coffee Mug'
        ));

        echo $this->Form->input('category_id', array(
            'label' => 'Category',
            'div' => false,
            'empty' => 'Select a category',
            'options' => $categories
        ));

        echo $this->Form->input('price', array(
            'label' => 'Price',
            'div' => false,
            'step' => '0.01',
            'min' => '0',
            'placeholder' => '0.00'
        ));
        ?>

        <div class="admin-form-grid">
            <?php
            echo $this->Form->input('stock', array(
                'label' => 'Stock',
                'div' => false,
                'min' => '0',
                'placeholder' => '0'
            ));

            echo $this->Form->input('is_active', array(
                'label' => 'Status',
                'div' => false,
                'type' => 'select',
                'options' => array(
                    '1' => 'Active on storefront',
                    '0' => 'Hidden from storefront',
                )
            ));
            ?>
        </div>

        <?php
        echo $this->Form->input('description', array(
            'label' => 'Description',
            'div' => false,
            'type' => 'textarea',
            'placeholder' => 'Write a short product description that will appear in the catalog.'
        ));
        ?>

        <div class="field">
            <label for="ProductImage">Product image</label>
            <?php echo $this->Form->file('image'); ?>
            <p class="admin-field-hint">Accepted formats: JPG, PNG, WEBP.</p>
        </div>

        <?php if ($currentImage): ?>
            <div class="admin-image-preview">
                <span>Current image</span>
                <?php echo $this->Html->image('products/' . $currentImage, array('alt' => $this->request->data['Product']['name'])); ?>
            </div>
        <?php endif; ?>

        <div class="product-actions">
            <?php echo $this->Html->link('Back to products', array('action' => 'index', 'admin' => true), array('class' => 'button button-secondary')); ?>
            <?php echo $this->Form->submit($isEditing ? 'Save Changes' : 'Add Product', array('class' => 'button button-primary')); ?>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</section>
