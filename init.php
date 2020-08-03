<?php
/**
 * Plugin Name:  WP Batch Jobs.
 * Description:  Batch Processing framework for WordPress.
 * Version:     0.5.0
 * Author:      KT12
 *
 * @package WP_Batch_Jobs
 */

namespace WBJ;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require plugin_dir_path( __FILE__ ) . 'defs.php'; // varaible defs here.
require plugin_dir_path( __FILE__ ) . 'helpers.php'; // helper function here.
require plugin_dir_path( __FILE__ ) . 'model'.DIRECTORY_SEPARATOR.'model.php'; // helper function here.

require plugin_dir_path( __FILE__ ) . 'class-wp-batch-job.php';
require plugin_dir_path( __FILE__ ) . 'class-batch-factory.php';
require plugin_dir_path( __FILE__ ) . 'class-batch.php';
require plugin_dir_path( __FILE__ ) . 'class-job.php';
require plugin_dir_path( __FILE__ ) . 'class-job-factory.php';
require plugin_dir_path( __FILE__ ) . 'class-register-job-files.php';


$wbj = new WP_Batch_Job();
$wbj->run();

$rjf = new Register_Job_Files();
$rjf->run();


/** Sample Test File ***/
require plugin_dir_path( __FILE__ ) . 'class-sample-job-test.php';
$rjf = new Sample_Job_Test();
$rjf->run();