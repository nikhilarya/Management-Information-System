<?php

if (!defined('BASEPATH'))exit('No direct script access allowed');

class Result_declaration_drside extends MY_Controller {

    public function __construct() {
        parent::__construct(array('exam_dr','hod','ft'));
        //$this->addJS("exam_tabulation/exam_tabulation.js");
        $this->load->model('exam_tabulation/exam_tabulation_model');
        $this->load->model('exam_tabulation/exam_tabulation_config'); 
        $this->load->model('result_declaration/result_declaration_model');
        $this->load->model('subject_mapping/mapping');
        $this->load->model('student_sem_form/sbasic_model');
    }
    public function index() {        
        $this->load->model('departments_model');                        
        $newObj=$this->departments_model->get_departments('academic');           
        $deptList=Array();
        $deptList['']='select';              
        foreach($newObj as $row){                         
             $deptList[$row->id]=$row->name;              
        }       
        $data['deptList']=$deptList;        
        $data['dept_sel']="";                       
        $this->drawHeader("Result Declaration");
        $this->load->view('result_declaration/result_declaration_drside',$data);
        $this->drawFooter();         
    }
    function show_details()
    {        
        $sy=$this->input->post('session_year');
        $sess=$this->input->post('session');
        $et=$this->input->post('exm_type');
        $deptid=$this->input->post('dept');
        $sec_name=$this->input->post('section_name');        
        $this->load->model('departments_model');                        
        $newObj=$this->departments_model->get_departments('academic');           
        $deptList=Array();
        $deptList['']='select';              
        foreach($newObj as $row){                         
             $deptList[$row->id]=$row->name;              
        } 
        $data['deptList']=$deptList;        
        $data['dept_sel']="";                 
        $data['dept_list']=$this->result_declaration_model->get_depart_details($sy,$sess,$deptid,$et,$this->input->post('rd_type'),$sec_name);           
        $data['s_year']=$sy;
        $data['sess']=$sess;
        $data['ex_type']=$et;
        $data['dept_id']= $deptid ;
        $data['sec_nm']= $sec_name ;  		                            
        $this->drawHeader("Result Declaration");
        $this->load->view('result_declaration/result_declaration_drside',$data);
        $this->drawFooter();            
    }
        function stulist_with_date_show()
        {
           // $data['id'] = $this->input->post('id');
            $data['syear']=$this->input->post('vsy');
            $data['sess']=$this->input->post('vsess');
            $data['exam_type']=$this->input->post('vet');
            $data['dept_id']=$this->input->post('vdid');
            $data['sec_nm']=$this->input->post('vsec');
            $data['course_id']=$this->input->post('vcid');
            $data['branch_id']=$this->input->post('vbid');
            $sec_nm=  $data['sem']=$this->input->post('vsec');          
            $data['row_id']=$this->input->post('row_id');
            $data['decId'] = $this->input->post('decid');
            $sy=$this->input->post('vsy');
            $sess=$this->input->post('vsess');
            $etype=$this->input->post('vet');
            if($etype=="regular"){
                $etype='R';
            }
            $deptid=$this->input->post('vdid');       
            $cid=$this->input->post('vcid');
            $bid=$this->input->post('vbid');
            $sem=$this->input->post('vsem');            
            $data['stu_list']=$this->result_declaration_model->get_result_for_view($sy,$sess,$etype,$deptid,$sem,$cid,$bid,$sec_nm);
        
            $stop_stu = $this->result_declaration_model->get_stop_stu($data['decId']);
            $data['stop_stu_main']=$stop_stu;
            $data['stop_stu']= $this->array_flatten($stop_stu);
           // print_r($data['stop_stu']); 
            
            $this->load->view('result_declaration/redec_student_list',$data);
        }
        
        function array_flatten($array) {
        if (!is_array($array)) {
            return FALSE;
        }
        $result = array();
        foreach ($array as $key => $value) {
           foreach($value as $v)
               array_push ($result, strtolower(trim ($value['admn_no'])));
          
        }
        return $result;
       }

    function date_show()
        {
            $data['syear']=$this->input->post('vsy');
            $data['sess']=$this->input->post('vsess');
            $data['exam_type']=$this->input->post('vet');
            $data['dept_id']=$this->input->post('vdid');
            $data['sec_nm']=$this->input->post('vsec');
            $data['course_id']=$this->input->post('vcid');
            $data['branch_id']=$this->input->post('vbid');
            $data['sem']=$this->input->post('vsem');
            
            //print_r($data); die();
            
            $this->load->view('result_declaration/show_date',$data);
        }
        
        
        function show_data_for_viewPDF(){
             
            $data['syear']=$this->input->post('syear');
            $data['sess']=$this->input->post('hsess');
            $data['exam_type']=$this->input->post('hetype');
            $deptid=$data['dept_id']= $data['vdid']=$this->input->post('hdeptid');            
            $sec_nm= $data['sec_nm']=$this->input->post('hsec_name');
            $data['course_id']=$this->input->post('hcid');
            $data['branch_id']=$this->input->post('hbid');
            $data['sem']=$this->input->post('hsem');
            $sy=$this->input->post('syear');
            $sess=$this->input->post('hsess');
            $etype=$this->input->post('hetype');
            if($etype=="regular"){
                $etype='R';
            }          
            $cid=$this->input->post('hcid');
            $bid=$this->input->post('hbid');
            $sem=$this->input->post('hsem');
            $data['published']=$pub=$this->input->post('published');            
            $data['result_list']=$this->result_declaration_model->get_result_for_view($sy,$sess,$etype,$deptid,$sem,$cid,$bid,$sec_nm);           
            $data['stu_ctr']=count($data['result_list']);        
            
              $this->load->helper(array('dompdf', 'file'));
              $html= $this->load->view('result_declaration/view_show_data',$data,TRUE);             
              pdf_create($html, 'RESULT_DECLARATION');
        }
        
        function show_data_for_view()
        {            
            $data['syear']=$this->input->post('vsy');
            $data['sess']=$this->input->post('vsess');
            $data['exam_type']=$this->input->post('vet');            
            $sec_nm=$data['sec_nm']=$this->input->post('vsec');
            $data['course_id']=$this->input->post('vcid');
            $data['branch_id']=$this->input->post('vbid');
            $data['sem']=$this->input->post('vsem');
            $sy=$this->input->post('vsy');
            $sess=$this->input->post('vsess');
            $etype=$this->input->post('vet');
            if($etype=="regular"){
                $etype='R';
            }
            $data['vdid']=$deptid=$this->input->post('vdid');       
            $cid=$this->input->post('vcid');
            $bid=$this->input->post('vbid');
            $sem=$this->input->post('vsem');            
            $data['result_list']=$this->result_declaration_model->get_result_for_view($sy,$sess,$etype,$deptid,$sem,$cid,$bid,$sec_nm);           
            $data['stu_ctr']=count($data['result_list']); 
            $this->load->view('result_declaration/view_show_data',$data);            
        }
        
        function show_data_for_view_redeclare(){
            //echo "hello"; die();
             $data['syear']=$this->input->post('vsy');
            $data['sess']=$this->input->post('vsess');
            $data['exam_type']=$this->input->post('vet');
            $data['dept_id']=$this->input->post('vdid');
            
            $sec_nm=$data['sec_nm']=$this->input->post('vsec');
            $data['course_id']=$this->input->post('vcid');
            $data['branch_id']=$this->input->post('vbid');
            $data['sem']=$this->input->post('vsem');
            $sy=$this->input->post('vsy');
            $sess=$this->input->post('vsess');
            $etype=$this->input->post('vet');
            if($etype=="regular"){
                $etype='R';
            }
             $data['vdid']=$deptid=$this->input->post('vdid');       
            $cid=$this->input->post('vcid');
            $bid=$this->input->post('vbid');
            $sem=$this->input->post('vsem');  
            $id=$this->input->post('id');
           $data['published']= $pub=$this->input->post('published');
            $admn_no = $this->result_declaration_model->show_data_for_view_redeclared($id,$pub);
           //print_r($admn_no); die();
            $data['result_list']=$this->result_declaration_model->get_result_for_view($sy,$sess,$etype,$deptid,$sem,$cid,$bid,$sec_nm,$admn_no);           
            $data['stu_ctr']=count($data['result_list']); 
            $this->load->view('result_declaration/view_show_data',$data); 
        }
        
         function show_data_for_PDF_redeclare(){
          
            $data['syear']=$this->input->post('syear');
            $data['sess']=$this->input->post('hsess');
            $data['exam_type']=$this->input->post('hetype');
             $deptid=$data['dept_id']= $data['vdid']=$this->input->post('hdeptid');
            $data['vdid']=$this->input->post('hdeptid');
            $sec_nm= $data['sec_nm']=$this->input->post('hsec_name');
            $data['course_id']=$this->input->post('hcid');
            $data['branch_id']=$this->input->post('hbid');
            $data['sem']=$this->input->post('hsem');
            $sy=$this->input->post('syear');
            $sess=$this->input->post('hsess');
            $etype=$this->input->post('hetype');
            if($etype=="regular"){
                $etype='R';
            }
          
            $cid=$this->input->post('hcid');
            $bid=$this->input->post('hbid');
            $sem=$this->input->post('hsem');
            $id=$this->input->post('id');
            $data['published']=$pub=$this->input->post('published');
            $admn_no = $this->result_declaration_model->show_data_for_view_redeclared($id,$pub);
            //echo $this->db->last_query(); die();
            $data['result_list']=$this->result_declaration_model->get_result_for_view($sy,$sess,$etype,$deptid,$sem,$cid,$bid,$sec_nm,$admn_no);           
            $data['stu_ctr']=count($data['result_list']);        
              $this->load->helper(array('dompdf', 'file'));
              $html= $this->load->view('result_declaration/view_show_data',$data,TRUE);             
              pdf_create($html, 'RESULT_DECLARATION');
        }
       
        function save_data(){
            date_default_timezone_set('Asia/Calcutta');            
            $data['s_year']=$this->input->post('hsyear');
            $data['session']=$this->input->post('hsess');
            $data['exam_type']=$this->input->post('hetype');
            $data['dept_id']=$this->input->post('hdeptid');
            if($data['dept_id']=='comm')$data['section']=$this->input->post('hsec_name');
            $data['course_id']=$this->input->post('hcid');
            $data['branch_id']=$this->input->post('hbid');
            $data['semester']=$this->input->post('hsem');
            $data['published_by']=$this->session->userdata('id');
            $data['published_on']=date('Y-m-d',strtotime($this->input->post('b_date')));
            $data['actual_published_on']=date("Y-m-d H:i:s");
            $data['status']="1";  
            $data['type']="F";  
            $r=$this->result_declaration_model->insert_result_declaration($data);            
             if ($r){
                       $this->session->set_flashdata('flashSuccess','Published.');
                       redirect('/result_declaration/result_declaration_drside/', 'refresh');
                  }            
           }
        
        function undo_record(){            
            $id=$this->input->post('rid');            
            $r=$this->result_declaration_model->update_decl_status($id);            
            echo $r;           
        }
               
         function printPDF(){        
            $data['master'] = $this->mp->getMarksMaster(array('id'=>$_POST['mid']));
            $data['des']=$this->mp->getMarksDes(array('marks_master_id'=>$_POST['mid']));            
            $this->load->helper(array('dompdf', 'file'));	    
             $html= $this->load->view('marks_submit/marks_print',$data,TRUE);
             pdf_create($html, 'MarksSheet');            
         }
         
          public function get_section_common2($session_year)
	 {
	        $this->load->model('attendance/show_student');
		$data['session_year']=$session_year;
		$data['section']=$this->show_student->get_section2($session_year);		
		$this->load->view('attendance/load_section',$data);                
	}     
         function save_admn_no_re_declared(){      
          
            $row_id=$this->input->post('rid');
            $admno=  explode('-',$this->input->post('admno'));                         
            if($row_id !=null && $this->input->post('admno')!=null){                     
           $this->db->trans_start();
            date_default_timezone_set('Asia/Calcutta');            
            $data['s_year']=$this->input->post('vsy');
            $data['session']=$this->input->post('vsess');
            $data['exam_type']=$this->input->post('vet');
            $data['dept_id']=$this->input->post('vdid');
            if($data['dept_id']=='comm')$data['section']=$this->input->post('vsec');
            $data['course_id']=$this->input->post('vcid');
            $data['branch_id']=$this->input->post('vbid');
            $data['semester']=$this->input->post('vsem');
            $data['published_by']=$this->session->userdata('id');
            $data['published_on']=date('Y-m-d',strtotime($this->input->post('b_date')));
            $data['actual_published_on']=date("Y-m-d H:i:s");            
            $data['status']="1";                                   
           
           //print_r($data); die();
           // $this->result_declaration_model->insert_result_declaration($data);                            
         //  echo $this->db->last_query(); die();
            $cnt=count($admno);           
            $i = 0;	
            $data1 =array();
            while($i < $cnt){
                if(trim($admno[$i])){
                $data1[]=array('res_dec_id'=>$this->input->post('rid'),'admn_no'=>$admno[$i],'in_date'=>date("Y-m-d H:i:s"));	      
                }$i++;
	     }
            if(count($data1) > 0){
             $r1=$this->result_declaration_model->insert_re_declared_data($data1);
             $this->db->trans_complete();
             $return= (bool)$this->db->trans_status();
             
            }else{
                $return =false;
            }
            echo json_encode($return);
             
                                         
         }
       }  
       
       function save_re_declared(){
              date_default_timezone_set('Asia/Calcutta');  
            $con['res_dec_id']=$this->input->post('rid');
            $con['status']='M';
            $id=explode("-", $this->input->post('res_re_id'));
           //print_r($id); die();
            $data['actual_published_on']=date("Y-m-d H:i:s");
            $data['published_on']=date('Y-m-d',strtotime($this->input->post('date')));
            $data['published_by']=$this->session->userdata('id');
            $data['reason']=$this->input->post('reason');
            $data['status']='D';
            foreach($id as $a){
                $con['id'] = $a;
               $result['status']= $this->result_declaration_model->update_re_declared_data($data,$con);
               if($result['status'] == false){
               $result['admn_no'] =$a; }
            }    
            echo json_encode($result);
       }
       
       function undo(){
           $data['status'] = 'P';
           $con['id'] =$this->input->post('id');
           $result['status']= $this->result_declaration_model->update_re_declared_data($data,$con);
           echo json_encode($result);
       }
       
       function view_result(){
          // $adm,$s,$sy,$sem,$c,$b
          // print_r($_POST);
             
          $marks_master = '<div class="row">';
          $marks_master .='<div><b>Student Addmission No.</b> '.$this->input->post('admn_no').'&nbsp;&nbsp; <b>Name </b> '.$this->input->post('name').'</div>';
           $admn_no = $this->input->post('admn_no');
           $session = $this->input->post('session');
           $session_year=$this->input->post('session_year');
           $semester=$this->input->post('semester');
          $course_id = $this->input->post('course_id');
          $branch_id = $this->input->post('branch_id');
         
          $data['marks_master'] = $this->result_declaration_model->get_result_data_marks_master($admn_no,$session,$session_year,$semester,$course_id,$branch_id);
          $data['marks_master_bkp'] = $this->result_declaration_model->get_result_data_marks_master_bkp($admn_no,$session,$session_year,$semester,$course_id,$branch_id);
        
          if(count($data['marks_master_bkp']) > 0){
          $marks_master .='<div class="col-sm-6">
                                <div style="text-align:center;">
                                    <h4>Old Result</h4>
                                </div>
                            <table class="table table-bordered" id="marks_master">
                            <thead>
                                <tr>
                                    <th align="center">Sub_id</th>
                                    <th align="center">Sessional</th>
                                    <th align="center">Theory</th>
                                    <th align="center">Practical</th>
                                    <th align="center">Total</th>
                                    <th align="center">Grade</th>
                                </tr>
                            </thead>
                            <tbody>';
                    foreach( $data['marks_master'] as $v){
                        
                        $marks_master .='<tr>
                                    <td align="center">'.$v->subject_id.'</td>
                                    <td align="center">'.$v->sessional.'</td>
                                    <td align="center">'.$v->theory.'</td>
                                    <td align="center">'.$v->practical.'</td>
                                    <td align="center">'.$v->total.'</td>
                                    <td align="center">'.$v->grade.'</td>
                                </tr>';
                    }
                    $marks_master .='</tbody></table></div>';
          }
          if(count($data['marks_master']) > 0){
          $marks_master ='<div class="col-sm-6">
                                <div style="text-align:center;">
                                    <h4 style="color:green;">Current Result</h4>
                                </div>
                            <table class="table table-bordered" id="marks_master">
                            <thead>
                                <tr>
                                    <th align="center">Sub_id</th>
                                    <th align="center">Sessional</th>
                                    <th align="center">Theory</th>
                                    <th align="center">Practical</th>
                                    <th align="center">Total</th>
                                    <th align="center">Grade</th>
                                </tr>
                            </thead>
                            <tbody>';
                    foreach( $data['marks_master'] as $v){
                        
                        $marks_master .='<tr>
                                    <td align="center">'.$v->subject_id.'</td>
                                    <td align="center">'.$v->sessional.'</td>
                                    <td align="center">'.$v->theory.'</td>
                                    <td align="center">'.$v->practical.'</td>
                                    <td align="center">'.$v->total.'</td>
                                    <td align="center">'.$v->grade.'</td>
                                </tr>';
                    }
                    $marks_master .='</tbody></table></div>';
          }
          
          $marks_master.='</div> <div style="clear:both;"> <br><br><a class="btn btn-primary" onclick="viewback();"><< Back</a>';
          
          echo $marks_master;
     }
}

?>