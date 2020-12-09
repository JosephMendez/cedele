<?php
function cdls_create_table_cedele_setting() {
    global $wpdb;

    $prefix = $wpdb->prefix;
    $charset_collate = '';
    if($charset_collate == null)
        $charset_collate = $wpdb->get_charset_collate();

    $sql = "
        CREATE TABLE {$prefix}cedele_setting (
          id int(11) NOT NULL AUTO_INCREMENT,
          data_name VARCHAR(50) NOT NULL,
          data_value INT(4) NOT NULL,
          PRIMARY KEY (id)
        ) $charset_collate;
        CREATE TABLE {$prefix}cedele_setting_peak_hour (
          id int(11) NOT NULL AUTO_INCREMENT,
          start_time TIME NOT NULL,
          end_time TIME NOT NULL,
          PRIMARY KEY (id)
        ) $charset_collate;
        CREATE TABLE {$prefix}cedele_setting_occasion (
          id int(11) NOT NULL AUTO_INCREMENT,
          start_date DATE NOT NULL,
          end_date DATE NOT NULL,
          description VARCHAR(100) NOT NULL,
          PRIMARY KEY (id)
        ) $charset_collate;
        CREATE TABLE {$prefix}cedele_setting_shipping_partner (
          id INT(11) NOT NULL AUTO_INCREMENT ,
          partner_name VARCHAR(100) NOT NULL ,
          short_name VARCHAR(100) NOT NULL ,
          contact_number VARCHAR(20) NOT NULL ,
          status TINYINT(1) NOT NULL DEFAULT 1,
          PRIMARY KEY (id)
        ) $charset_collate;
        CREATE TABLE {$prefix}cedele_setting_riders (
          id INT(11) NOT NULL AUTO_INCREMENT ,
          rider_name VARCHAR(100) NOT NULL ,
          contact_number VARCHAR(20) NOT NULL ,
          partner_id INT(11) NOT NULL ,
          status TINYINT(1) NOT NULL DEFAULT 1,
          PRIMARY KEY (id)
        ) $charset_collate;
        CREATE DEFINER=`root`@`localhost`
        EVENT IF NOT EXISTS `update_is_in_stock_every_day`
        ON SCHEDULE EVERY 1 DAY
          STARTS '2020-08-08 00:00:01'
        ON COMPLETION NOT PRESERVE ENABLE
        DO 
          UPDATE `wp_store_location_post` SET `is_in_stock` = 1;
        SET GLOBAL event_scheduler = ON;
    ";

    require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
    return dbDelta($sql);
}

function cdls_update_table()
{
    // code ...
}

function cdls_uninstall_table()
{
    // code ...
}