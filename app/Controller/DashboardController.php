<?php
App::uses('AppController', 'Controller');

class DashboardController extends AppController {

    public $uses = array('Order', 'User', 'Product');

    public function beforeFilter() {
        parent::beforeFilter();
    }

    public function admin_index() {
        $stats = array(
            'total_orders' => (int)$this->Order->find('count'),
            'pending_orders' => (int)$this->Order->find('count', array(
                'conditions' => array('Order.status' => 'pending')
            )),
            'total_revenue' => (float)$this->Order->field('SUM(total)', array(
                'Order.status !=' => 'cancelled'
            )),
            'total_users' => (int)$this->User->find('count'),
            'total_products' => (int)$this->Product->find('count'),
            'low_stock' => (int)$this->Product->find('count', array(
                'conditions' => array('Product.stock <' => 5)
            )),
        );

        $this->set(compact('stats'));
        $this->set('title_for_layout', 'Admin Dashboard');
    }
}
