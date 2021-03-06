<?php
require_once($argv[1]); // type.php
require_once($argv[2]); // program.php
$file_prefix = $argv[3];
?>
[apps.meta]
name = meta
type = meta
arguments = 
ports = 34601
run = true
count = 1 
    
[apps.replica]
name = replica
type = replica
arguments =
ports = 34801
run = true
count = 3

[apps.client]
name = client
type = client
arguments = <?=$_PROG->name?>.instance0
run = true
count = 2 
    
[core]

tool = simulator
;tool = nativerun
;toollets = tracer
;toollets = tracer, profiler, fault_injector
pause_on_start = false

logging_factory_name = dsn::tools::screen_logger

[tools.simulator]
random_seed = 0

[network]
; how many network threads for network library(used by asio)
io_service_worker_count = 2

[network.34601]
; channel = network_header_format, network_provider_name, buffer_block_size
;RPC_CHANNEL_TCP = NET_HDR_DSN, dsn::tools::asio_network_provider, 65536

;RPC_CHANNEL_TCP = NET_HDR_THRIFT, dsn::tools::asio_network_provider, 65536


; specification for each thread pool
[threadpool.default]
worker_count = 2

[threadpool.THREAD_POOL_DEFAULT]
name = default
partitioned = false
max_input_queue_length = 1024
worker_priority = THREAD_xPRIORITY_NORMAL
worker_count = 2

[threadpool.THREAD_POOL_REPLICATION]
name = replication
partitioned = true
; max_input_queue_length = 8192
worker_priority = THREAD_xPRIORITY_NORMAL

[task.default]
is_trace = true
is_profile = true
allow_inline = false
rpc_call_channel = RPC_CHANNEL_TCP
fast_execution_in_network_thread = false
rpc_call_header_format_name = dsn
rpc_timeout_milliseconds = 5000

[task.LPC_AIO_IMMEDIATE_CALLBACK]
is_trace = false
allow_inline = false

[task.LPC_RPC_TIMEOUT]
is_trace = false

[task.RPC_FD_FAILURE_DETECTOR_PING]
is_trace = false

[task.RPC_FD_FAILURE_DETECTOR_PING_ACK]
is_trace = false

[task.LPC_BEACON_CHECK]
is_trace = false


[replication.meta_servers]
localhost:34601

[replication.app]
app_name = <?=$_PROG->name?>.instance0 
app_type = <?=$_PROG->name?> 
partition_count = 1
max_replica_count = 3

[replication]

prepare_timeout_ms_for_secondaries = 1000
learn_timeout_ms = 30000
staleness_for_commit = 20
staleness_for_start_prepare_for_potential_secondary = 110
mutation_max_size_mb = 15
mutation_max_pending_time_ms = 20
mutation_2pc_min_replica_count = 2

preapre_list_max_size_mb = 250
request_batch_disabled = false
group_check_internal_ms = 100000
group_check_disabled = false
fd_disabled = false
fd_check_interval_seconds = 5
fd_beacon_interval_seconds = 3
fd_lease_seconds = 14
fd_grace_seconds = 15
working_dir = .
log_buffer_size_mb = 1
log_pending_max_ms = 100
log_file_size_mb = 32
log_batch_write = true

config_sync_interval_ms = 60000
