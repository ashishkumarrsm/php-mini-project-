<?php
class UsersController extends AppController
{
    public $uses = array('User', 'Order');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array('register', 'login', 'admin_login', 'admin_register'));
    }

    // !login function

    public function login()
    {
        $this->set('title_for_layout', 'Sign In');

        if ($this->request->is("post")) {
            if ($this->Auth->login()) {
                if ($this->Auth->user('role') === 'admin') {
                    return $this->redirect(array('controller' => 'dashboard', 'action' => 'index', 'admin' => true));
                }
                return $this->redirect($this->Auth->redirectUrl());
            } else {
                $this->Flash->error("Invalid username or password, try again");
            }
        }
    }

    // ! logout function 

    public function logout()
    {
        $this->Flash->success("You have been logged out.");
        return $this->redirect($this->Auth->logout());
    }


    // ! register function

    public function register()
    {
        $this->set('title_for_layout', 'Create Account');

        if ($this->request->is("post")) {
            $this->request->data['User']['role'] = 'user';
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Flash->success('Account created! Please login.');
                return $this->redirect(array('action' => 'login'));
            }
            $this->Flash->error('Could not create account. Check errors below.');
        }
    }

    public function admin_login()
    {
        $this->layout = 'default';
        $this->set('title_for_layout', 'Admin Sign In');

        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                if ($this->Auth->user('role') !== 'admin') {
                    $this->Auth->logout();
                    $this->Flash->error('This account does not have admin access.');
                    return $this->redirect(array('action' => 'login', 'admin' => true));
                }

                return $this->redirect(array(
                    'controller' => 'dashboard',
                    'action' => 'index',
                    'admin' => true
                ));
            }

            $this->Flash->error('Invalid admin username or password.');
        }
    }

    public function admin_register()
    {
        $this->layout = 'default';
        $this->set('title_for_layout', 'Admin Register');

        if ($this->request->is('post')) {
            $this->request->data['User']['role'] = 'admin';
            $this->User->create();

            if ($this->User->save($this->request->data)) {
                $this->Flash->success('Admin account created. Please sign in.');
                return $this->redirect(array('action' => 'login', 'admin' => true));
            }

            $this->Flash->error('Could not create admin account. Check the form and try again.');
        }
    }
    // !PROFILE PAGE
    public function profile()
    {
        $user = $this->User->findById($this->Auth->user('id'));
        $orders = $this->Order->find('all', array(
            'conditions' => array('Order.user_id' => $this->Auth->user('id')),
            'contain' => array('OrderItem'),
            'limit' => 5,
            'order' => array('Order.created' => 'desc')
        ));

        $lifetimeSpend = 0;
        foreach ($orders as $order) {
            $lifetimeSpend += (float)$order['Order']['total'];
        }

        $this->set(compact('user'));
        $this->set(compact('orders', 'lifetimeSpend'));
        $this->set('title_for_layout', 'Your Profile');
    }
}
