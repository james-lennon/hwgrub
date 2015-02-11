<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Session extends CI_Session 
{

/**
* Update an existing session
*
* @access    public
* @return    void
*/
    function sess_update()
    {
    	$CI =& get_instance();
    	log_message('debug', 'Session override called');
       // skip the session update if this is an AJAX call!
       if ( ! $CI->input->is_ajax_request() )
       {
       		log_message('debug', 'skipping sess_update');
           parent::sess_update();
       }
    } 

}

/* End of file MY_Session.php */
/* Location: ./application/libraries/MY_Session.php */ 