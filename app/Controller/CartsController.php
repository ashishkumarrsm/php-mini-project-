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
        $this->request->allowMethod('post');

        $product = $this->Product->findById($product_id);

        if (!$product) {
            throw new NotFoundException();
        }

        if (empty($product['Product']['is_active'])) {
            $this->Flash->error('This product is not available right now.');
            return $this->redirect(array('controller' => 'products', 'action' => 'index'));
        }

        $cart = $this->_getCart();
        $currentQty = isset($cart[$product_id]) ? (int)$cart[$product_id]['qty'] : 0;

        if ($currentQty >= (int)$product['Product']['stock']) {
            $this->Flash->error('You have reached the available stock for this product.');
            return $this->redirect($this->referer(array('controller' => 'products', 'action' => 'index')));
        }

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

            if (!empty($this->request->data['clear_cart'])) {
                $this->Session->delete('Cart');
                $this->Flash->success('Your cart has been cleared.');
                return $this->redirect(array('action' => 'view'));
            }

            if (!empty($this->request->data['remove_id'])) {
                unset($cart[$this->request->data['remove_id']]);
                $this->_saveCart($cart);
                $this->Flash->success('Item removed from cart.');
                return $this->redirect(array('action' => 'view'));
            }

            $productIds = array_keys((array)$this->request->data['qty']);
            $products = $this->Product->find('all', array(
                'conditions' => array('Product.id' => $productIds),
                'fields' => array('Product.id', 'Product.stock', 'Product.is_active'),
                'recursive' => -1
            ));
            $productMap = array();
            foreach ($products as $product) {
                $productMap[$product['Product']['id']] = $product['Product'];
            }

            foreach ($this->request->data['qty'] as $id => $qty) {
                if ((int)$qty <= 0) {
                    unset($cart[$id]);
                } else {
                    if (empty($productMap[$id]) || empty($productMap[$id]['is_active'])) {
                        unset($cart[$id]);
                        continue;
                    }

                    if ((int)$productMap[$id]['stock'] <= 0) {
                        unset($cart[$id]);
                        continue;
                    }

                    $qty = min((int)$qty, (int)$productMap[$id]['stock']);
                    $cart[$id]['qty'] = (int)$qty;
                }
            }

            $this->_saveCart($cart);
            $this->Flash->success('Your cart has been updated.');
        }

        return $this->redirect(array('action' => 'view'));
    }

    public function remove($product_id = null) {
        $this->request->allowMethod('post');

        $cart = $this->_getCart();

        unset($cart[$product_id]);

        $this->_saveCart($cart);
        $this->Flash->success('Item removed from cart.');

        return $this->redirect(array('action' => 'view'));
    }

    public function clear() {
        $this->request->allowMethod('post');
        $this->Session->delete('Cart');
        $this->Flash->success('Your cart has been cleared.');
        return $this->redirect(array('action' => 'view'));
    }
}
