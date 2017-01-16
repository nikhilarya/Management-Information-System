<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Main extends MY_Controller
	{
		public function __construct()
		{
			
                         parent::__construct(array('admin_exam'));
                         $this->load->model('transcript/stu_details');
                         $this->load->model('student_grade_sheet/student_grade_model');
		}
		public function index()
		{
			$data=array();
			$this->drawheader('Transcript Generation');
			$this->load->view('transcript/transcript_view',$data);
			$this->drawfooter();
		}
	
                
		function get_details() 
		{
			$admn_no = $this->input->post('admn_no');
                        $data['nrows'] =$this->input->post('nrows');
                       // $paper = $this->input->post('psize');
                       // $pap_format = $this->input->post('pformat');
                        $paper='legal';
                        $pap_format='tcolumn';
                       
			$stu_details = $this->stu_details->get_details($admn_no);
                       // print_r($stu_details);die();
                        $data['stu_name']=$did=$stu_details[0]->stu_name;
                        $data['dept']=$did=$stu_details[0]->dept_id;
                        $data['course_id']=$cid=$stu_details[0]->course_id;
                        $data['branch_id']=$bid=$stu_details[0]->branch_id;
                        $data['cname']=$bid=$stu_details[0]->cname;
                        $data['bname']=$bid=$stu_details[0]->bname;
                        $data['admn_no']=$admn_no;
                        $data['tot_sem']=$stu_details[0]->tot_sem;;
                       
                        //Get record from Tabulation1 checking starts-----------------------------------
                        $existing_sem=0;
                        for($i=0;$i<$stu_details[0]->tot_sem;$i++)
                        {
                            //used to get semester/max_exam/sem_code
                            $et = $this->stu_details->max_exam_type_semBased($admn_no,($i+1));
                            if(!empty($et[0]->sem))
                            {
                                //used to get details from tabulation1 based on admn_no/sem_code/max_exam
                                $res = $this->stu_details->get_result_from_tabulation1($admn_no,$et[0]->sem_code,$et[0]->max_exam);
                                
                                if(!empty($res)){
                                    $j=0;
                                    foreach($res as $r)
                                    {
                                
                                    $data[$i]['result'][$j]['subje_name']=$r->subje_name;
                                    $data[$i]['result'][$j]['crdhrs']=$r->crdhrs;
                                    $data[$i]['result'][$j]['crpts']=$r->crpts;
                                    $data[$i]['result'][$j]['grade']=$r->grade;
                                    $data[$i]['result'][$j]['ysession']=$r->ysession;
                                    $data[$i]['result'][$j]['wsms']=$r->wsms;
                                    $data[$i]['result'][$j]['examtype']=$r->examtype;
                                    $data[$i]['result'][$j]['gpa']=$r->gpa;
                                    $data[$i]['result'][$j]['ogpa']=$r->ogpa;
                                    $j++;
                                    }
                              
                                $existing_sem++; //it will return 6 because tabulaition has result till 6th semester.
                                $data['mis']="tab";
                                
                                }
                            }
                             
                        }
                     
                         //End of tabulation1-----------------------------------
                        //Get record from mis regular checking------------------------------------
                        //@finding student is registered on mis regular or not , if yes then number of semester
                        $reg_sem=$this->stu_details->get_number_of_semester($admn_no);
                       // print_r($reg_sem); die();
                        if(!empty($reg_sem))
                        {
                        
                        $all_sem=explode(",",$reg_sem->sem);
                        //print_r($all_sem); die();
                        $j=0;
                        for($i=0;$i<count($all_sem);$i++)
                        {
                            $tsem[$i]=$all_sem[$i];
                            $keyone=$existing_sem++;
                            $result_reg=$this->student_grade_model->grade_sheet_details($admn_no,$tsem[$i]);
                           // print_r($result_reg)die();
                            $j=0;
                            if(!empty($result_reg)){
                               // print_r($result_reg);
                                foreach($result_reg as $r){
                                $data[$keyone]['result'][$j]['subje_name']=$r->name;
                                
                                $data[$keyone]['result'][$j]['crdhrs']=$r->credit_hours;
                                $data[$keyone]['result'][$j]['crpts']=$r->totcrdtpt;
                                $data[$keyone]['result'][$j]['grade']=$r->grade;
                                $sy=$r->session_year;
                                $sye=explode("-",$sy);
                                $sye1=$sye[0]%100;
                                $sye2=$sye[1]%100;
                                $sye3=$sye1.$sye2;
                                $data[$keyone]['result'][$j]['ysession']=$sye3;
                                
                                $s=$r->session;
                                if($s=="Monsoon"){$ss="MS";}
                                if($s=="Winter"){$ss="WS";}
                                if($s=="Summber"){$ss="ZS";}
                                $data[$keyone]['result'][$j]['wsms']=$ss;
                                 if(isset($data[$keyone]['result'][$j]['examtype'])){
                                    $et= (++$data[$keyone]['result'][$j]['examtype']);
                                 }else{
                                     $et="R";
                                 }
                                 //echo $et;
                            
                                $data[$keyone]['result'][$j]['examtype']=$et;//"R";
                                $data[$keyone]['result'][$j]['gpa']="N/A";
                                $data[$keyone]['result'][$j]['ogpa']="N/A";
                                $data['mis']="reg";
                                $j++;
                                }
                            }
//                            else{
//                                echo "No Record for".$tsem[$i];
//                            }
                             //print_r($data[$keyone]);die();
                        } //end of for loop
                        } // end of if
                        
                            //finding other students ---------------------
                            $commsem=0;
                            $reg_sem=$this->stu_details->get_number_of_semester_from_other($admn_no);
                            //print_r($reg_sem);die();
                            if(!empty($reg_sem))
                            {
                            
                                if (strpos($reg_sem->sem, ',') !== false) {

                                      $all_sem=explode(",",$reg_sem->sem);
                                     
                                      $commsem=1;
                                }
                                 else
                                {
                                    $all_sem=$reg_sem->sem;
                                    $commsem=0;
                                }
                                
                                //----------------------------------------
                             if($commsem==1)
                             {
                                 
                             
                            $j=0;
                        for($i=0;$i<count($all_sem);$i++)
                        {
                            $tsem[$i]=$all_sem[$i];
                            $etype=$this->stu_details->get_exam_type($admn_no,$tsem[$i]);
                           
                                    
                            $keyone=$tsem[$i]-1;;
                            // print_r($keyone);die();
                            $result_reg=$this->student_grade_model->get_others_sub_marks($admn_no,trim($tsem[$i]),$etype->type); 
                           // print_r($result_reg);
                            $result_reg_final=$this->stu_details->get_final_details($result_reg[0]->foil_id);
                           // print_r($result_reg_final);
                            $j=0;
                            if(!empty($result_reg)){
                               // print_r($result_reg);
                                foreach($result_reg as $r){
                                $data[$keyone]['result'][$j]['subje_name']=$r->name;
                                $data[$keyone]['result'][$j]['crdhrs']=$r->cr_hr;
                               // $data[$keyone]['result'][$j]['crpts']=$r->cr_pts;
                                $p=$this->student_grade_model->get_grade_points($r->grade);
                                $data[$keyone]['result'][$j]['crpts']=$p->points*$r->cr_hr;
                                $data[$keyone]['result'][$j]['grade']=$r->grade;
                                $sy=$result_reg_final->session_yr;
                               // print_r($sy);die();
                                $sye=explode("-",$sy);
                                $sye1=$sye[0]%100;
                                $sye2=$sye[1]%100;
                                $sye3=$sye1.$sye2;
                                $data[$keyone]['result'][$j]['ysession']=$sye3;
                                
                                $s=$result_reg_final->session;
                                if($s=="Monsoon"){$ss="MS";}
                                if($s=="Winter"){$ss="WS";}
                                if($s=="Summber"){$ss="ZS";}
                                $data[$keyone]['result'][$j]['wsms']=$ss;
                                $data[$keyone]['result'][$j]['examtype']=(++$data[$keyone]['result'][$j]['examtype']);//"Other";
                                $data[$keyone]['result'][$j]['gpa']=$result_reg_final->gpa;
                                $data[$keyone]['result'][$j]['ogpa']=$result_reg_final->cgpa;
                                $data['mis']="others";
                                $j++;
                                }
                            }

                        } //end of for loop
                        
                             }
                             else
                             {
                                 
                                      $tsem=$all_sem;
                                     
                            $etype=$this->stu_details->get_exam_type($admn_no,$tsem);
                           
                                   //print_r($etype);die();  
                            $keyone=$tsem-1;;
                            // print_r($keyone);die();
                            $result_reg=$this->student_grade_model->get_others_sub_marks($admn_no,trim($tsem),$etype->type); 
                            //print_r($result_reg);
                            $result_reg_final=$this->stu_details->get_final_details($result_reg[0]->foil_id);
                            //print_r($result_reg_final);die();
                            $j=0;
                            if(!empty($result_reg)){
                                
                               
                                foreach($result_reg as $r){
                                     //print_r($r->grade);
                                $data[$keyone]['result'][$j]['subje_name']=$r->name;
                                $data[$keyone]['result'][$j]['crdhrs']=$r->cr_hr;
                               // $data[$keyone]['result'][$j]['crpts']=$r->cr_pts;
                                $p=$this->student_grade_model->get_grade_points($r->grade);
                                $data[$keyone]['result'][$j]['crpts']=$p->points*$r->cr_hr;
                                $data[$keyone]['result'][$j]['grade']=$r->grade;
                                $sy=$result_reg_final->session_yr;
                               // print_r($sy);die();
                                $sye=explode("-",$sy);
                                $sye1=$sye[0]%100;
                                $sye2=$sye[1]%100;
                                $sye3=$sye1.$sye2;
                                $data[$keyone]['result'][$j]['ysession']=$sye3;
                                
                                $s=$result_reg_final->session;
                                if($s=="Monsoon"){$ss="MS";}
                                if($s=="Winter"){$ss="WS";}
                                if($s=="Summber"){$ss="ZS";}
                                $data[$keyone]['result'][$j]['wsms']=$ss;
                                $data[$keyone]['result'][$j]['examtype']=(++$data[$keyone]['result'][$j]['examtype']);//"Other";
                                $data[$keyone]['result'][$j]['gpa']=$result_reg_final->gpa;
                                $data[$keyone]['result'][$j]['ogpa']=$result_reg_final->cgpa;
                                $data['mis']="others";
                                $j++;
                                }
                                
                            }
                                 
                             }
                             
                                      
                                      
                                
                                //-----------------------------------------
                                      
                                
                               
                                
                               
                                
                                
                            }
                       
                          // die();
                        
                  
                        // End of Regular checking
                        
                        
                        //Get record from mis other students regular checking------------------------------------
                       

                        // End of other students checking
                        
                    
                        if($pap_format=='scolumn')
                        {
                            if($paper=='afour'){
                                  $this->drawheader('');
                                $this->load->view('transcript/output_single',$data);
                                $this->drawfooter();
                            }
                            if($paper=='legal'){
                                  $this->drawheader('');
                                $this->load->view('transcript/output_single',$data);
                                $this->drawfooter();
                            }
                                
                        }
                        if($pap_format=='tcolumn')
                        {
                            if($paper=='afour'){
                                
                                $this->drawheader('');
                                $this->load->view('transcript/output',$data);
                                $this->drawfooter();
                                
                            }
                            if($paper=='legal'){
                               
                                //echo "legal";die();
                                //print_r($data);die();
                                $this->drawheader('');
                                //$this->load->view('transcript/output_legal',array('stu_result'=>$data));
                                $this->load->view('transcript/output_legal',array('stu_result'=>$data));
                                $this->drawfooter();
                                
                            }
                        }
                        
                        
                        
                       
			
			
		}

	}
?>