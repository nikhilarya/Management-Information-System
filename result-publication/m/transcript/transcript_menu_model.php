<?php if(!defined("BASEPATH")){ exit("No direct script access allowed"); }

class transcript_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		$menu=array();
		//auth ==> ft
//		$menu['ft']=array();
//		$menu['ft']['Generate Transcript']['Admission Number Wise'] = site_url('transcript/main');
//		$menu['ft']['Generate Transcript']['Bunch Print'] = site_url('transcript/transcript_bunch');
//		$menu['exam_dr']=array();
//                $menu['exam_dr']['Generate Transcript']['Admission Number Wise'] = site_url('transcript/main');
//		$menu['exam_dr']['Generate Transcript']['Bunch Print'] = site_url('transcript/transcript_bunch');
//                $menu['exam_dr']['Generate Transcript']['TPS'] = site_url('transcript/main_tps');
                
                $menu['admin_exam']=array();
                $menu['admin_exam']['Generate Transcript']['Admission Number Wise'] = site_url('transcript/main');
		$menu['admin_exam']['Generate Transcript']['Bunch Print'] = site_url('transcript/transcript_bunch');
                $menu['admin_exam']['Generate Transcript']['TPS'] = site_url('transcript/main_tps');
                $menu['admin_exam']['Provisional Certificate']['Admission Number Wise'] = site_url('transcript/pro_certificate');
                $menu['admin_exam']['Provisional Certificate']['Bunch Print'] = site_url('transcript/pro_certificate_bunch');
                
                
		return $menu;
	}
}

	
