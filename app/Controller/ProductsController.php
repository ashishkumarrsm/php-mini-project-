<?php
class ProductsController extends AppController
{
    public $components = array('Paginator');

    public function index()
    {
        $conditions = array('Product.is_active' => 1);

        if (!empty($this->request->query['q'])) {
            $q = '%' . $this->request->query['q'] . '%';
            $conditions['OR'] = array(
                'Product.name LIKE' => $q,
                'Product.description LIKE' => $q,
            );
        }

        if (!empty($this->request->query['category'])) {
            $conditions['Product.category_id'] = $this->request->query['category'];
        }

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

    public function admin_index()
    {
        $this->Paginator->settings = array(
            'contain' => array('Category'),
            'limit' => 12,
            'order' => array('Product.created' => 'desc')
        );

        $products = $this->Paginator->paginate('Product');
        $stats = array(
            'total' => (int)$this->Product->find('count'),
            'active' => (int)$this->Product->find('count', array(
                'conditions' => array('Product.is_active' => 1)
            )),
            'inactive' => (int)$this->Product->find('count', array(
                'conditions' => array('Product.is_active' => 0)
            )),
            'low_stock' => (int)$this->Product->find('count', array(
                'conditions' => array('Product.stock <=' => 5)
            )),
        );

        $this->set(compact('products', 'stats'));
        $this->set('title_for_layout', 'Manage Products');
    }

    public function admin_add()
    {
        $this->set('formTitle', 'Add Product');
        $this->set('formCopy', 'Create a new catalog item with pricing, stock, image, and storefront visibility settings.');

        if ($this->request->is('post')) {
            $this->Product->create();

            if ($this->_saveAdminProduct()) {
                $this->Flash->success('Product added successfully.');
                return $this->redirect(array('action' => 'admin_index'));
            }
        }

        $this->set('categories', $this->_getCategoryOptions());
    }

    public function admin_edit($id = null)
    {
        $product = $this->_findAdminProduct($id);
        $this->set('product', $product);
        $this->set('formTitle', 'Edit Product');
        $this->set('formCopy', 'Update details, swap the image if needed, and keep catalog information accurate.');

        if ($this->request->is(array('post', 'put'))) {
            $this->Product->id = $id;

            if ($this->_saveAdminProduct($product)) {
                $this->Flash->success('Product updated.');
                return $this->redirect(array('action' => 'admin_index'));
            }
        } else {
            $this->request->data = $product;
            $this->request->data['Product']['replace_image'] = '';
        }

        $this->set('categories', $this->_getCategoryOptions());
    }

    public function admin_delete($id = null)
    {
        $this->request->allowMethod('post');

        $product = $this->_findAdminProduct($id);

        if ($this->Product->delete($id)) {
            $this->_deleteProductImage($product);
            $this->Flash->success('Product deleted.');
        } else {
            $this->Flash->error('Product could not be deleted.');
        }

        return $this->redirect(array('action' => 'admin_index'));
    }

    protected function _findAdminProduct($id)
    {
        $product = $this->Product->find('first', array(
            'conditions' => array('Product.id' => $id),
            'contain' => array('Category')
        ));

        if (!$product) {
            throw new NotFoundException('Product not found.');
        }

        return $product;
    }

    protected function _getCategoryOptions()
    {
        return $this->Product->Category->find('list', array(
            'order' => array('Category.name' => 'asc')
        ));
    }

    protected function _saveAdminProduct($existingProduct = null)
    {
        $uploadedImage = $this->_handleProductImageUpload($existingProduct);
        if ($uploadedImage === false) {
            return false;
        }

        if ($uploadedImage !== null) {
            $this->request->data['Product']['image'] = $uploadedImage;
        } elseif ($existingProduct) {
            $this->request->data['Product']['image'] = $existingProduct['Product']['image'];
        } else {
            $this->request->data['Product']['image'] = null;
        }

        unset($this->request->data['Product']['replace_image']);

        if ($this->Product->save($this->request->data)) {
            if (
                $uploadedImage !== null &&
                $existingProduct &&
                !empty($existingProduct['Product']['image']) &&
                $existingProduct['Product']['image'] !== $uploadedImage
            ) {
                $this->_deleteProductImage($existingProduct);
            }

            return true;
        }

        if ($uploadedImage !== null) {
            $this->_deleteProductImage(array(
                'Product' => array('image' => $uploadedImage)
            ));
        }

        $this->Flash->error('Please fix the highlighted product details and try again.');
        return false;
    }

    protected function _handleProductImageUpload($existingProduct = null)
    {
        if (empty($this->request->data['Product']['image']) || !is_array($this->request->data['Product']['image'])) {
            return null;
        }

        $file = $this->request->data['Product']['image'];
        if ((int)$file['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ((int)$file['error'] !== UPLOAD_ERR_OK || empty($file['tmp_name'])) {
            $this->Flash->error('The product image could not be uploaded. Please try again.');
            return false;
        }

        $allowedExtensions = array('jpg', 'jpeg', 'png', 'webp');
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedExtensions)) {
            $this->Flash->error('Use a JPG, PNG, or WEBP image for the product.');
            return false;
        }

        $uploadDir = WWW_ROOT . 'img' . DS . 'products' . DS;
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true)) {
            $this->Flash->error('The product image folder is not writable.');
            return false;
        }

        $filename = uniqid('product_', true) . '.' . $extension;
        if (!move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
            $this->Flash->error('The product image could not be saved.');
            return false;
        }

        return $filename;
    }

    protected function _deleteProductImage($product)
    {
        if (empty($product['Product']['image'])) {
            return;
        }

        $path = WWW_ROOT . 'img' . DS . 'products' . DS . $product['Product']['image'];
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
