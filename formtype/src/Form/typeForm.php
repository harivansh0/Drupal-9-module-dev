<?php
/**
 * @file
 * /Drupal/formtype/Form/typeForm
 */

namespace Drupal\formtype\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use \Drupal\node\Entity\Node;
use \Drupal\file\Entity\File;

/*
 * Provide Form with title, DropDown menu, with Number of nodes, Body
 * {@inheritdoc}
 */

 class typeForm extends FormBase{
    /**
     *  return form id
     * {@inheritdoc}
     */

     public function getFormId(){
         return 'formty[e';
     }

     /**
      * Form build with DropDown , title , node of nodes field, and body
      *{@inheritdoc}
      */
     public function buildForm(array $form, FormStateInterface $form_state){
       $nids = \Drupal::entityQuery('node')->condition('type', 'my_custom_type')->execute();
        $node_types = \Drupal\node\Entity\NodeType::loadMultiple();
        $options = array();
        foreach($node_types as $node_type){
            $options[$node_type->id()] = $node_type->label();
        }
     $form['title'] = array (
         '#title' => $this->t('Enter the title'),
         '#type' => 'textfield',
         '#required' => true,
     );
     $form['content'] = array(
         '#title' => $this->t('Select Content type'),
         '#type' => 'select',
         '#options' => $options,
     );
     $form['nodes'] = array(
         '#title' => $this->t('Enter the no of nodes'),
         '#type' => 'number',
         '#max' => 6,
         '#required' => true,
     );
     $form['textarea']= array(
         '#type' => 'textarea',
         '#title' => $this->t('Body'),
     );
     $form['submit'] = array (
         '#value' => $this->t('Submit Form'),
         '#type' => 'submit',
     ) ;
     return $form;
     
    }
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        $no_of_nodes = $form_state->getvalue('nodes');
        if( $no_of_nodes > 6){
            $form_state->setErrorByName('node','please select b/w 0 to 5');
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state ){
        $no_of_nodes = $form_state->getvalue('nodes');
        if($no_of_nodes <= 5){
            for($i=0; $i<$no_of_nodes; $i++){
                $node = Node::create(['type' =>$form_state->getvalue('content')]);
                $node->set('title',$form_state->getvalue('title'));
                $node->set('body',[ 'value' => $form_state->getvalue('tetxarea'),'format'=>'basic_html',]);
                $node->enforceIsNew();
                $node->save();
            }
        }
        \Drupal::messenger()->addStatus($no_of_nodes.'Form is Working');
    }
 }