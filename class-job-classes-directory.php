<?php
namespace WBJ;

class Job_Classes_Directory {

    private $jobs;
    
    private $job_factories;

    public function __construct(){
       $this->jobs = [];
       $this->job_factories = [];
    }

    public function set_jobs( $jobs ){
        $this->jobs = $jobs;
    }

    public function get_jobs(){
        return $this->jobs;
    }

    public function set_job_factories( $job_factories ){
        $this->job_factories = $job_factories;
    }

    public function get_job_factories(){
        return $this->job_factories;
    }
}