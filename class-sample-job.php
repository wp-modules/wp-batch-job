<?php 

class job_test extends WBJ\Job {
    public function process_batch($job_id, $batch){
        $sum = 0 ; 
        $data = $batch->get_data();
        foreach( $data as $each){
            $sum += $each;
        }
        sleep(10);
        echo "Sum is $sum";
        return $sum;
    }
    
    public function after_final(){
        echo "Hello";
        return;
    }
}