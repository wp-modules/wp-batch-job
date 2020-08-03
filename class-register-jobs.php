<?php
namespace WBJ;

class Register_Jobs {

    private $jcd;  //Object of Job_Classes_Directory

    public function __construct( &$jcd )
    {
        $this->jcd = $jcd;
    }

    public function run(){
        // Don't go by the hook name, 'sanitize_comment_cookies' that runs after 'plugin_loaded' but before 'init'.
        add_action( 'sanitize_comment_cookies', array( $this, 'register_jobs') );
        add_action( 'sanitize_comment_cookies', array( $this, 'register_job_factories') );
    }

    public function register_jobs(){
        $jobs = $this->jcd->get_jobs();
        $jobs  = apply_filters( 'wbj_jobs', $jobs );
        $this->jcd->set_jobs( $jobs );
    }

    public function register_job_factories(){
        $job_factories = $this->jcd->get_job_factories();
        $job_factories  = apply_filters( 'wbj_job_factories', $job_factories );
        $this->jcd->set_job_factories( $job_factories );
    }

}