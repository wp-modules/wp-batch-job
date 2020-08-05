<?php
namespace WBJ;

class Job_Ajaxify {

    private $jcd;  //Object of Job_Classes_Directory

    public function __construct( &$jcd )
    {
        $this->jcd = $jcd;
    }

    public function run()
    {
        add_action( 'init', array( $this, 'register_ajax_jobs'), 1 );
        add_action( 'init', array( $this, 'ajaxify_registred_jobs') );
    }

    /**
     * Suppose you had registerd, job factory and job with the key name 'my-job'.
     * 
     */
    public function register_ajax_jobs()
    {
        $ajaxified_jobs = $this->jcd->get_ajaxified_jobs();
        $ajaxified_jobs  = apply_filters( 'wbj_ajax_jobs', $ajaxified_jobs );
        $this->jcd->set_ajaxified_jobs( $ajaxified_jobs );
    }
    
    public function ajaxify_registred_jobs()
    {
        $ajaxified_jobs = $this->jcd->get_ajaxified_jobs();

        foreach( $ajaxified_jobs as $key => $condition )
        {
            switch ( $condition )
            {
                case "priv":
                    add_action( 'wp_ajax_wbj_job_factory_'.$key, array( $this, 'job_factory_ajax' ) );
                    add_action( 'wp_ajax_wbj_job_'.$key, array( $this, 'job_ajax' ) );
                break; 
                case "nonpriv":
                    add_action( 'wp_ajax_nopriv_wbj_job_factory_'.$key, array( $this, 'job_factory_ajax' ) );
                    add_action( 'wp_ajax_nopriv_wbj_job_'.$key, array( $this, 'job_ajax' ) );
                break;  
                case "both":
                    add_action( 'wp_ajax_wbj_job_factory_'.$key, array( $this, 'job_factory_ajax' ) );
                    add_action( 'wp_ajax_wbj_job_'.$key, array( $this, 'job_ajax' ) );
                    add_action( 'wp_ajax_nopriv_wbj_job_factory_'.$key, array( $this, 'job_factory_ajax' ) );
                    add_action( 'wp_ajax_nopriv_wbj_job_'.$key, array( $this, 'job_ajax' ) );
                break;  
                default:
                    add_action( 'wp_ajax_wbj_job_factory_'.$key, array( $this, 'job_factory_ajax' ) );
                    add_action( 'wp_ajax_wbj_job_'.$key, array( $this, 'job_ajax' ) );
                break;
            }
        }
    }

    public function job_factory_ajax()
    {
        $action = $_POST['action'];
        $batch_size = isset($_POST['batch_size'])?(int)$_POST['batch_size']:DEFAULT_WBJ_BATCH_SIZE;
        $job_key = str_replace( "wbj_job_factory_", "", $action );
        check_ajax_referer( "wbj_job_factory_$job_key", "security" );
        $job_factory_class = $this->jcd->get_job_factory_class( $job_key );
        $job_class = $this->jcd->get_job_class( $job_key );
        $job_factory = new $job_factory_class();
        $job_factory->set_batch_size($batch_size);
        $job_id = $job_factory->create_job();
        $job = $job_class($job_id);
        $batch_count = $job->get_batch_count();
        $job_ajax_nonce = wp_create_nonce("wbj_job_$job_key_$job_id");
        wp_send_json_success([
            'job_id' => $job_id,
            'batch_count' => $batch_count,
            'job_security' => $job_ajax_nonce
        ]);
    }

    public function job_ajax()
    {
        $action = $_POST['action'];
        $job_key = str_replace( "wbj_job_", "", $action );
        $job_id = (int)$_POST['job_id'];
        check_ajax_referer( "wbj_job_$job_key_$job_id", "security" );
        $job_class = $this->jcd->get_job_class( $job_key );
        $job = new $job_class( $job_id );
        
        $completed = $job->get_completed();
        /***
         * 
         * 
         * 
         * 
         * 
         * 
         */
        die();
    }
}