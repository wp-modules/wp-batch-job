<?php
namespace WBJ;
use WBJ\Model\Batch_Job;

class Batch_Factory {

    private $job_id;
    private $bjm; //batch_job model
    private $id;

    public function __construct($job_id, $data){
        $this->job_id = $job_id;
        $this->bjm = new Batch_Job();
        $this->id = $this->save_batch($data);
    }

    public function get_id(){
        return $this->id;
    }

    private function save_batch($data):int{
        $cols['job_id'] = $this->job_id; $format[] = '%d';
        $cols['data'] = maybe_serialize($data); $format[] = '%s';
        $cols['has_started'] = 0; $format[] = '%d';
        $cols['completed'] = 0; $format[] = '%d';
        return $this->bjm->save_batch( $cols, $format );
    }
}