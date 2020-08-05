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
require plugin_dir_path( __FILE__ ) . 'class-job-classes-directory.php';
require plugin_dir_path( __FILE__ ) . 'class-register-jobs.php';
require plugin_dir_path( __FILE__ ) . 'class-job-ajaxify.php';


$wbj = new WP_Batch_Job();
$wbj->run();

$wbj_rjf = new Register_Job_Files();
$wbj_rjf->run();

// this will be used as a global variable, hence the caps.
$WBJ_JOBS = new Job_Classes_Directory();
$wbj_rj = new Register_Jobs( $WBJ_JOBS );
$wbj_rj->run();

$wbj_ja = new Job_Ajaxify( $WBJ_JOBS );
$wbj_ja->run();

/** Sample Test File ***/
require plugin_dir_path( __FILE__ ) . 'class-sample-job-test.php';
$rjf = new Sample_Job_Test();
$rjf->run();