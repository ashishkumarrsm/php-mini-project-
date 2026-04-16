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

        if ($this->request->is('post')) {

            $data = $this->request->data;

            // 💰 Calculate total
            $total = array_sum(array_map(function ($i) {
                return $i['price'] * $i['qty'];
            }, $cart));

            // 🧾 Save Order
            $this->Order->create();
            $saved = $this->Order->save(array(
                'Order' => array(
                    'user_id' => $this->Auth->user('id'),
                    'total' => $total,
                    'status' => 'pending',
                    'shipping_addr' => $data['Order']['shipping_addr'],
                    'payment_method' => $data['Order']['payment_method'],
                )
            ));

            if ($saved) {

                $orderId = $this->Order->id;

                // 📦 Save items + reduce stock
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

                // 📥 Fetch full order (needed for event + email)
                $order = $this->Order->find('first', array(
                    'conditions' => array('Order.id' => $orderId),
                    'contain' => array('User', 'OrderItem' => array('Product')),
                ));

                // 🔥 FIRE EVENT (CORRECT PLACE)
                $event = new CakeEvent('Order.placed', $this, array(
                    'order' => $order,
                    'cart' => $cart
                ));
                $this->getEventManager()->dispatch($event);

                // 🧹 Clear cart
                $this->Session->delete('Cart');

                // 📧 Send email (optional: can move to listener)
                $this->_sendConfirmationEmail($orderId);

                $this->Flash->success('Order placed! Confirmation email sent.');

                return $this->redirect(array(
                    'action' => 'view',
                    $orderId
                ));
            }
        }

        // Show checkout page
        $total = array_sum(array_map(function ($i) {
            return $i['price'] * $i['qty'];
        }, $cart));

        $this->set(compact('cart', 'total'));
    }
    // Send Email
    private function _sendConfirmationEmail($orderId)
    {

        $order = $this->Order->find('first', array(
            'conditions' => array('Order.id' => $orderId),
            'contain' => array('User', 'OrderItem' => array('Product')),
        ));

        App::uses('CakeEmail', 'Network/Email');

        $email = new CakeEmail('default');
        $email->to($order['User']['email'])
            ->subject('Order Confirmation #' . $orderId)
            ->template('order_confirmation', 'default')
            ->emailFormat('html')
            ->viewVars(array('order' => $order))
            ->send();
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
    }
}