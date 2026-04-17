<?php
App::uses('AppModel', 'Model');

class OrderItem extends AppModel
{
    public $belongsTo = array(
        'Order' => array(
            'className' => 'Order',
            'foreignKey' => 'order_id'
        ),
        'Product' => array(
            'className' => 'Product',
            'foreignKey' => 'product_id'
        ),
    );
}
