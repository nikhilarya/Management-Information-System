<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Pro_certificate extends MY_Controller
	{
		public function __construct()
		{
			
                         parent::__construct(array('admin_exam'));
                        $this->load->model('transcript/pro_certificate_model','',TRUE);
                         
		}
		public function index()
		{
			
                        $this->drawheader('Provisional Certificate');
			$this->load->view('transcript/pro_certificate_view');
			$this->drawfooter();
		}
                public function get_details()
                {
                    $admn_no=$this->input->post('admn_no');
                    $data['stu_details']=$this->pro_certificate_model->get_stu_details($admn_no);
                    
                        $this->drawheader('Provisional Certificate');
			$this->load->view('transcript/pro_certificate_details',$data);
			$this->drawfooter();
                    
                   // print_r($data);die();
                }
                
 
                
        }
		
?>