<?php
class Review extends AppModel {

    public $belongsTo = array(
        'Product',
        'User'
    );

    public $validate = array(

        // ⭐ Rating (1–5)
        'rating' => array(
            'validRange' => array(
                'rule' => array('range', 0, 6),
                'message' => 'Rating must be between 1 and 5'
            )
        ),

        // 📝 Review Body
        'body' => array(
            'minLength' => array(
                'rule' => array('minLength', 10),
                'message' => 'Review must be at least 10 characters long'
            )
        ),

        // 🏷 Title
        'title' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 150),
                'message' => 'Title must be less than 150 characters'
            )
        )
    );
}