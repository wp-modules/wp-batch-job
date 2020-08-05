<?php
namespace WBJ;
use WBJ\Model\Batch_Job;

class Batch {

    private $batch_id;
    private $started;
    private $started_at;
    private $completed;
    private $completed_at;
    private $bjm; //batch_job_model

    public function __construct( int $batch_id )
    {
        $bjm = new Batch_Job();
        $this->bjm = $bjm; 
        $db_batch = $bjm->get_batch_by_id( $batch_id );
        if(!isset($db_batch->id)){
            throw new \Exception( "Batch with id:{$batch_id} not found in database." );
        }
        $this->batch_id = $db_batch->id;
        $this->started = $db_batch->started;
        $this->started_at = $db_batch->started_at;
        $this->completed = $db_batch->completed;
        $this->completed_at = $db_batch->completed_at;
        $this->data = maybe_unserialize($db_batch->data);
    }

    public function get_id()
    {
        return $this->batch_id;
    } 

    public function get_data()
    {
        return $this->data;
    } 

    public function set_started( $started_at )
    {
        $this->started = 1;
        $this->started_at = $started_at;
        return $this->bjm->set_batch_started( $this->batch_id, $started_at );
    } 

    public function set_completed( $completed_at )
    {
        $this->completed = 1;
        $this->completed_at = $completed_at;
        return $this->bjm->set_batch_completed( $this->batch_id, $completed_at );
    } 

    public function set_exec_time( $exec_time ) 
    {
        return $this->bjm->set_batch_exec_time( $this->batch_id, $exec_time );
    }

    public function set_processed_data( $processed_data )
    {
        $processed_data = maybe_serialize( $processed_data );
        return $this->bjm->set_batch_processed_data( $this->batch_id, $processed_data );
    }
}