<?php
namespace WBJ;

class Sample_Job_Test {

    public function run(){
        // add_filter('register_job_files', 'add_sample_job_file');
        add_filter( 'wbj_job_factory_files', array( $this, 'add_sample_job_factory_file'), 10, 1 );
        add_filter( 'wbj_job_files', array( $this, 'add_sample_job_file'), 10, 1 );
        add_action( 'init', array( $this, 'test_job_factory') );
        add_action( 'init', array( $this, 'test_job') );
    }

    function test_job_factory()
    {
        if(isset($_GET['wbj']) && $_GET['wbj'] == 'run_factory_sample'){
            $job = new \job_test_factory();
            $job->set_batch_size(2);
            $job_id = $job->create_job();
            echo "Job id = $job_id";
            die();
        }
    }
    
    function test_job(){
        if(isset($_GET['wbj']) && $_GET['wbj'] == 'run_sample'){
            $job = new \job_test(1);
            $job->process_next();
            die();
        }
    }
    
    function add_sample_job_factory_file( $files )
    {
        $files[] = plugin_dir_path( __FILE__ ) . 'class-sample-job-factory.php';
        return $files;
    }

    function add_sample_job_file( $files )
    {
        $files[] = plugin_dir_path( __FILE__ ) . 'class-sample-job.php';
        return $files;
    }
    
}