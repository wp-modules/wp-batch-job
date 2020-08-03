<?php 

class job_test_factory extends WBJ\Job_Factory {
    public function preprocess() {
        // $data['preprocessed_data'] = [
        //     'filename' => 'Hello_world.php'
        // ];
        $data['all_data'] = [1,2,3,4,5];
        return $data;
    }
}