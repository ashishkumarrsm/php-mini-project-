<?php
class Category extends AppModel
{

    public $actsAs = array('Tree'); // MPTT structure

    public $belongsTo = array(
        'Parent' => array(
            'className' => 'Category',
            'foreignKey' => 'parent_id'
        ),
    );

    public $hasMany = array(
        'Children' => array(
            'className' => 'Category',
            'foreignKey' => 'parent_id'
        ),
        'Product' => array(
            'className' => 'Product',
            'foreignKey' => 'category_id'
        ),
    );
}