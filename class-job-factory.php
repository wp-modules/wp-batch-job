<?php
namespace WBJ;
use WBJ\Model\Batch_Job;

abstract class Job_Factory {

    private $batch_size = DEFAULT_WBJ_BATCH_SIZE;

    private $exec_time_per_batch = DEFAULT_WBJ_BATCH_EXEC_TIME;

    private $job_id = 0;

    private $is_serial = 1;

    private $preprocessed_data = null;

    private $all_data = null;

    private $created_at;

    private $bjm;

    public function set_batch_size(int $size){
        $this->batch_size = $size;
    }

    public function enable_parallel_jobs(){
        $this->is_serial = 0;
    }

    public function get_batch_size():int{
        return $this->batch_size;
    }

    public function __construct(){
        $this->bjm = new Batch_Job();
    }

    abstract function preprocess();

    public function create_job(){
        //get preprocessed data.
        $data = $this->preprocess();
        $this->preprocessed_data = !empty($data['preprocessed_data']) ? $data['preprocessed_data']:null;
        if( !isset($data['all_data']) ) 
            throw new \Exception( "preprocess() function must be implemented and return array with data for batching in 'all_data' key." );
        $this->all_data = $data['all_data'];
        $this->job_id = $this->save_job();
        if(!$this->job_id)
            throw new \Exception( "Job creation failed." );
              
        $batches = $this->create_batches();
        return $this->job_id;
    }

    private function save_job():int{
        if($this->preprocessed_data != null)
            $cols['preprocessed_data'] = maybe_serialize($this->preprocessed_data); $format[] = '%s'; 
        $cols['all_data'] = maybe_serialize($this->all_data); $format[] = '%s';
        $cols['is_serial'] = $this->is_serial; $format[] = '%d';
        $cols['started'] = 0; $format[] = '%d';
        $cols['batch_processed'] = 0; $format[] = '%d';
        $cols['batch_count'] = ceil( sizeof($this->all_data)/$this->batch_size ); $format[] = '%d';
        $cols['exec_time_per_batch'] = $this->exec_time_per_batch; $format[] = '%d';
        $this->created_at = time();
        $cols['created_at'] = $this->created_at; $format[] = '%d';
        $cols['updated_at'] = $this->created_at; $format[] = '%d';
        return $this->bjm->save_job($cols, $format);
    }

    private function create_batches(){
        $batch_ids = [];
        $data_size = sizeof($this->all_data);
        $batch_size = $this->batch_size;
        $batch_count = ceil($data_size/$batch_size);
        $start_index = 0;
        for($i = 0; $i < $batch_count; $i++)
        {
            $batch_data = array_slice($this->all_data, $start_index, $batch_size);
            $batch = new Batch_Factory($this->job_id, $batch_data);
            $batch_ids[] = $batch->get_id();
            $start_index += $batch_size;
        }
        return $batch_ids;
    }
}