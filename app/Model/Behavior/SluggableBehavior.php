<?php
App::uses('ModelBehavior', 'Model');

class SluggableBehavior extends ModelBehavior {

    public function beforeSave(Model $model, $options = array()) {

        // Generate slug only if empty
        if (!empty($model->data[$model->alias]['name']) &&
            empty($model->data[$model->alias]['slug'])) {

            $slug = $this->_slugify(
                $model->data[$model->alias]['name']
            );

            // Ensure unique slug
            $slug = $this->_makeUnique($model, $slug);

            $model->data[$model->alias]['slug'] = $slug;
        }

        return true;
    }

    // 🔹 Convert text to slug
    protected function _slugify($text) {

        $text = mb_strtolower(trim($text), 'UTF-8');

        // Replace non-alphanumeric with hyphen
        $text = preg_replace('/[^a-z0-9]+/u', '-', $text);

        // Remove duplicate hyphens
        $text = preg_replace('/-+/', '-', $text);

        return trim($text, '-');
    }

    // 🔥 Ensure unique slug
    protected function _makeUnique(Model $model, $slug) {

        $originalSlug = $slug;
        $i = 1;

        while ($model->find('count', array(
            'conditions' => array($model->alias . '.slug' => $slug)
        ))) {
            $slug = $originalSlug . '-' . $i;
            $i++;
        }

        return $slug;
    }
}