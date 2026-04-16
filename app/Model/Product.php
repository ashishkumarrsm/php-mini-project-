<?php
class Product extends AppModel
{

    public $belongsTo = array(
        'Category' => array(
            'className' => 'Category',
            'foreignKey' => 'category_id'
        ),
    );

    public $hasMany = array(
        'Review' => array(
            'className' => 'Review',
            'foreignKey' => 'product_id'
        ),
        'OrderItem' => array(
            'className' => 'OrderItem',
            'foreignKey' => 'product_id'
        ),
    );

    public $actsAs = array('Containable', 'Sluggable'); // custom behavior

    public $validate = array(
        'name' => array(
            'rule' => 'notBlank',
            'message' => 'Product name required'
        ),
        'price' => array(
            'rule' => 'numeric',
            'message' => 'Price must be a number'
        ),
        'category_id' => array(
            'rule' => 'numeric',
            'message' => 'Select a category'
        ),
        'stock' => array(
            'rule' => array('comparison', '>=', 0),
            'message' => 'Stock cannot be negative'
        ),
    );
}