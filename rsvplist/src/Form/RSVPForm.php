<?php
/**
 * @file
 * Contains \Drupal\rsvplist\Form\RSVPForm
 */
namespace Drupal\rsvplist\Form;
use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
/**
 * Provides an RSVP Email Form.
 */
class RSVPForm extends FormBase{
    /**
     * (@inheritdoc)
     */
    public function getFormId()
    {
        return 'rsvplist_email_form';
    }

    /**
     * (@inheritdoc)
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $node = \Drupal::routeMatch()->getParameter('node');
        $nid = $node->nid->value;
        $form['email'] = array(
            '#title' => t('Email Address'),
            '#type' => 'textfield',
            '#size' => 25,
            '#description' => t("we'll send updates to email address you provide."),
            '#required' => TRUE,
        );
        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => t('RSVP'),
        );
        $form['nid']= array(
            '#type' => 'hidden',
            '#value' => $nid,
        );
        return $form;
    }

    /**
     * (@inheritdoc)
     */
    public function validateForm(array &$form, FormStateInterface $form_state){
        $value =$form_state->getValue('email');
        if($value == !\Drupal::service('email.validator')->isValid($value)){
            $form_state->setErrorByName('email', t('This email address %mail is not 
            valid.', array('%mail' => $value)));
            return ;
        }
    $node = \Drupal::routeMatch()->getParameter('node');
    //check if mail is set for this node
    $select = Database::getConnection()->select('rsvplist','r');
    $select->fields('r', array('nid'));
    $select->condition('nid',$node->id());
    $select->condition('mail',$value);
    $results = $select->execute();
    if(!empty($results->fetchCol())){
        //we found a row with this nid and email
        $form_state->setErrorByName('email', t('this address %mail is alrady subscrived to 
        this list', array('%mail'=>$value)));
    }
}
    /**
     * (@inheritdoc)
     */
    public function submitForm(array &$form, FormStateInterface $form_state){        
        $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
        db_insert('rsvplist')
            ->fields(array(
            'mail' => $form_state->getValue('email'),
            'nid' => $form_state->getValue('nid'),
            'uid' => $user->id(),
            'created' => time(),
        ))
        ->execute();
        drupal_set_message(t('Thank for your RSVP, you are on the list for the event.'));

    }
}