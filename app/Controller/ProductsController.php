<?php
class ProductsController extends AppController
{
    public $components = array('Paginator');

    // ===============================
    // PUBLIC: PRODUCT LIST + SEARCH
    // ===============================
    public function index()
    {
        $conditions = array('Product.is_active' => 1);

        // 🔍 Search
        if (!empty($this->request->query['q'])) {
            $q = '%' . $this->request->query['q'] . '%';

            $conditions['OR'] = array(
                'Product.name LIKE' => $q,
                'Product.description LIKE' => $q,
            );
        }

        // 📂 Category filter (optional)
        if (!empty($this->request->query['category'])) {
            $conditions['Product.category_id'] = $this->request->query['category'];
        }

        // ⚙️ Pagination
        $this->Paginator->settings = array(
            'conditions' => $conditions,
            'contain' => array('Category'),
            'limit' => 12,
            'order' => array('Product.created' => 'desc'),
        );

        $products = $this->Paginator->paginate('Product');
        $categories = $this->Product->Category->find('list');
        $this->set(compact('products', 'categories'));
        $this->set('title_for_layout', 'Discover Products');
    }

    // ===============================
    // PUBLIC: PRODUCT VIEW
    // ===============================
    public function view($slug = null)
    {
        $product = $this->Product->find('first', array(
            'conditions' => array(
                'Product.slug' => $slug,
                'Product.is_active' => 1
            ),
            'contain' => array(
                'Category',
                'Review' => array('User')
            ),
        ));

        if (!$product) {
            throw new NotFoundException('Product not found');
        }

        $this->set(compact('product'));



        // 🔹 Product already fetched above

        $ratingData = $this->Product->Review->find('first', array(
            'fields' => array(
                'AVG(Review.rating) AS avg_rating',
                'COUNT(Review.id) AS review_count'
            ),
            'conditions' => array(
                'Review.product_id' => $product['Product']['id'],
                'Review.is_approved' => 1
            ),
            'group' => 'Review.product_id'
        ));

        $this->set(compact('product', 'ratingData'));
        $this->set('title_for_layout', $product['Product']['name']);
    }

    // ===============================
    // ADMIN: ADD PRODUCT
    // ===============================
    public function add()
    {
        $this->Auth->deny(); // only logged-in users

        if ($this->request->is('post')) {

            $this->Product->create();
            $file = $this->request->data['Product']['image'];

            if (!empty($file['name'])) {

                $uploadDir = WWW_ROOT . 'img' . DS . 'products' . DS;

                // Create folder if not exists
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $allowed = array('image/jpeg', 'image/png', 'image/webp');

                if (!in_array($file['type'], $allowed)) {
                    $this->Flash->error('Only JPG, PNG, WEBP allowed.');
                } else {
                    $filename = uniqid() . '-' . basename($file['name']);

                    move_uploaded_file(
                        $file['tmp_name'],
                        $uploadDir . $filename
                    );

                    $this->request->data['Product']['image'] = $filename;
                }
            }

            if ($this->Product->save($this->request->data)) {
                $this->Flash->success('Product added successfully.');
                return $this->redirect(array('action' => 'index'));
            }
        }

        $categories = $this->Product->Category->find('list');
        $this->set(compact('categories'));
    }

    // ===============================
    // ADMIN: EDIT PRODUCT
    // ===============================
    public function edit($id = null)
    {
        $product = $this->Product->findById($id);

        if (!$product) {
            throw new NotFoundException();
        }

        if ($this->request->is(array('post', 'put'))) {

            if ($this->Product->save($this->request->data)) {
                $this->Flash->success('Product updated.');
                return $this->redirect(array('action' => 'index'));
            }
        }

        $this->request->data = $product;

        $categories = $this->Product->Category->find('list');
        $this->set(compact('categories'));
    }

    // ===============================
    // ADMIN: DELETE PRODUCT
    // ===============================
    public function delete($id = null)
    {
        $this->request->allowMethod('post');

        if ($this->Product->delete($id)) {
            $this->Flash->success('Product deleted.');
        }

        return $this->redirect(array('action' => 'index'));
    }

    // ===============================
    // ADMIN PREFIX METHODS
    // ===============================

    public function admin_index()
    {
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('Product.created' => 'desc')
        );

        $products = $this->Paginator->paginate('Product');
        $this->set(compact('products'));
    }

    public function admin_edit($id = null)
    {
        $product = $this->Product->findById($id);

        if (!$product) {
            throw new NotFoundException();
        }

        if ($this->request->is(array('post', 'put'))) {

            if ($this->Product->save($this->request->data)) {
                $this->Flash->success('Product updated.');
                return $this->redirect(array('action' => 'admin_index'));
            }
        }

        $this->request->data = $product;

        $categories = $this->Product->Category->find('list');
        $this->set(compact('categories'));
    }

    public function admin_delete($id = null)
    {
        $this->request->allowMethod('post');

        if ($this->Product->delete($id)) {
            $this->Flash->success('Product deleted.');
        }

        return $this->redirect(array('action' => 'admin_index'));
    }
}
