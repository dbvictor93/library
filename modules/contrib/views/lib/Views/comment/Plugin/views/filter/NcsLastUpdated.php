<?php

/**
 * @file
 * Definition of Views\comment\Plugin\views\filter\NcsLastUpdated.
 */

namespace Views\comment\Plugin\views\filter;

use Drupal\views\Plugin\views\filter\Date;
use Drupal\Core\Annotation\Plugin;

/**
 * Filter handler for the newer of last comment / node updated.
 *
 * @ingroup views_filter_handlers
 *
 * @Plugin(
 *   id = "ncs_last_updated",
 *   module = "comment"
 * )
 */
class NcsLastUpdated extends Date {

  public function query() {
    $this->ensureMyTable();
    $this->node_table = $this->query->ensure_table('node', $this->relationship);

    $field = "GREATEST(" . $this->node_table . ".changed, " . $this->tableAlias . ".last_comment_timestamp)";

    $info = $this->operators();
    if (!empty($info[$this->operator]['method'])) {
      $this->{$info[$this->operator]['method']}($field);
    }
  }

}
