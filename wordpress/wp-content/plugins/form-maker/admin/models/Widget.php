<?php

/**
 * Class FMModelWidget
 */
class FMModelWidget {
  public function get_gallery_rows_data() {
    global $wpdb;
    $query = "SELECT * FROM " . $wpdb->prefix . "formmaker order by `title`";
    $rows = $wpdb->get_results($query);

    return $rows;
  }
}
