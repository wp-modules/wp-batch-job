<?php
// Add defintions for the plugin goes here.
define('WBJ_VERSION', '0.5.0'); // this should be updated with plugin version change.

define('DEFAULT_WBJ_BATCH_SIZE', 10);
define('DEFAULT_WBJ_BATCH_EXEC_TIME', ini_get('max_execution_time'));

/**
 * DB Tables and versions
 */
define('WBJ_JOBS_TABLE','wbj_jobs');
define('WBJ_JOBS_TABLE_VERSION', '1.0.1');
define('WBJ_BATCHES_TABLE','wbj_batches');
define('WBJ_BATCHES_TABLE_VERSION', '1.0.0');