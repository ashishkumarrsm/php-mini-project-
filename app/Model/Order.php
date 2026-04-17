<?php
App::uses('AppModel', 'Model');

class Order extends AppModel
{
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        ),
    );

    public $hasMany = array(
        'OrderItem' => array(
            'className' => 'OrderItem',
            'foreignKey' => 'order_id',
            'dependent' => true
        ),
    );

    public $actsAs = array('Containable');

    public $validate = array(
        'shipping_addr' => array(
            'rule' => 'notBlank',
            'message' => 'Shipping address is required.'
        ),
        'payment_method' => array(
            'rule' => 'notBlank',
            'message' => 'Choose a payment method.'
        ),
    );
}
