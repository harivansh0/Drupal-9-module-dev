<?php
/**
 * @file
 * Contains \Druapl\rsvplist\EnablerService
 */
namespace Drupal\rsvplist;

use Drupal\Core\Database\Database;
use Drupal\node\Entity\Node;
/**
 * Define a service for manging RSVP list Enbaled nodes.
 */
class EnablerService{
    /**
     * Contructor
     */
    public function __construct()
    {
    }
    /**
     * sets a individual node to be RSVP enabled
     * 
     * @param \Drupal\node\Entity\Node $node
     */
    public function setEnabled(Node $node)
    {
        if(!$this->isEnabled($node)){
            $insert = Database::getConnection()->insert('rsvplist_enabled');
            $insert->fields(array('nid'),array($node->id()));
            $insert->execute();
        }
    }
    /**
     * Check if an individual node is RSVP enabled
     * 
     * @param \Drupal\node\Entity\Node $node
     * 
     * @return bool
     * Whether the node is enabled for the RSVP functionality.
     */
    public function isEnabled(Node $node){
        if($node->isNew()){
            return false;
        }
        $select = Database::getConnection()->select('rsvplist_enabled', 're');
        $select->fields('re', array('nid'));
        $select->condition('nid', $node->id());
        $results = $select->execute();
        return !empty($results->fetchcol());
    }
    /**
     * Deletes enabled settings for an individual node.
     * 
     * @param \Druapl\node\Entity\Node $node
     */
    public function delEnabled(Node $node){
        $delete = Database::getConnection()->delete('rsvplist_enabled');
        $delete->condition('nid',$node->id());
        $delete->execute();
    }
}