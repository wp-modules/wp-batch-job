<?php
$models = [
    'bj'=>'class-batch-job.php',
    'bjf'=>'class-batch-job-factory.php'
];

// This will help overide some of the model files. 
$models = apply_filters( 'wbj_models', $models);

foreach($models as $model){
    require_once plugin_dir_path( __FILE__ ).$model;
}