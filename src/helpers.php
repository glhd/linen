<?php

namespace Glhd\Linen;

use Illuminate\Support\Facades\App;

function tempfile_with_cleanup(): string
{
	$path = tempnam(sys_get_temp_dir(), 'glhd-linen-data');
	
	App::terminating(static fn() => @unlink($path));
	
	return $path;
}
