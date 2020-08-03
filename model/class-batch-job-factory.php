<?php
namespace WBJ\Model;

/**
 * Centralize all db query here.
 */
class Batch_Job_Factory
{
    // v is just db version - is just to support backward compatibility if needed.
    private $v;
    private $wpdb;
    public function __construct( $v = '1' )
    {
        global $wpdb;
        $this->v = $v;
        $this->wpdb = $wpdb;
        $this->prefix = $wpdb->prefix;
    }

    public function create_jobs_table(){
        $charset_collate = $this->wpdb->get_charset_collate();
        $installed_ver = get_option( "WBJ_JOBS_TABLE_VERSION" );
        $table = $this->prefix.WBJ_JOBS_TABLE;
        if( WBJ_JOBS_TABLE_VERSION !== $installed_ver){
            $sql = "CREATE TABLE $table (
                `id` int NOT NULL AUTO_INCREMENT,
                `preprocessed_data` text,
                `all_data` text NOT NULL,
                `exec_time_per_batch` int NOT NULL,
                `is_serial` bit(1) NOT NULL DEFAULT b'1',
                `has_started` bit(1) NOT NULL DEFAULT b'0',
                `started_at` int DEFAULT NULL,
                `batch_count` int NOT NULL,
                `batch_processed` int NOT NULL,
                `last_batch_processed` int DEFAULT NULL,
                `completed` bit(1) NOT NULL DEFAULT b'0',
                `completed_at` int DEFAULT NULL,
                `created_at` int NOT NULL DEFAULT 0,
                `updated_at` int NOT NULL DEFAULT 0,
                PRIMARY KEY (id)
            ) $charset_collate";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }
    }

    public function create_batches_table(){
        $charset_collate = $this->wpdb->get_charset_collate();
        $installed_ver = get_option( "WBJ_BATCHES_TABLE_VERSION" );
        $table = $this->prefix.WBJ_BATCHES_TABLE;
        if( WBJ_BATCHES_TABLE_VERSION !== $installed_ver){
            $sql = "CREATE TABLE $table (
                    `id` int NOT NULL AUTO_INCREMENT,
                    `job_id` int NOT NULL,
                    `data` text NOT NULL,
                    `processed_data` text,
                    `has_started` bit(1) NOT NULL DEFAULT b'0',
                    `started_at` int DEFAULT NULL,
                    `completed` bit(1) NOT NULL DEFAULT b'0',
                    `completed_at` int DEFAULT NULL,
                    `exec_time` int DEFAULT NULL,
                    PRIMARY KEY (id)
            ) $charset_collate";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }
    }
}