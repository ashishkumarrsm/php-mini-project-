<?php
class DashboardController extends AppController {

    public $uses = array('Order', 'User', 'Product');

    public function admin_index() {
        $this->set('title_for_layout', 'Admin Dashboard');
        $stats = array(
            'total_orders' => $this->Order->find('count'),

            'pending_orders' => $this->Order->find('count', array(
                'conditions' => array('Order.status' => 'pending')
            )),

            'total_revenue' => $this->Order->field('SUM(total)', array(
                'Order.status !=' => 'cancelled'
            )),

            'total_users' => $this->User->find('count'),

            'total_products' => $this->Product->find('count'),

            'low_stock' => $this->Product->find('count', array(
                'conditions' => array('Product.stock <' => 5)
            )),
        );

        $this->set(compact('stats'));
    }
}
