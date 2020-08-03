<?php
namespace WBJ;

class Register_Job_Files {

    public function run(){
        add_action( 'plugins_loaded', array( $this, 'register_job_factory_files') );
        add_action( 'plugins_loaded', array( $this, 'register_job_files') );
    }

    public function register_job_files(){
        $file_paths = [];
        $file_paths  = apply_filters( 'wbj_job_files', $file_paths );

        foreach( $file_paths as $file_path ){
            require_once $file_path;
        }
    }
    public function register_job_factory_files(){
        $file_paths = [];
        $file_paths  = apply_filters( 'wbj_job_factory_files', $file_paths );
        foreach( $file_paths as $file_path ){
            require_once $file_path;
        }
    }

}