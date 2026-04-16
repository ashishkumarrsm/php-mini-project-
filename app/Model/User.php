<?php
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class User extends AppModel
{

    public $validate = array(
        'username' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Username required'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'Username already taken'
            ),
            'length' => array(
                'rule' => array('minLength', 3),
                'message' => 'Minimum 3 characters'
            ),
        ),

        'email' => array(
            'valid' => array(
                'rule' => 'email',
                'message' => 'Enter a valid email'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'Email already registered'
            ),
        ),

        'password' => array(
            'required' => array(
                'rule' => array('minLength', 6),
                'message' => 'Minimum 6 characters'
            ),
        ),
    );

    // Automatically hash password before save
    public function beforeSave($options = array())
    {
        if (!empty($this->data[$this->alias]['password'])) {
            $hasher = new BlowfishPasswordHasher();
            $this->data[$this->alias]['password'] =
                $hasher->hash($this->data[$this->alias]['password']);
        }
        return true;
    }
}