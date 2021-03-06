<?php
namespace WBJ;
use WBJ\Model\Batch_Job;

abstract class Job {
    
    private $job_id;
    private $preprocessed_data;
    private $all_data;
    private $exec_time_per_batch;
    private $is_serial;
    private $started;
    private $started_at;
    private $batch_count;
    private $batch_processed;
    private $last_batch_processed;
    private $completed;
    private $completed_at;
    private $created_at;
    private $updated_at;
    private $bjm; //batch_job_model

    public function __construct( int $job_id )
    {
        $bjm = new Batch_Job();
        $this->bjm = $bjm; 
        $db_job = $bjm->get_job_by_id($job_id);
        if(!isset($db_job->id)){
            throw new \Exception( "Job with id:{$job_id} not found in database." );
        }
        $this->job_id = $db_job->id;
        $this->preprocessed_data = maybe_unserialize($db_job->preprocessed_data);
        $this->all_data = maybe_unserialize($db_job->all_data);
        $this->exec_time_per_batch = $db_job->exec_time_per_batch;
        $this->is_serial = $db_job->is_serial;
        $this->started = $db_job->started;
        $this->started_at = $db_job->started_at;
        $this->batch_count = $db_job->batch_count;
        $this->batch_processed = $db_job->batch_processed;
        $this->last_batch_processed = $db_job->last_batch_processed;
        $this->completed = $db_job->completed;
        $this->completed_at = $db_job->completed_at;
        $this->postprocessed_data = $db_job->postprocessed_data;
        $this->created_at = $db_job->created_at;
        $this->updated_at = $db_job->updated_at;

    }

    public function process_next()
    {
        $batch = $this->next_batch();
        if( empty( $batch ) ){ 
            return false;
        }
        $started_at = time();
        $batch->started( $started_at );
        if( !$this->started ){
            $this->started( $started_at );
        }
        $processed_data = $this->process_batch( $this->job_id, $batch );
        $batch->set_processed_data( $processed_data );
        $completed_at = time();
        $batch->set_completed( $completed_at );
        $exec_time = ( $completed_at - $started_at );
        $batch->set_exec_time( $exec_time );
        $this->update_batch_processed( $batch->get_id() );
        if( $this->batch_processed == $this->batch_count ) 
        {
            $this->set_completed( $completed_at );
            $this->completed = 1;
            $this->completed_at = $completed_at;
            $postprocessed_data = $this->after_final();
            $this->set_postprocessed_data( $postprocessed_data );
        }
        return $batch;
        
    }

    public function get_id()
    {
        return $this->job_id;
    }

    public function get_preprocessed_data()
    {
        return $this->preprocessed_data;
    }

    public function get_post_processed_data()
    {
        return $this->postprocessed_data;
    }

    public function started( $started_at )
    {
        $this->started = 1;
        $this->started_at = $started_at;
        return $this->bjm->set_job_started( $this->job_id, $started_at );
    }

    public function set_completed( $completed_at )
    {
        $this->completed = 1;
        $this->completed_at = $completed_at;
        return $this->bjm->set_job_completed( $this->job_id, $completed_at );
    } 

    public function get_batch_count()
    {
        return $this->batch_count;
    }

    public function next_batch()
    {
        $next_batch_id = $this->bjm->get_next_batch_id( $this->job_id );
        if(empty($next_batch_id))
        {
            return false;
        }
        return new Batch( $next_batch_id );
    }

    private function update_batch_processed( $last_batch_id )
    {
        $this->batch_processed = $this->bjm->set_batches_processed($this->job_id, $last_batch_id);
    }

    public function set_postprocessed_data( $postprocessed_data )
    {
        $this->postprocessed_data = $postprocessed_data;
        $postprocessed_data = maybe_serialize($postprocessed_data);
        $this->batch_processed = $this->bjm->set_job_postprocessed_data( $this->job_id , $postprocessed_data);
    }

    abstract function process_batch( $job_id, $batch );
    abstract function after_final();
}