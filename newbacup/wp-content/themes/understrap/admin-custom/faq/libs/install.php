<?php
function faq_custom_install_table() {
    global $wpdb;

    $prefix = $wpdb->prefix;
    $charset_collate = '';
    if($charset_collate == null)
        $charset_collate = $wpdb->get_charset_collate();

    $sql = "
        CREATE TABLE {$prefix}faq_custom (
          id int(11) NOT NULL AUTO_INCREMENT,
          question text NOT NULL,
          answer text,
          faq_category_id int(11),
          status tinyint(1) DEFAULT 1, 
          created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (id)
        ) $charset_collate;
        CREATE TABLE {$prefix}faq_categories_custom (
          id int(11) NOT NULL AUTO_INCREMENT,
          title text NOT NULL,
          PRIMARY KEY (id)
        ) $charset_collate;
    ";

    require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
    return dbDelta($sql);
}