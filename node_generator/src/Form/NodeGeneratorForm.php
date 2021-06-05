<?php
/**
 *  /Drupal/node_generator/Form/NodeGenratorForm
 * @file
 */
namespace Drupal\node_generator\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use \Drupal\node\Entity\Node;
use \Drupal\file\Entity\File;

/**
 * Provide Node Generator form
 */
class NodeGeneratorForm extends FormBase{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'node_generator';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state){
        $node_types = \Drupal\node\Entity\NodeType::loadMultiple();
        $options= array();
        foreach( $node_types as $node_type){
            $options[$node_type->id()] = $node_type->label();
        }

        $form['title'] = array(
            '#title' => $this->t('Title'),
            '#type' => 'textfield',
            '#required' => true,
        ); 
        $form['nodes'] = array(
            '#title' => $this-> t('No. of Nodes'),
            '#type' => 'number',
            '#min' => 2,
            '#max' => 10,
            '#required' => true,
        );
        $form['content'] = array(
            '#type' => 'select',
            '#title' => $this->t('Content types'),
            '#options' => $options,
        );
        $form['submit'] = array (
            '#value' => $this->t('Submit node'),
            '#type' => 'submit',
        );
        return $form;

    }
    
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        $no_of_nodes= $form_state->getvalue('nodes');
        if( $no_of_nodes < 2 || $no_of_nodes > 10){
            $form_state->setErrorByName('node','node should be b/w 2 to 10');
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state){
        $node_value = $form_state->getvalue('nodes');
        if($node_value >= 2 && $node_value <=10){
            for( $i=0; $i<intval($node_value); $i++){
                $node= Node::create(['type' => $form_state->getvalue('content')]);
                $node->set('title', $form_state->getvalue('title'));
                $node->enforceIsNew();
                $node->save();


            }
        }
        
        \Drupal::messenger()->addStatus($this->t('Node Genrator Working'));

    }
}