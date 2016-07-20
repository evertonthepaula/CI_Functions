<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class ProfilerHandler
{
	/**
	* Unique
	*
	* Check if the project is in production or in development. 
	* In production profiler is disable, in development profiler is enable;
	*
	* @access	public
	*
	*
	**/
	public function profileDefine()
	{
		$CI = &get_instance();
		$CI->output->enable_profiler( ENVIRONMENT === 'development' );
	}
}
 
/* End of file profileDefine.php */
/* Location: ./system/application/hooks/profileDefine.php */