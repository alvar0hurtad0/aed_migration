<?php

namespace Drupal\aed_migration\Plugin\migrate\source;

use Drupal\migrate\Row;
use Drupal\node\Plugin\migrate\source\d7\Node;

/**
 * Drupal 7 node source from database.
 *
 * @MigrateSource(
 *   id = "node_videos"
 * )
 */
class NodeVideos extends Node {

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {

    $nid = $row->getSourceProperty('nid');
    $vid = $row->getSourceProperty('vid');

    // Body.
    $body = $this->getFieldValues('node', 'body', $nid, $vid);

    $row->setSourceProperty('body_value', $body[0]['value']);
    $row->setSourceProperty('body_format', 'full_html');
    $row->setSourceProperty('body_summary', $body[0]['summary']);

    // Taxonomy terms.
    $taxonomy_terms = [
      'field_videos_audiencia',
      'field_videos_ano',
      'field_videos_evento',
      'field_videos_idioma',
      'field_videos_nivel',
      'field_video_ponente',
      'field_videos_version',
    ];

    foreach ($taxonomy_terms as $term) {
      $value = [];
      $source_term = $this->getFieldValues('node', $term, $nid, $vid);
      foreach ($source_term as $item) {
        $value[] = $item['tid'];
      }
      $row->setSourceProperty($term, $value);
    }
    
    return parent::prepareRow($row);

  }

}
