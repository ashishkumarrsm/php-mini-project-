<?php
class OrdersController extends AppController
{
    public $uses = array('Order', 'OrderItem', 'Product');

    public function checkout()
    {
        $cart = $this->Session->read('Cart');

        if (empty($cart)) {
            $this->Flash->error('Your cart is empty.');
            return $this->redirect(array(
                'controller' => 'carts',
                'action' => 'view'
            ));
        }

        $currentUser = $this->Auth->user();

        if ($this->request->is('post')) {
            $data = $this->request->data;
            $stockIssue = $this->_validateCartStock($cart);
            if ($stockIssue) {
                $this->Flash->error($stockIssue);
                return $this->redirect(array(
                    'controller' => 'carts',
                    'action' => 'view'
                ));
            }

            $total = array_sum(array_map(function ($i) {
                return $i['price'] * $i['qty'];
            }, $cart));

            $this->Order->create();
            $saved = $this->Order->save(array(
                'Order' => array(
                    'user_id' => $currentUser['id'],
                    'total' => $total,
                    'status' => 'pending',
                    'shipping_addr' => $data['Order']['shipping_addr'],
                    'payment_method' => $data['Order']['payment_method'],
                )
            ));

            if ($saved) {
                $orderId = $this->Order->id;

                foreach ($cart as $pid => $item) {
                    $this->OrderItem->create();
                    $this->OrderItem->save(array(
                        'OrderItem' => array(
                            'order_id' => $orderId,
                            'product_id' => $pid,
                            'quantity' => $item['qty'],
                            'price' => $item['price'],
                        )
                    ));

                    $this->Product->updateAll(
                        array('Product.stock' => 'Product.stock - ' . (int) $item['qty']),
                        array('Product.id' => $pid)
                    );
                }

                $order = $this->Order->find('first', array(
                    'conditions' => array('Order.id' => $orderId),
                    'contain' => array('User', 'OrderItem' => array('Product')),
                ));

                $event = new CakeEvent('Order.placed', $this, array(
                    'order' => $order,
                    'cart' => $cart
                ));
                $this->getEventManager()->dispatch($event);

                $this->Session->delete('Cart');

                $emailSent = $this->_sendConfirmationEmail($order);
                $this->Flash->success($emailSent ? 'Order placed successfully. Confirmation email sent.' : 'Order placed successfully.');

                return $this->redirect(array(
                    'action' => 'view',
                    $orderId
                ));
            }

            $this->Flash->error('We could not place your order. Please check the form and try again.');
        }

        $total = array_sum(array_map(function ($i) {
            return $i['price'] * $i['qty'];
        }, $cart));

        if (empty($this->request->data['Order']['shipping_addr']) && !empty($currentUser['address'])) {
            $this->request->data['Order']['shipping_addr'] = $currentUser['address'];
        }

        $this->set(compact('cart', 'total', 'currentUser'));
        $this->set('title_for_layout', 'Checkout');
    }

    private function _sendConfirmationEmail($order)
    {
        if (empty($order['User']['email'])) {
            return false;
        }

        $orderId = $order['Order']['id'];

        App::uses('CakeEmail', 'Network/Email');

        $email = new CakeEmail('default');
        $email->to($order['User']['email']);
        $email->subject('Order Confirmation #' . $orderId);
        $email->template('order_confirmation', 'default');
        $email->emailFormat('html');
        $email->viewVars(array('order' => $order));

        try {
            $email->send();
            return true;
        } catch (Exception $exception) {
            CakeLog::write('error', '[Order.email] ' . $exception->getMessage());
            return false;
        }
    }

    public function view($id = null)
    {
        $order = $this->Order->find('first', array(
            'conditions' => array(
                'Order.id' => $id,
                'Order.user_id' => $this->Auth->user('id')
            ),
            'contain' => array(
                'OrderItem' => array('Product')
            ),
        ));

        if (!$order) {
            throw new NotFoundException();
        }

        $this->set(compact('order'));
        $this->set('title_for_layout', 'Order #' . (int)$order['Order']['id']);
    }

    protected function _validateCartStock($cart)
    {
        $productIds = array_keys($cart);
        $products = $this->Product->find('all', array(
            'conditions' => array('Product.id' => $productIds),
            'fields' => array('Product.id', 'Product.name', 'Product.stock', 'Product.is_active'),
            'recursive' => -1
        ));

        $productMap = array();
        foreach ($products as $product) {
            $productMap[$product['Product']['id']] = $product['Product'];
        }

        foreach ($cart as $productId => $item) {
            if (empty($productMap[$productId])) {
                return 'One of the products in your cart is no longer available.';
            }

            $product = $productMap[$productId];
            if (empty($product['is_active'])) {
                return $product['name'] . ' is no longer available for purchase.';
            }

            if ((int)$product['stock'] < (int)$item['qty']) {
                return 'Only ' . (int)$product['stock'] . ' unit(s) of ' . $product['name'] . ' are currently available.';
            }
        }

        return null;
    }
}
