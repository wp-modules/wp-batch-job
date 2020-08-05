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
    }

    /**
     *   job classes should be added to this array with a key (if you need this jobs for automated)       e.g 
     *   [   
     *      'my-job' => 
     *       [ 
     *                    'job' => 'job_test', 
     *                    'job_factory' => 'job_test_factory'
     *       ] 
     *   ]      
     */
    public function register_jobs(){
        $jobs = $this->jcd->get_jobs();
        $jobs  = apply_filters( 'wbj_jobs', $jobs );
        $this->jcd->set_jobs( $jobs );
    }
}