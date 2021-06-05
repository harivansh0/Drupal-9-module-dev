<?php
 /**
 *@file
 Contains \Drupal\node_json\Form\NODEForm
 */
namespace Drupal\node_json\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
/**
 * Provides API NodeJsonData From  
 */
class NODEForm extends ConfigFormBase{
    /**
     *{@inheritdoc}
     */

     protected function getEditableConfigNames()
     {
         return [
             'node_json.apitext',
         ];
     }

    public function getFormId()
    {
        return 'text';
    }
    /**
     * {@inheritdoc}              
     */
    public function buildForm(array $form, FormStateInterface $form_state){
        $node = \Drupal::routeMatch()->getParameter('node');
        $nid = $node->nid->value;
        $config = $this->config('node_json.apitext');
        $form['text'] = [
            '#title' => $this-> t('Enter api Key'),
            '#type' => 'textfield',
            '#size' => '20',
            '#maxlength' => 16,
            '#description' =>$this->t('Submit API KEY'),
            '#required' => TRUE,
            '#default_value' => $config->get('text'),
        ];
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this-> t('Submit API key'),
        ];
    
        return $form;
    }
    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state){
        $this->config('node_json.apitext')->set('text', $form_state->getValue('text'))->save();
       \Drupal::messenger()->addStatus($this->t('This form is working'));
    }
}