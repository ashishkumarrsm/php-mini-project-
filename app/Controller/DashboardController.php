<?php
App::uses('AppController', 'Controller');

class DashboardController extends AppController {

    public $components = array('Auth', 'Session', 'Flash');

    public function beforeFilter() {
        parent::beforeFilter();
    }

    // 👉 ADMIN DASHBOARD PAGE
    public function admin_index() {
        $this->set('title', 'Admin Dashboard');
    }
}   