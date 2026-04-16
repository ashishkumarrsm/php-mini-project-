<?php
App::uses('Controller', 'Controller');
App::uses('CakeEvent', 'Event');

class AppController extends Controller
{
    public $components = array(
        'Session',
        'Flash',
        'Auth' => array(
            'loginRedirect' => array('controller' => 'products', 'action' => 'index'),
            'logoutRedirect' => array('controller' => 'products', 'action' => 'index'),
            'authError' => 'You must be logged in to view this page.',
            'authenticate' => array(
                'Form' => array(
                    'userModel' => 'User',
                    'passwordHasher' => 'Blowfish'
                )
            )
        )
    );

    public function beforeFilter()
    {
        $this->Auth->allow('index', 'view', 'login', 'register', 'admin_login', 'admin_register');

        $isAdminPrefix = isset($this->request->params['prefix']) && $this->request->params['prefix'] === 'admin';
        $isAdminAuthPage = (
            $this->request->params['controller'] === 'users' &&
            in_array($this->request->params['action'], array('admin_login', 'admin_register'))
        );

        if ($isAdminPrefix && !$isAdminAuthPage && $this->Auth->user('role') !== 'admin') {
            $this->Flash->error('You are not authorized to access this page.');
            return $this->redirect(array('controller' => 'users', 'action' => 'login', 'admin' => true));
        }

        $cart = (array)$this->Session->read('Cart');
        $cartCount = 0;
        foreach ($cart as $item) {
            $cartCount += isset($item['qty']) ? (int)$item['qty'] : 0;
        }

        $unreadCount = 0;
        if ($this->Auth->user('id') && App::objects('Model', null, false) && in_array('Notification', App::objects('Model'))) {
            $Notification = ClassRegistry::init('Notification');
            if ($Notification) {
                $unreadCount = (int)$Notification->find('count', array(
                    'conditions' => array(
                        'Notification.user_id' => $this->Auth->user('id'),
                        'Notification.is_read' => 0
                    )
                ));
            }
        }

        $this->set(compact('cartCount', 'unreadCount'));
        $this->set('currentUser', $this->Auth->user());
        $this->set('isAdminArea', $isAdminPrefix);

        $this->getEventManager()->attach(array($this, 'onOrderPlaced'), 'Order.placed');
    }

    public function onOrderPlaced(CakeEvent $event)
    {
        if (empty($event->data['order']['Order']['id'])) {
            return;
        }

        $order = $event->data['order'];
        CakeLog::write(
            'info',
            '[Order.placed] Order #' . $order['Order']['id'] . ' by user ' . $order['Order']['user_id']
        );
    }

    protected function _notify($userId, $message)
    {
        if (!in_array('Notification', App::objects('Model'))) {
            return false;
        }

        $Notification = ClassRegistry::init('Notification');
        $Notification->create();

        return (bool)$Notification->save(array(
            'Notification' => array(
                'user_id' => $userId,
                'message' => $message
            )
        ));
    }
}
