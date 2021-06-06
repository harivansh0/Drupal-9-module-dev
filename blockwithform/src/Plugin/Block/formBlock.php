<?php
/**
 * @file
 * contains \Drupal\rsvplist\Plugin\Block\RSVPBlock
 */
namespace Drupal\blockwithform\Plugin\Block;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;

/**
 * Provide an 'Blockwithform' List block
 * @Block(
 *   id= "blockwithform_block",
 *   admin_label = @Translation("blockwithform")
 * )
 */
class formBlock extends BlockBase{
/**
 * {@inheritdoc}
 */
public function build(){
        return \Drupal::formBuilder()->getForm('Drupal\blockwithform\Form\BlockWithForm');
    }
    

}