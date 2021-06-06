<?php /**
 * @file
 *  \Contains Drupal\blockwithform\BlockWithForm
 */
namespace Drupal\blockwithform\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides Form with Name and Radio button
 */
class BlockWithForm extends FormBase{
/**
 * {@inheritdoc}
 */
    public function getFormId()
    {
        return 'blockwithform';
        
    }
    /**
     * {@inheritdoc}
     * buildForm with Name and Radio button 
     */

     public function buildForm(array $form, FormStateInterface $form_state){
     $node = \Drupal::routeMatch()->getParameter('node');
     $form['names'] = array (
         '#title' => $this->t('Enter the Name'),
         '#type' => 'textfield',
         '#size' => 25,
         '#required' => true,
     );
     $form ['selects'] = array(
         '#title' => $this->t('Select the rating B/W 1 to 5'),
         '#type' => 'radios',
         '#options' => array(5 => $this->t('Very Satisfied'),
                            4 => $this->t('Satisfied'),
                            3 => $this->t('Fair'),
                            2 => $this->t('Dissatisfied'),
                            1 => $this->t('Very Dissatisfied'),
                            ),
        '#default_value' => 5,
        '#require' => true,
                        );
    $form['submit'] = array (
        '#type' => 'submit',
        '#value' => $this->t('Submit Form'),
    );
        return $form;
     }

    /**
     * {@inheritdoc}
     * Submit Form 
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $con = \Drupal\Core\Database\Database::getConnection();
        $con->insert('blockwithform')
            ->fields(array(
                'selection' => $form_state->getValue('selects'),
                'name' => $form_state->getValue('names'),
                'created' =>time(),
            ))
            ->execute();
        \Drupal::messenger()->addStatus('Form is Working');
    }

}