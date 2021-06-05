<?php 
 /**
  * created Controller to get key
  */
namespace Drupal\node_json\Controller;

use Drupal\Core\Controller\ControllerBase;;
use Symfony\Component\HttpFoundation\JsonResponse;

class nodeController extends ControllerBase{
     /**
      * it willv return the api key
      */
      public function getKey($apikey, $node_id){
          $node= \Drupal::entityQuery('node')->condition('nid', $node_id)->execute();
          $node_id = !empty($node);
          $key = \Drupal::config('node_json.apitext')->get('text');
          if($apikey == $key && $node_id){
              return new JsonResponse(
                [
                  '#type' => 'markup',
                  '#markup' => $this->t('apikey : '. $key),
                  'method' => 'GET',
              ]
              );
          }
          else {
              return(
            [
              '#type' => 'markup',
              '#markup' => $this->t('Invalid key:'.$node_id)
            ]
              );
          }
      }
     
}