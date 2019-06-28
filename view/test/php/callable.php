<?php 

function test_callable(){
	call('call', function($a){
		var_dump($a,func_get_args());
	});
}

function call($a, $b){
	var_dump($a, is_callable($b));
	if(is_callable($b)){
		call_user_func_array($b, ['test','test2']);
	}
}

test_callable();