<?php
class ReviewsController extends AppController {

    public function add($product_id = null) {

        // Only POST allowed
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        // Check user logged in
        if (!$this->Auth->user('id')) {
            $this->Flash->error('Please login to submit a review.');
            return $this->redirect(array(
                'controller' => 'users',
                'action' => 'login'
            ));
        }

        // Create review
        $this->Review->create();

        $this->request->data['Review']['user_id'] = $this->Auth->user('id');
        $this->request->data['Review']['product_id'] = $product_id;

        // Optional: moderation (recommended)
        $this->request->data['Review']['is_approved'] = 0;

        if ($this->Review->save($this->request->data)) {

            $this->Flash->success(
                'Review submitted — it will appear after admin approval.'
            );

        } else {

            $this->Flash->error('Please correct the errors below.');
        }

        // Redirect back to product page
        return $this->redirect(array(
            'controller' => 'products',
            'action' => 'view',
            $product_id
        ));
    }
}