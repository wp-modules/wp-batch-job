<?php
namespace WBJ\Model;

/**
 * Centralize all db query here.
 */
class Batch_Job
{
    // v is just db version - is just to support backward compatibility if needed.
    private $v;
    private $wpdb;
    private $jobs_table;
    private $batches_table;

    public function __construct( $v = '1' )
    {
        global $wpdb;
        $this->v = $v;
        $this->wpdb = $wpdb;
        $this->prefix = $wpdb->prefix;
        $this->jobs_table = $this->prefix.WBJ_JOBS_TABLE;
        $this->batches_table = $this->prefix.WBJ_BATCHES_TABLE;
    }

    public function save_job($cols, $format):int
    {
        if(!$this->wpdb->insert($this->jobs_table, $cols, $format))
            return 0; 
        return $this->wpdb->insert_id;
    }

    public function save_batch( $cols, $format ):int
    {
        if(!$this->wpdb->insert($this->batches_table, $cols, $format))
            return 0; 
        return $this->wpdb->insert_id;
    }

    public function get_job_by_id( int $job_id )
    {
        return $this->wpdb->get_row( $this->wpdb->prepare( "select * from {$this->jobs_table} where id=%d", [ $job_id ] ) );
    }

    public function get_batch_by_id( int $batch_id )
    {
        return $this->wpdb->get_row( $this->wpdb->prepare( "select * from {$this->batches_table} where id=%d", [ $batch_id ] ) );
    }

    public function get_next_batch_id( int $job_id )
    {
        return $this->wpdb->get_var( $this->wpdb->prepare( "select id from {$this->batches_table} where job_id=%d and has_started = 0 limit 0,1", [ $job_id ] ) );
    }

    public function set_batches_processed( int $job_id, int $last_batch_id )
    {
        $batches_processed = $this->wpdb->get_var( $this->wpdb->prepare( "select count(id) from {$this->batches_table} where job_id=%d and completed = 1", [ $job_id ] ) );
        $this->wpdb->update(
            $this->jobs_table,
            [ 
                'batch_processed' => $batches_processed, 
                'last_batch_processed' => $last_batch_id, 
                'updated_at'  => time() 
            ],
            [ 'id'=> $job_id ]
        );
        return $batches_processed;
    }

    public function set_batch_has_started( int $batch_id, int $started_at )
    {
        return $this->wpdb->update(
            $this->batches_table,
            [ 'has_started' => 1, 'started_at'  => $started_at ],
            [ 'id'=> $batch_id ]
        );
    }

    public function set_batch_completed( int $batch_id, int $completed_at )
    {
        return $this->wpdb->update(
            $this->batches_table,
            [ 'completed' => 1, 'completed_at'  => $completed_at ],
            [ 'id'=> $batch_id ]
        );
    }

    public function set_job_has_started( int $job_id, int $started_at ){
        return $this->wpdb->update(
            $this->jobs_table,
            [ 'has_started' => 1, 'started_at'  => $started_at ],
            [ 'id'=> $job_id ]
        );
    }

    public function set_job_completed( int $job_id, int $completed_at )
    {
        return $this->wpdb->update(
            $this->jobs_table,
            [ 'completed' => 1, 'completed_at'  => $completed_at ],
            [ 'id'=> $job_id ]
        );
    }

    public function set_batch_exec_time( int $batch_id, int $exec_time )
    {
        return $this->wpdb->update(
            $this->batches_table,
            [ 'exec_time' => $exec_time ],
            [ 'id'=> $batch_id ]
        );
    }

    public function set_batch_processed_data( int $batch_id, $processed_data )
    {
        return $this->wpdb->update(
            $this->batches_table,
            [ 'processed_data' => $processed_data ],
            [ 'id'=> $batch_id ]
        );
    }
}