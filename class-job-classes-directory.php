<?php
namespace WBJ;

class Job_Classes_Directory {

    private $jobs;

    private $ajaxified_jobs;

    public function __construct(){
       $this->jobs = [];
       $this->job_factories = [];
       $this->ajaxified_jobs = [];
    }

    public function set_jobs( $jobs ){
        $this->jobs = $jobs;
    }

    public function get_job_class( $job_key ){
        return $this->jobs[$job_key]['job'];
    }

    public function get_job_factory_class( $job_key ){
        return $this->jobs[$job_key]['job_factory'];
    }

    public function get_jobs(){
        return $this->jobs;
    }

    public function set_ajaxified_jobs( $ajaxified_jobs ){
        $this->ajaxified_jobs = $ajaxified_jobs;
    }

    public function get_ajaxified_jobs(){
        return $this->ajaxified_jobs;
    }
}