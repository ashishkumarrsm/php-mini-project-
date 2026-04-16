<?php
class CartsController extends AppController {

    public $uses = array('Product');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('add','view','update','remove','clear'));
    }

    private function _getCart() {
        return $this->Session->read('Cart') ?: array();
    }

    private function _saveCart($cart) {
        $this->Session->write('Cart', $cart);
    }

    public function view() {
        $cart = $this->_getCart();

        $total = array_sum(array_map(function($i){
            return $i['price'] * $i['qty'];
        }, $cart));

        $this->set(compact('cart', 'total'));
    }

    public function add($product_id = null) {
        $product = $this->Product->findById($product_id);

        if (!$product) {
            throw new NotFoundException();
        }

        $cart = $this->_getCart();

        if (isset($cart[$product_id])) {
            $cart[$product_id]['qty']++;
        } else {
            $cart[$product_id] = array(
                'name'  => $product['Product']['name'],
                'price' => $product['Product']['price'],
                'qty'   => 1,
                'image' => $product['Product']['image'],
            );
        }

        $this->_saveCart($cart);

        $this->Flash->success('Item added to cart!');
        return $this->redirect($this->referer());
    }

    public function update() {
        if ($this->request->is('post')) {

            $cart = $this->_getCart();

            foreach ($this->request->data['qty'] as $id => $qty) {
                if ((int)$qty <= 0) {
                    unset($cart[$id]);
                } else {
                    $cart[$id]['qty'] = (int)$qty;
                }
            }

            $this->_saveCart($cart);
        }

        return $this->redirect(array('action' => 'view'));
    }

    public function remove($product_id = null) {
        $cart = $this->_getCart();

        unset($cart[$product_id]);

        $this->_saveCart($cart);

        return $this->redirect(array('action' => 'view'));
    }

    public function clear() {
        $this->Session->delete('Cart');
        return $this->redirect(array('action' => 'view'));
    }
}