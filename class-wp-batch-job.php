<?php
namespace WBJ;
use WBJ\Model\Batch_Job_Factory;

class WP_Batch_Job {
    public function run(){
        register_activation_hook( whj_get_plugin_file(), array( $this,  'plugin_activate' ) );

        /** support table upgrade on plugin update, where reg_act hook is not triggered. */ 
        add_action( 'plugins_loaded', array($this, 'create_tables' ) );
    }

    public function plugin_activate(){
        $this->create_tables();
    }
    
    public function create_tables(){
        $bjfm = new Batch_Job_Factory();
        $bjfm->create_jobs_table();   
        $bjfm->create_batches_table();   
    }
}