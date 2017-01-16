<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Main_tps extends MY_Controller
	{
		public function __construct()
		{
			
                         parent::__construct(array('admin_exam'));
		}
		public function index()
		{
			$data=array();
			$this->drawheader('Transcript Generation');
			$this->load->view('transcript/main_tps_view',$data);
			$this->drawfooter();
		}
		function print_transcript($admn_no)
		{
			$this->load->helper(array('dompdf', 'file'));
			$this->load->model('transcript/stu_details');
			$stu_details = $this->stu_details->get_details($admn_no);
		
			$branch_id = $stu_details[0]->branch_id;
			$course_id = $stu_details[0]->course_id;
			$branch_obj = $this->stu_details->get_branch($stu_details[0]->branch_id);
			$course_obj = $this->stu_details->get_course($stu_details[0]->course_id);
			$branch = $branch_obj[0]->name;
			$course = $course_obj[0]->name;
			/*creating the data array for the result*/
			

			$stu_result = $this->stu_details->get_result($admn_no);
			foreach ($stu_result as $key => $rs) {
				if(isset($result[$rs->sem][$rs->subje_name]))
				{
					if($result[$rs->sem][$rs->subje_name]['grade']=='F')
					{
						$result[$rs->sem][$rs->subje_name]['grade']=$rs->grade;
						$result[$rs->sem][$rs->subje_name]['points']=$rs->points;
					}
				}
				else
				{
					
					$result[$rs->sem][$rs->subje_name]['grade']=$rs->grade;
					$result[$rs->sem][$rs->subje_name]['points']=$rs->points;
					$result[$rs->sem][$rs->subje_name]['credit']=$rs->crdhrs;

				}
				
			}
			//print_r($result);
			//print_r($stu_result);
			$stu_results_new = $this->stu_details->grade_sheet_details($admn_no);
			//print_r($stu_details_new);
			foreach ($stu_results_new as $key => $rs) {
				if(isset($result[$rs->semester][strtoupper($rs->name)]))
				{
					if($result[$rs->semester][strtoupper($rs->name)]['grade']=='F')
					{
						$result[$rs->semester][strtoupper($rs->name)]['grade']=$rs->grade;
						$result[$rs->semester][strtoupper($rs->name)]['points']=$rs->points;
					}
				}
				else
				{
					
					$result[$rs->semester][strtoupper($rs->name)]['grade']=$rs->grade;
					$result[$rs->semester][strtoupper($rs->name)]['points']=$rs->points;
					$result[$rs->semester][strtoupper($rs->name)]['credit']=$rs->credit_hours;

				}
				
			}
			$stu_foil_res=$this->stu_details->new_table_fail($admn_no);
			//print_r($data['foil']);
			foreach ($stu_foil_res as $key => $rs) {
				if(isset($result[$rs->semester][strtoupper($rs->name)]))
				{
					if($result[$rs->semester][strtoupper($rs->name)]['grade']=='F' &&  $rs->status == 'PASS')
					{
						$result[$rs->semester][strtoupper($rs->name)]['grade']='D';
						$result[$rs->semester][strtoupper($rs->name)]['points']=4;
					}
				}
				
				
			}
			for($i=1;$i<11;$i++)
			{
				$stu_foil_added = $this->stu_details->get_others_sub_marks($admn_no,$i);
				print_r($stu_foil_added);
			}
			
			//
			$data =   array();
			$data['stu_result']=$result;
			$data['admn_no']=$admn_no;
			$data['name_of_student']=$stu_result[0]->stu_name;
			$data['branch_id']=$branch_id;
			$data['course_id']=$course_id;
			//$this->load->helper(array('dompdf', 'file'));
			//$this->load->view('transcript/output',$data);
			//pdf_create($ff, 'Transcript_' . $admn_no);
		}
		function get_details($p='',$adno='') 
		{
                    
			$this->load->model('transcript/stu_details');
			
                        if($p=='')
                        {
			$admn_no = $this->input->post('admn_no');
                        }  
                        
                        if($p==15)
                        {
			$admn_no =$adno;
                        }  
                        
                        
                        
			
			$stu_details = $this->stu_details->get_details($admn_no);
		
			$branch_id = $stu_details[0]->branch_id;
			$course_id = $stu_details[0]->course_id;
			$branch_obj = $this->stu_details->get_branch($stu_details[0]->branch_id);
			$course_obj = $this->stu_details->get_course($stu_details[0]->course_id);
			$branch = $branch_obj[0]->name;
			$course = $course_obj[0]->name;
			/*creating the data array for the result*/
			$stu_result = $this->stu_details->get_result($admn_no);
                        //var_dump($stu_result);die();
			foreach ($stu_result as $key => $rs) {
				if(isset($result[$rs->sem][$rs->subje_name]))
				{
					if($result[$rs->sem][$rs->subje_name]['grade']=='F')
					{
						$result[$rs->sem][$rs->subje_name]['theory']=$rs->theory;
                                                $result[$rs->sem][$rs->subje_name]['sessional']=$rs->sessional;
                                                $result[$rs->sem][$rs->subje_name]['practical']=$rs->practiocal;
                                                $result[$rs->sem][$rs->subje_name]['total']=$rs->totalmarks;
                                                $result[$rs->sem][$rs->subje_name]['total']=$rs->grade;
						$result[$rs->sem][$rs->subje_name]['points']=$rs->points;
					}
				}
				else
				{
					$result[$rs->sem][$rs->subje_name]['theory']=$rs->theory;
                                        $result[$rs->sem][$rs->subje_name]['sessional']=$rs->sessional;
                                        $result[$rs->sem][$rs->subje_name]['practical']=$rs->practiocal;
                                         $result[$rs->sem][$rs->subje_name]['total']=$rs->totalmarks;
					$result[$rs->sem][$rs->subje_name]['grade']=$rs->grade;
					$result[$rs->sem][$rs->subje_name]['points']=$rs->points;
					$result[$rs->sem][$rs->subje_name]['credit']=$rs->crdhrs;

				}
                                
				
			}
			//print_r($result);die();
			//print_r($stu_result);
			$stu_results_new = $this->stu_details->grade_sheet_details($admn_no);
			//print_r($stu_results_new);die();
			foreach ($stu_results_new as $key => $rs) {
				if(isset($result[$rs->semester][strtoupper($rs->name)]))
				{
					if($result[$rs->semester][strtoupper($rs->name)]['grade']=='F')
					{
						$result[$rs->semester][strtoupper($rs->name)]['theory']=$rs->theory;
                                                $result[$rs->semester][strtoupper($rs->name)]['sessional']=$rs->sessional;
                                               $result[$rs->semester][strtoupper($rs->name)]['practical']=$rs->practical;
                                               $result[$rs->semester][strtoupper($rs->name)]['total']=$rs->total;
                                               $result[$rs->semester][strtoupper($rs->name)]['grade']=$rs->grade;
						$result[$rs->semester][strtoupper($rs->name)]['points']=$rs->points;
					}
				}
				else
				{
					$result[$rs->semester][strtoupper($rs->name)]['theory']=$rs->theory;
                                        $result[$rs->semester][strtoupper($rs->name)]['sessional']=$rs->sessional;
                                        $result[$rs->semester][strtoupper($rs->name)]['practical']=$rs->practical;
                                        $result[$rs->semester][strtoupper($rs->name)]['total']=$rs->total;
					$result[$rs->semester][strtoupper($rs->name)]['grade']=$rs->grade;
					$result[$rs->semester][strtoupper($rs->name)]['points']=$rs->points;
					$result[$rs->semester][strtoupper($rs->name)]['credit']=$rs->credit_hours;

				}
				
			}
			$stu_foil_res=$this->stu_details->new_table_fail($admn_no);
			//print_r($data['foil']);
			foreach ($stu_foil_res as $key => $rs) {
				if(isset($result[$rs->semester][strtoupper($rs->name)]))
				{
					if($result[$rs->semester][strtoupper($rs->name)]['grade']=='F' &&  $rs->status == 'PASS')
					{
						$result[$rs->semester][strtoupper($rs->name)]['grade']='D';
						$result[$rs->semester][strtoupper($rs->name)]['points']=4;
					}
				}
				
				
			}

			$data =   array();
			$data['stu_result']=$result;
                       
			$data['stu_details']=$stu_details;
			$data['admn_no']=$admn_no;
			$data['name_of_student']=$stu_result[0]->stu_name;
                        $bnm=$this->stu_details->get_branch($branch_id);
                        $cnm=$this->stu_details->get_course($course_id);
                        $data['branch_id']=$bnm[0]->name;
			$data['course_id']=$cnm[0]->name;
                        
                        
//                        print_r($data['branch_id']);
//                        print_r($data['course_id']);
//                        
//                        die();
                        
//			$data['branch_id']=$branch_id;
//			$data['course_id']=$course_id;
                        
                       if($p==15){
                           //print_r($data);die();
                              $this->print_excel($data);
                        }else{
			$this->drawheader('');
			$this->load->view('transcript/output_tps',$data);
			$this->drawfooter();
                        }
                        
			
			
		}
                    
                function print_excel($data)
                {
                   
                		
		
		
		
                
            
            
      if(!empty($data))
       {
          
        //  print_r($data);die()     ;
          //CSV Library//
                    $this->load->helper('download');
                    $this->load->library('PHPExcel');
                    $this->load->library('PHPExcel/IOFactory');	
                    
               
                     //////////////////Setting///////////////////////
                        $heading = array(
                                                'font'  => array(
                                                             'bold'  => true,
                                                             'color' => array('rgb' => '000000'),
                                                             'size'  => 8,
                                                             'name'  => 'Verdana',
                                                             'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
                          ));
                        
                        $datac = array(
                                                'font'  => array(
                                                             'bold'  => false,
                                                             'color' => array('rgb' => '000000'),
                                                             'size'  => 8,
                                                             'name'  => 'Verdana',
                                                             'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
                          ));
                        
                        $main = array(
                                                'font'  => array(
                                                             'bold'  => true,
                                                             'color' => array('rgb' => '000000'),
                                                             'size'  => 9,
                                                             'name'  => 'Verdana',
                                                             'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
                          ));
                    ///////////////////////////////////////
                        
                          $objPHPExcel = new PHPExcel();
                     $objPHPExcel->getProperties()
                    ->setTitle("Student Report")
                    ->setDescription("Student Report");
                     
                     $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('C2', 'INDIAN SCHOOL OF MINES')
                        ->setCellValue('C3', ' DHANBAD - 826004')
                        ->setCellValue('C4', 'CONSOLIDATE GRADE CARD');

                     
                     //--------------------------------------------

        $objPHPExcel->getActiveSheet()->mergeCells('C2:G2');  
        $objPHPExcel->getActiveSheet()->mergeCells('C3:G3');
        $objPHPExcel->getActiveSheet()->mergeCells('C4:G4');
        
             $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A7', 'Name')
                ->setCellValue('C7', 'Admn No.')
              ->setCellValue('E7', 'Course')
              ->setCellValue('G7', 'Branch');
             
             
             $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('B7',strtoupper($data['name_of_student']) )
                ->setCellValue('D7', strtoupper($data['admn_no']))
              ->setCellValue('F7', $data['course_id'])
              ->setCellValue('H7', $data['branch_id']);
                
                
        
       $objPHPExcel->getActiveSheet()->getStyle('A7')->applyFromArray($heading);
       $objPHPExcel->getActiveSheet()->getStyle('B7')->applyFromArray($datac);
       $objPHPExcel->getActiveSheet()->getStyle('C7')->applyFromArray($heading);
       $objPHPExcel->getActiveSheet()->getStyle('D7')->applyFromArray($datac);
       $objPHPExcel->getActiveSheet()->getStyle('E7')->applyFromArray($heading);
       $objPHPExcel->getActiveSheet()->getStyle('F7')->applyFromArray($datac);
       $objPHPExcel->getActiveSheet()->getStyle('G7')->applyFromArray($heading);
       $objPHPExcel->getActiveSheet()->getStyle('H7')->applyFromArray($datac);
      
       if($data){
           
           $row=10;
         $k=1;  foreach($stu as $s){
                                                                  if($s->middle_name="Na")
								 {
									 $mname=" ";
								 }
								 else
								 {
									 $mname=$b->middle_name;
								 }
                  $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('A'.$row, $k)
                                    ->setCellValue('B'.$row, strtoupper($s->admn_no))
                                    ->setCellValue('C'.$row, strtoupper(($s->first_name ." ". $mname." ". $s->last_name)))
                                    ->setCellValue('E'.$row, strtoupper($s->dept_id))
                                    ->setCellValue('F'.$row, strtoupper($s->course_id))
                                    ->setCellValue('G'.$row, strtoupper($s->branch_id))
                                    ->setCellValue('H'.$row, strtoupper($s->mobile_no))
                                    ->setCellValue('I'.$row, strtoupper($s->email));
                        
                        
             
                $objPHPExcel->getActiveSheet()->mergeCells('C'.$row.':D'.$row);  
                
        
       $objPHPExcel->getActiveSheet()->getStyle('A'.$row)->applyFromArray($datac);
       $objPHPExcel->getActiveSheet()->getStyle('B'.$row)->applyFromArray($datac);
       $objPHPExcel->getActiveSheet()->getStyle('C'.$row)->applyFromArray($datac);
       $objPHPExcel->getActiveSheet()->getStyle('D'.$row)->applyFromArray($datac);
       $objPHPExcel->getActiveSheet()->getStyle('E'.$row)->applyFromArray($datac);
       $objPHPExcel->getActiveSheet()->getStyle('F'.$row)->applyFromArray($datac);
       $objPHPExcel->getActiveSheet()->getStyle('G'.$row)->applyFromArray($datac);
        $objPHPExcel->getActiveSheet()->getStyle('H'.$row)->applyFromArray($datac);
       $k++;
       $row++;
           }
           
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

        if(!is_dir('./assets/sem_report/'))
        {
            mkdir('./assets/sem_report/', 0777, true);
        }
        $output_file='./assets/sem_report/Stu_Report_.xls';
        $objWriter->save($output_file);
        
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment;  filename=student_list.xls');
        header('Content-Length: '.filesize($output_file));
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        ob_get_clean();
        echo file_get_contents($output_file) ;
        ob_end_flush();
       }
       
       }
       else
       {
           $this->session->set_flashdata('flashError',' Record Not Found.');
           redirect('/transcript/main_tps/index', 'refresh');
       }
    }
            
            
            
            
        
//-------------------------------Excel End-------------------------------------------------------------------------	
	

	}
?>