<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transcript_bunch extends MY_Controller {

    public function __construct() {

        parent::__construct(array('admin_exam'));
        $this->load->model('transcript/transcript_bunch_model', '', TRUE);
        $this->load->model('student_view_report/report_model', '', TRUE);
        $this->load->model('transcript/stu_details');
        $this->load->model('student_grade_sheet/student_grade_model');
        $this->load->model('marks_submission_control/marks_submission_control_model', '', TRUE);
    }

    public function index() {
        $data['session_year'] = $this->marks_submission_control_model->get_session_year();
        $data['dept_list'] = $this->report_model->get_depts();
        $this->drawheader('Transcript Bunch Printing');
        $this->load->view('transcript/transcript_bunch_view', $data);
        $this->drawfooter();
    }

    public function get_course_by_dept() {
        $did = $this->input->post('dept_id');
        $data['course_list'] = $this->report_model->get_course_bydept_cs($did);
        // print_r($data);
        echo json_encode($data);
    }

    public function get_branch_by_course_dept() {
        $did = $this->input->post('dept_id');
        $cid = $this->input->post('course_id');


        $data['br_list'] = $this->report_model->get_branch_bycourse($cid, $did);
        //print_r($data);
        echo json_encode($data);
    }

    function check_generate() {
        if (isset($_POST['pdf'])) {
            $this->generate_transcript('pdf');
        } elseif (isset($_POST['excel'])) {
            $this->generate_transcript('excel');
        }
    }

    function generate_transcript($id) {

        $data['syear'] = $syear = $this->input->post('selsyear');
        $data['dept_id'] = $did = $this->input->post('seldept');
        $data['course_id'] = $cid = $this->input->post('courselist');
        $data['branch_id'] = $bid = $this->input->post('branchlist');
        $data['sem'] = $sem = $this->input->post('sem');
        $count_rec = 0;
        //print_r($data);die();
        // need to add session year 
        $stu_all = $this->transcript_bunch_model->get_all_student($did, $cid, $bid, $sem);
        // print_r ($stu_all[1]->id);die();
        foreach ($stu_all as $r) {

            $admn_no = $r->id;

            $data['nrows'] = '14';


            $stu_details = $this->stu_details->get_details($admn_no);
            // print_r($stu_details);die();
            $data['stu_name'] = $did = $stu_details[0]->stu_name;
            $data['dept'] = $did = $stu_details[0]->dept_id;
            $data['course_id'] = $cid = $stu_details[0]->course_id;
            $data['branch_id'] = $bid = $stu_details[0]->branch_id;
            $data['cname'] = $bid = $stu_details[0]->cname;
            $data['bname'] = $bid = $stu_details[0]->bname;
            $data['admn_no'] = $admn_no;
            $data['tot_sem'] = $stu_details[0]->tot_sem;
            ;

            //Get record from Tabulation1 checking starts-----------------------------------
            $existing_sem = 0;
            for ($i = 0; $i < $stu_details[0]->tot_sem; $i++) {
                //used to get semester/max_exam/sem_code
                $et = $this->stu_details->max_exam_type_semBased($admn_no, ($i + 1));
                if (!empty($et[0]->sem)) {
                    //used to get details from tabulation1 based on admn_no/sem_code/max_exam
                    $res = $this->stu_details->get_result_from_tabulation1($admn_no, $et[0]->sem_code, $et[0]->max_exam);

                    if (!empty($res)) {
                        $j = 0;
                        foreach ($res as $r) {

                            $data[$i]['result'][$j]['subje_name'] = $r->subje_name;
                            $data[$i]['result'][$j]['crdhrs'] = $r->crdhrs;
                            $data[$i]['result'][$j]['crpts'] = $r->crpts;
                            $data[$i]['result'][$j]['grade'] = $r->grade;
                            $data[$i]['result'][$j]['ysession'] = $r->ysession;
                            $data[$i]['result'][$j]['wsms'] = $r->wsms;
                            $data[$i]['result'][$j]['examtype'] = $r->examtype;
                            $data[$i]['result'][$j]['gpa'] = $r->gpa;
                            $data[$i]['result'][$j]['ogpa'] = $r->ogpa;
                            $j++;
                        }

                        $existing_sem++; //it will return 6 because tabulaition has result till 6th semester.
                        $data['mis'] = "tab";
                    }
                }
            }

            //End of tabulation1-----------------------------------
            //Get record from mis regular checking------------------------------------
            //@finding student is registered on mis regular or not , if yes then number of semester
            $reg_sem = $this->stu_details->get_number_of_semester($admn_no);
            // print_r($reg_sem); die();
            if (!empty($reg_sem)) {

                $all_sem = explode(",", $reg_sem->sem);
                //print_r($all_sem); die();
                $j = 0;
                for ($i = 0; $i < count($all_sem); $i++) {
                    $tsem[$i] = $all_sem[$i];
                    $keyone = $existing_sem++;
                    $result_reg = $this->student_grade_model->grade_sheet_details($admn_no, $tsem[$i]);
                    // print_r($result_reg)die();
                    $j = 0;
                    if (!empty($result_reg)) {
                        // print_r($result_reg);
                        foreach ($result_reg as $r) {
                            $data[$keyone]['result'][$j]['subje_name'] = $r->name;

                            $data[$keyone]['result'][$j]['crdhrs'] = $r->credit_hours;
                            $data[$keyone]['result'][$j]['crpts'] = $r->totcrdtpt;
                            $data[$keyone]['result'][$j]['grade'] = $r->grade;
                            $sy = $r->session_year;
                            $sye = explode("-", $sy);
                            $sye1 = $sye[0] % 100;
                            $sye2 = $sye[1] % 100;
                            $sye3 = $sye1 . $sye2;
                            $data[$keyone]['result'][$j]['ysession'] = $sye3;

                            $s = $r->session;
                            if ($s == "Monsoon") {
                                $ss = "MS";
                            }
                            if ($s == "Winter") {
                                $ss = "WS";
                            }
                            if ($s == "Summber") {
                                $ss = "ZS";
                            }
                            $data[$keyone]['result'][$j]['wsms'] = $ss;
                            if (isset($data[$keyone]['result'][$j]['examtype'])) {
                                $et = ( ++$data[$keyone]['result'][$j]['examtype']);
                            } else {
                                $et = "R";
                            }
                            //echo $et;

                            $data[$keyone]['result'][$j]['examtype'] = $et; //"R";
                            $data[$keyone]['result'][$j]['gpa'] = "N/A";
                            $data[$keyone]['result'][$j]['ogpa'] = "N/A";
                            $data['mis'] = "reg";
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
            $commsem = 0;
            $reg_sem = $this->stu_details->get_number_of_semester_from_other($admn_no);
            //print_r($reg_sem);die();
            if (!empty($reg_sem)) {

                if (strpos($reg_sem->sem, ',') !== false) {

                    $all_sem = explode(",", $reg_sem->sem);

                    $commsem = 1;
                } else {
                    $all_sem = $reg_sem->sem;
                    $commsem = 0;
                }

                //----------------------------------------
                if ($commsem == 1) {


                    $j = 0;
                    for ($i = 0; $i < count($all_sem); $i++) {
                        $tsem[$i] = $all_sem[$i];
                        $etype = $this->stu_details->get_exam_type($admn_no, $tsem[$i]);


                        $keyone = $tsem[$i] - 1;
                        ;
                        // print_r($keyone);die();
                        $result_reg = $this->student_grade_model->get_others_sub_marks($admn_no, trim($tsem[$i]), $etype->type);
                        // print_r($result_reg);
                        $result_reg_final = $this->stu_details->get_final_details($result_reg[0]->foil_id);
                        // print_r($result_reg_final);
                        $j = 0;
                        if (!empty($result_reg)) {
                            // print_r($result_reg);
                            foreach ($result_reg as $r) {
                                $data[$keyone]['result'][$j]['subje_name'] = $r->name;
                                $data[$keyone]['result'][$j]['crdhrs'] = $r->cr_hr;
                                // $data[$keyone]['result'][$j]['crpts']=$r->cr_pts;
                                $p = $this->student_grade_model->get_grade_points($r->grade);
                                $data[$keyone]['result'][$j]['crpts'] = $p->points * $r->cr_hr;
                                $data[$keyone]['result'][$j]['grade'] = $r->grade;
                                $sy = $result_reg_final->session_yr;
                                // print_r($sy);die();
                                $sye = explode("-", $sy);
                                $sye1 = $sye[0] % 100;
                                $sye2 = $sye[1] % 100;
                                $sye3 = $sye1 . $sye2;
                                $data[$keyone]['result'][$j]['ysession'] = $sye3;

                                $s = $result_reg_final->session;
                                if ($s == "Monsoon") {
                                    $ss = "MS";
                                }
                                if ($s == "Winter") {
                                    $ss = "WS";
                                }
                                if ($s == "Summber") {
                                    $ss = "ZS";
                                }
                                $data[$keyone]['result'][$j]['wsms'] = $ss;
                                $data[$keyone]['result'][$j]['examtype'] = ( ++$data[$keyone]['result'][$j]['examtype']); //"Other";
                                $data[$keyone]['result'][$j]['gpa'] = $result_reg_final->gpa;
                                $data[$keyone]['result'][$j]['ogpa'] = $result_reg_final->cgpa;
                                $data['mis'] = "others";
                                $j++;
                            }
                        }
                    } //end of for loop
                } else {

                    $tsem = $all_sem;

                    $etype = $this->stu_details->get_exam_type($admn_no, $tsem);

                    //print_r($etype);die();  
                    $keyone = $tsem - 1;
                    ;
                    // print_r($keyone);die();
                    $result_reg = $this->student_grade_model->get_others_sub_marks($admn_no, trim($tsem), $etype->type);
                    //print_r($result_reg);
                    $result_reg_final = $this->stu_details->get_final_details($result_reg[0]->foil_id);
                    //print_r($result_reg_final);die();
                    $j = 0;
                    if (!empty($result_reg)) {


                        foreach ($result_reg as $r) {
                            //print_r($r->grade);
                            $data[$keyone]['result'][$j]['subje_name'] = $r->name;
                            $data[$keyone]['result'][$j]['crdhrs'] = $r->cr_hr;
                            // $data[$keyone]['result'][$j]['crpts']=$r->cr_pts;
                            $p = $this->student_grade_model->get_grade_points($r->grade);
                            $data[$keyone]['result'][$j]['crpts'] = $p->points * $r->cr_hr;
                            $data[$keyone]['result'][$j]['grade'] = $r->grade;
                            $sy = $result_reg_final->session_yr;
                            // print_r($sy);die();
                            $sye = explode("-", $sy);
                            $sye1 = $sye[0] % 100;
                            $sye2 = $sye[1] % 100;
                            $sye3 = $sye1 . $sye2;
                            $data[$keyone]['result'][$j]['ysession'] = $sye3;

                            $s = $result_reg_final->session;
                            if ($s == "Monsoon") {
                                $ss = "MS";
                            }
                            if ($s == "Winter") {
                                $ss = "WS";
                            }
                            if ($s == "Summber") {
                                $ss = "ZS";
                            }
                            $data[$keyone]['result'][$j]['wsms'] = $ss;
                            $data[$keyone]['result'][$j]['examtype'] = ( ++$data[$keyone]['result'][$j]['examtype']); //"Other";
                            $data[$keyone]['result'][$j]['gpa'] = $result_reg_final->gpa;
                            $data[$keyone]['result'][$j]['ogpa'] = $result_reg_final->cgpa;
                            $data['mis'] = "others";
                            $j++;
                        }
                    }
                }
            }
            //echo $count_rec;die();
            $data_result['result'][$count_rec] = $data;

            $count_rec++;
            $data = "";

            if ($count_rec == 3)
                break;
        }


        //print_r(count($data_result['result']));die();
        if ($id == 'pdf') {
            $this->load->helper(array('dompdf', 'file'));
            //$dompdf->set_paper(array(0,0,$width,$height));
            //$dompdf->set_paper("A4", "portrait");
            $ff = $this->load->view('transcript/print_transcript', array('stu_result' => $data_result), TRUE);
            pdf_create($ff, 'Transcript_of_', "", "M");
        }
        if ($id == 'excel') {
            $this->generate_excel($data_result);
        }
    }

    function generate_excel($data_result) {


        $tot_rec=count($data_result['result']);

        if (!empty($data_result)) {
            //CSV Library//
            $this->load->helper('download');
            $this->load->library('PHPExcel');
            $this->load->library('PHPExcel/IOFactory');

        
            //////////////////Setting///////////////////////
            $heading = array(
                'font' => array(
                    'bold' => true,
                    'color' => array('rgb' => '000000'),
                    'size' => 8,
                    'name' => 'Verdana',
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    )
            ));
            
            $hh = array(
                'font' => array(
                    'bold' => true,
                    'color' => array('rgb' => '000000'),
                    'size' => 7,
                    'name' => 'Calibri',
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    ),
                    'borders' => array(
                                         'allborders' => array(
                                                               'style' => PHPExcel_Style_Border::BORDER_THIN
                                                              )
                                       )
            ));
            $hn = array(
                'font' => array(
                    'color' => array('rgb' => '000000'),
                    'size' => 7,
                    'name' => 'Calibri',
                    'borders' => array(
                                         'allborders' => array(
                                                               'style' => PHPExcel_Style_Border::BORDER_THIN
                                                              )
                                       )
            ));
            $center =  array(
             'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
             'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
             'rotation'   => 0,
             'wrap'       => true
                );
            
            $wrap = array('wrap' => true, 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
             'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,);

            $datac = array(
                'font' => array(
                    'bold' => false,
                    'color' => array('rgb' => '000000'),
                    'size' => 8,
                    'name' => 'Verdana',
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    )
            ));

            $main = array(
                'font' => array(
                    'bold' => true,
                    'color' => array('rgb' => '000000'),
                    'size' => 9,
                    'name' => 'Verdana',
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    )
            ));
            ///////////////////////////////////////
            if ($data_result)
            {
                $objPHPExcel = new PHPExcel();
                
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
                
                  $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
                  $row=2;
                  $row1=2;
               foreach ($data_result['result'] as $rec){
                   
                 //  $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
                 //  
                 //   $objPHPExcel->getActiveSheet()->mergeCells('C2:G2');  
                    //-----------------------------------------------------------------------
                        
//                        $objPHPExcel->getProperties()
//                                ->setTitle("Student Report")
//                                ->setDescription("Student Report");
//                        $objPHPExcel->setActiveSheetIndex(0)
//                                ->setCellValue('A'.$row, $rec['tot_sem']);
                       
                        $row=$row+2;
                        $row1=$row1+2;
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('C'.$row, 'INDIAN SCHOOL OF MINES')
                                ->setCellValue('C'.($row+1), 'DHANBAD - 826004')
                                ->setCellValue('C'.($row+2), 'CONSOLIDATED GRADE CARD');
                                
                        $objPHPExcel->getActiveSheet()->mergeCells('C'.$row.':F'.$row);
                        $objPHPExcel->getActiveSheet()->mergeCells('C'.($row+1).':F'.($row+1));
                        $objPHPExcel->getActiveSheet()->mergeCells('C'.($row+2).':F'.($row+2));

                        $objPHPExcel->getActiveSheet()->getStyle('C'.$row)->applyFromArray($heading);
                        $objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getAlignment()->applyFromArray($center);
                        $objPHPExcel->getActiveSheet()->getStyle('C'.($row+1))->applyFromArray($heading);
                        $objPHPExcel->getActiveSheet()->getStyle('C'.($row+1))->getAlignment()->applyFromArray($center);
                        $objPHPExcel->getActiveSheet()->getStyle('C'.($row+2))->applyFromArray($heading);
                        $objPHPExcel->getActiveSheet()->getStyle('C'.($row+2))->getAlignment()->applyFromArray($center);
                            
                        $row =$row+4;
                        $row1 =$row1+4;  
                      
                        
                         $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.($row) , "Name: ")
                            ->setCellValue('B'.($row) , strtoupper($rec['stu_name'])) 
                            ->setCellValue('H'.($row) , "Admission No: ")    
                            ->setCellValue('I'.($row) , strtoupper($rec['admn_no']))         
                             ->setCellValue('A'.($row+1) , "Course: ")
                            ->setCellValue('B'.($row+1) , strtoupper($rec['cname']))
                            ->setCellValue('H'.($row+1) , "Branch: ") 
                            ->setCellValue('I'.($row+1) , strtoupper($rec['bname'])) ;        
                                 
                     
                        
                          $objPHPExcel->getActiveSheet()->mergeCells('B'.$row.':F'.$row);
                          
                          $objPHPExcel->getActiveSheet()->getStyle('A'.$row)->applyFromArray($hh);
                        
                          $objPHPExcel->getActiveSheet()->getStyle('B'.$row)->applyFromArray($hh);
                           
                             
                           $objPHPExcel->getActiveSheet()->getStyle('H'.$row)->applyFromArray($hh);
                                                  
                           $objPHPExcel->getActiveSheet()->getStyle('I'.$row)->applyFromArray($hh);
                         
                        
                                                   
                           $objPHPExcel->getActiveSheet()->mergeCells('I'.$row.':M'.$row);
                        
                          

                          
                           $objPHPExcel->getActiveSheet()->mergeCells('B'.($row+1).':F'.($row+1));
                           $objPHPExcel->getActiveSheet()->mergeCells('I'.($row+1).':M'.($row+1));
                           
                             $objPHPExcel->getActiveSheet()->getStyle('A'.$row+1)->applyFromArray($hh);
                       
                          $objPHPExcel->getActiveSheet()->getStyle('B'.$row+1)->applyFromArray($hh);
                           
                           $objPHPExcel->getActiveSheet()->getStyle('H'.$row+1)->applyFromArray($hh);
                                                  
                           $objPHPExcel->getActiveSheet()->getStyle('I'.$row+1)->applyFromArray($hh);
                             
                         //------------------------------------------------------
                       $row=$row+2;
                       $row1=$row1+2;
                        // $row3=12;
                         //$row4=12;
                        $cum_total_credit=0;
                        $cum_total_points=0;
                        $flag_overall=0;
                        $m="";
                        $space=str_repeat(' ', 2);
                       
                         for($key=0;$key<$rec['tot_sem'];$key++)
                         {
                             
                              if(isset($rec[$key]))
                              { 
                                  
                                
                                $psy=$rec[$key]['result'][0]['ysession'];
                                   $psy1=intval($psy/100);
                                   $psy2=$psy%100;
                                   $sy="20".$psy1."-".$psy2;
                                //---------------------------------------------------

                                switch($sess=$rec[$key]['result'][0]['wsms'])
                               {


                                   case "MS":
                                           $sess = "Monsoon " ;
                                               break;
                                    case "WS":
                                           $sess = "Winter ";
                                               break;     
                                    case "ZS":
                                           $sess = "Summer ";
                                               break;
                               }  

                            //---------------------------------------------------
                     
                                    switch(trim($rec[$key]['result'][0]['examtype']))
                                   {
                                       case 'R':
                                           $et1 = "Regular";
                                           break;
                                       case 'S':
                                           $et1 = "Special";
                                           break;
                                       case 'T':
                                           $et1 = "Carry1";
                                           break;
                                       case 'U':
                                           $et1 = "Carry1 Special";
                                           break;
                                       case 'V':
                                           $et1 = "Carry2";
                                           break;
                                       case 'W':
                                           $et1 = "Carry2 Special";
                                           break;
                                       case 'X':
                                           $et1 = "Carry3";
                                           break;
                                       case 'Y':
                                           $et1 = "Carry2 Special";
                                           break;
                                       default:
                                          $et1 = "Error";

                                   }
                                  
                                   if(($key+1)%2 == 1)
                                  { 
                                       
                                   $a=count($rec[$key]['result']);
                                   $b=count($rec[$key+1]['result']);
                                   if($a>=$b)
                                   {
                                   $count=$a;
                                   }         
                                   if($b>=$a)
                                   {
                                   $count=$b;
                                   }
                                      $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('A'.($row) , strtoupper(($key+1)." Semester Examination"))
                                    ->setCellValue('D'.($row) , strtoupper($sy))
                                    ->setCellValue('E'.($row) , strtoupper($sess))
                                    ->setCellValue('F'.($row) , strtoupper($et1));
                                    $objPHPExcel->getActiveSheet()->getStyle('A'.($row))->applyFromArray($hh);
                                    $objPHPExcel->getActiveSheet()->getStyle('D'.($row))->applyFromArray($hh);
                                    $objPHPExcel->getActiveSheet()->getStyle('E'.($row))->applyFromArray($hh);
                                    $objPHPExcel->getActiveSheet()->getStyle('F'.($row))->applyFromArray($hh);
                                    $objPHPExcel->getActiveSheet()->mergeCells('A'.($row).':C'.($row));  
                                    $row++;
                                    $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('A'.($row) , strtoupper("Subject"))
                                    ->setCellValue('D'.($row) , strtoupper("Cr. Hrs."))
                                    ->setCellValue('E'.($row) , strtoupper("Cr. Pts."))
                                    ->setCellValue('F'.($row) , strtoupper("Grade"));
                                    //----------------------------------------------------------
                                    $objPHPExcel->getActiveSheet()->getStyle('A'.($row))->applyFromArray($hh);
                                    $objPHPExcel->getActiveSheet()->getStyle('D'.($row))->applyFromArray($hh);
                                     $objPHPExcel->getActiveSheet()->getStyle('D'.($row))->getAlignment()->applyFromArray($center);
                                    $objPHPExcel->getActiveSheet()->getStyle('E'.($row))->applyFromArray($hh);
                                       $objPHPExcel->getActiveSheet()->getStyle('E'.($row))->getAlignment()->applyFromArray($center);
                                    $objPHPExcel->getActiveSheet()->getStyle('F'.($row))->applyFromArray($hh);
                                            $objPHPExcel->getActiveSheet()->getStyle('F'.($row))->getAlignment()->applyFromArray($center);
                                
                                    $objPHPExcel->getActiveSheet()->mergeCells('A'.($row).':C'.($row));  
                                    
                                    $flag=0;
                                    $total_credit=0;
                                    $total_points=0;
                                      
                                    $mstatus="INC";
                                    $ostatus="PASS";
                                   
                                    
                                            
                                    for($x=0;$x<$count;$x++) // inner loop
                                    {
                                        $head++;
                                        $row++;
                                        if(!empty($rec[$key]['result'][$x]))
                                        {
                                            $total_credit+=$rec[$key]['result'][$x]['crdhrs'];
                                            $total_points+=($rec[$key]['result'][$x]['crpts']);

                                            if($rec[$key]['result'][$x]['grade']=='F'){$flag=1;$flag_overall=1;}
                                            
                                        }
                                        if(!empty($rec[$key]['result'][$x]))
                                        {
                                            
                                     $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('A'.($row) , $space.$rec[$key]['result'][$x]['subje_name'])
                                    ->setCellValue('D'.($row) , $space.$rec[$key]['result'][$x]['crdhrs'])
                                    ->setCellValue('E'.($row) , $space.$rec[$key]['result'][$x]['crpts'])
                                    ->setCellValue('F'.($row) , $space.$rec[$key]['result'][$x]['grade']);
                                           
                                   $objPHPExcel->getActiveSheet()->getStyle('A'.($row))->applyFromArray($hn);
                                  
                                    $objPHPExcel->getActiveSheet()->getStyle('D'.($row))->applyFromArray($hn);
                                     $objPHPExcel->getActiveSheet()->getStyle('D'.($row))->getAlignment()->applyFromArray($center);
                                    $objPHPExcel->getActiveSheet()->getStyle('E'.($row))->applyFromArray($hn);
                                     $objPHPExcel->getActiveSheet()->getStyle('E'.($row))->getAlignment()->applyFromArray($center);
                                    $objPHPExcel->getActiveSheet()->getStyle('F'.($row))->applyFromArray($hn);
                                     $objPHPExcel->getActiveSheet()->getStyle('F'.($row))->getAlignment()->applyFromArray($center);
                                    
                                            $objPHPExcel->getActiveSheet()->mergeCells('A'.($row).':C'.($row));  
                                            $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(13);
                                            
                                        }
                                        else
                                        {
                                            
                                        }
                                        
                                    }
                                    $row++;
                                    
                                      $pf=($flag==0)?"PASS":"INC";
                                      ($flag==0)?"":$m.=($key+1).",";
                                      
                                        $status= "Result: ".$pf;
                                        $gpa_status="GPA: ".$rec[$key]['result'][0]['gpa'];
                                        $cgpa_status="CGPA: ".$rec[$key]['result'][0]['ogpa'];
                                    $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('A'.($row) , $status )
                                    ->setCellValue('B'.($row) , $gpa_status)
                                    ->setCellValue('C'.($row) , $cgpa_status)
                                    ->setCellValue('D'.($row) , $total_credit)
                                     ->setCellValue('E'.($row) , $total_points);
                                  //-----------------------------------------------------  
                                    $cum_total_credit=$cum_total_credit+$total_credit;
                                    $cum_total_points=$cum_total_points+$total_points;

                                   
                                  //-----------------------------------------------------  
                                    $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(13);
                                      $objPHPExcel->getActiveSheet()->getStyle('A'.($row))->applyFromArray($hh);
                                      $objPHPExcel->getActiveSheet()->getStyle('A'.($row))->getAlignment()->applyFromArray($center);
                                    $objPHPExcel->getActiveSheet()->getStyle('B'.($row))->applyFromArray($hh);
                                    $objPHPExcel->getActiveSheet()->getStyle('B'.($row))->getAlignment()->applyFromArray($center);
                                    $objPHPExcel->getActiveSheet()->getStyle('C'.($row))->applyFromArray($hh);
                                    $objPHPExcel->getActiveSheet()->getStyle('C'.($row))->getAlignment()->applyFromArray($center);
                                    $objPHPExcel->getActiveSheet()->getStyle('D'.($row))->applyFromArray($hh);
                                    $objPHPExcel->getActiveSheet()->getStyle('D'.($row))->getAlignment()->applyFromArray($center);
                                     $objPHPExcel->getActiveSheet()->getStyle('E'.($row))->applyFromArray($hh);
                                     $objPHPExcel->getActiveSheet()->getStyle('E'.($row))->getAlignment()->applyFromArray($center);
                                    
                                    //---------------------------------------------------------------
                                      $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(3); 
                                      
                                  $row++;$row++;
                              
                                  }
                                else
                                {
                                     $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('H'.($row1) , strtoupper(($key+1)." Semester Examination"))
                                    ->setCellValue('K'.($row1) , strtoupper($sy))
                                    ->setCellValue('L'.($row1) , strtoupper($sess))
                                    ->setCellValue('M'.($row1) , strtoupper($et1));
                                     $objPHPExcel->getActiveSheet()->getStyle('H'.($row1))->applyFromArray($hh);
                                    $objPHPExcel->getActiveSheet()->getStyle('K'.($row1))->applyFromArray($hh);
                                    $objPHPExcel->getActiveSheet()->getStyle('L'.($row1))->applyFromArray($hh);
                                    $objPHPExcel->getActiveSheet()->getStyle('M'.($row1))->applyFromArray($hh);
                                    $objPHPExcel->getActiveSheet()->mergeCells('H'.($row1).':J'.($row1));  
                                    $row1++;
                                    $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('H'.($row1) , strtoupper("Subject"))
                                    ->setCellValue('K'.($row1) , strtoupper("Cr. Hrs."))
                                    ->setCellValue('L'.($row1) , strtoupper("Cr Pts."))
                                    ->setCellValue('M'.($row1) , strtoupper("Grade"));
                                    //----------------------------------------------------------
                                    
                                     $objPHPExcel->getActiveSheet()->getStyle('H'.($row1))->applyFromArray($hh);
                                    $objPHPExcel->getActiveSheet()->getStyle('K'.($row1))->applyFromArray($hh);
                                    $objPHPExcel->getActiveSheet()->getStyle('K'.($row1))->getAlignment()->applyFromArray($center);
                                    $objPHPExcel->getActiveSheet()->getStyle('L'.($row1))->applyFromArray($hh);
                                    $objPHPExcel->getActiveSheet()->getStyle('L'.($row1))->getAlignment()->applyFromArray($center);
                                    $objPHPExcel->getActiveSheet()->getStyle('M'.($row1))->applyFromArray($hh);
                                    $objPHPExcel->getActiveSheet()->getStyle('M'.($row1))->getAlignment()->applyFromArray($center);
                                    
                                    $objPHPExcel->getActiveSheet()->mergeCells('H'.($row1).':J'.($row1));  
                                    
                                     $flag=0;
                                    $total_credit=0;
                                    $total_points=0;
                                    for($x=0;$x<$count;$x++) // inner loop
                                    {
                                        
                                        $row1++;
                                        if(!empty($rec[$key]['result'][$x]))
                                        {
                                            $total_credit+=$rec[$key]['result'][$x]['crdhrs'];
                                            $total_points+=($rec[$key]['result'][$x]['crpts']);

                                            if($rec[$key]['result'][$x]['grade']=='F'){$flag=1;$flag_overall=1;}
                                        }
                                        if(!empty($rec[$key]['result'][$x]))
                                        {
                                            
                                            $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('H'.($row1) , $space.$rec[$key]['result'][$x]['subje_name'])
                                    ->setCellValue('K'.($row1) , $space.$rec[$key]['result'][$x]['crdhrs'])
                                    ->setCellValue('L'.($row1) , $space.$rec[$key]['result'][$x]['crpts'])
                                    ->setCellValue('M'.($row1) , $space.$rec[$key]['result'][$x]['grade']);
                                            
                                            
                                             $objPHPExcel->getActiveSheet()->getStyle('H'.($row1))->applyFromArray($hn);
                                    $objPHPExcel->getActiveSheet()->getStyle('K'.($row1))->applyFromArray($hn);
                                    $objPHPExcel->getActiveSheet()->getStyle('K'.($row1))->getAlignment()->applyFromArray($center);
                                    $objPHPExcel->getActiveSheet()->getStyle('L'.($row1))->applyFromArray($hn);
                                    $objPHPExcel->getActiveSheet()->getStyle('L'.($row1))->getAlignment()->applyFromArray($center);
                                    $objPHPExcel->getActiveSheet()->getStyle('M'.($row1))->applyFromArray($hn);
                                    $objPHPExcel->getActiveSheet()->getStyle('M'.($row1))->getAlignment()->applyFromArray($center);
                                            $objPHPExcel->getActiveSheet()->mergeCells('H'.($row1).':J'.($row1));  
                                            $objPHPExcel->getActiveSheet()->getRowDimension($row1)->setRowHeight(13);
                                            
                                        }
                                        else
                                        {
                                            
                                        }
                                    }
                                    
                                     $row1++;
                                    
                                      $pf=($flag==0)?"PASS":"INC";
                                      ($flag==0)?"":$m.=($key+1).",";
                                        $status= "Result: ".$pf;
                                          $gpa_status="GPA: ".$rec[$key]['result'][0]['gpa'];
                                        $cgpa_status="CGPA: ".$rec[$key]['result'][0]['ogpa'];
                                    $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('H'.($row1) , $status )
                                    ->setCellValue('I'.($row1) , $gpa_status)
                                    ->setCellValue('J'.($row1) , $cgpa_status)
                                    ->setCellValue('K'.($row1) , $total_credit)
                                     ->setCellValue('L'.($row1) , $total_points);
                                    
                                    //-------------------------------------------------
                                    
                                    $cum_total_credit=$cum_total_credit+$total_credit;
                                    $cum_total_points=$cum_total_points+$total_points;
//                                    if($flag==0){
//                                        $m=$m+($key+1).",";
//                                    }
                                   
                                    //---------------------------------------------------
                                    
                                    
                                    $objPHPExcel->getActiveSheet()->getRowDimension($row1)->setRowHeight(13);
                                     $objPHPExcel->getActiveSheet()->getStyle('H'.($row1))->applyFromArray($hh);
                                     $objPHPExcel->getActiveSheet()->getStyle('H'.($row1))->getAlignment()->applyFromArray($center);
                                    $objPHPExcel->getActiveSheet()->getStyle('I'.($row1))->applyFromArray($hh);
                                    $objPHPExcel->getActiveSheet()->getStyle('I'.($row1))->getAlignment()->applyFromArray($center);
                                    $objPHPExcel->getActiveSheet()->getStyle('J'.($row1))->applyFromArray($hh);
                                    $objPHPExcel->getActiveSheet()->getStyle('J'.($row1))->getAlignment()->applyFromArray($center);
                                    $objPHPExcel->getActiveSheet()->getStyle('K'.($row1))->applyFromArray($hh);
                                    $objPHPExcel->getActiveSheet()->getStyle('K'.($row1))->getAlignment()->applyFromArray($center);
                                     $objPHPExcel->getActiveSheet()->getStyle('L'.($row1))->applyFromArray($hh);
                                     $objPHPExcel->getActiveSheet()->getStyle('L'.($row1))->getAlignment()->applyFromArray($center);
                                    //---------------------------------------------------------------
                                    $row1++; $row1++;
                                     $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(3);
                                }
                                   
                                  
                               
                                
                                
                              } //if 
                             
                             $head++;$head++;
                            //  print_r($cum_total_credit);print_r($cum_total_points);
                             
                         }//for
                         
                          
                         //-------------------------Last Line----------------
                                    $pf=($flag==0)?"PASS":"INC";
                                    if($flag_overall!=0){
                                          $ogpa=$m." INC";
                                    }
                                    else
                                    {
                                      
                                        $ogpa=number_format($cum_total_points/$cum_total_credit,2);
                                    }
                                    $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('A'.($row1) , strtoupper("Result:"))
                                    ->setCellValue('B'.($row1) , strtoupper($pf))
                                    ->setCellValue('C'.($row1) , strtoupper("Cumm_Hrs"))
                                    ->setCellValue('D'.($row1) , $cum_total_credit)
                                    ->setCellValue('E'.($row1) , strtoupper("Cumm_Points:"))
                                    ->setCellValue('F'.($row1) , strtoupper($cum_total_points))
                                    ->setCellValue('H'.($row1) , strtoupper("OGPA: "))
                                    ->setCellValue('I'.($row1) , $ogpa);
                                //    ->setCellValue('J'.($row1) , strtoupper("Status:"))
                                 //   ->setCellValue('K'.($row1) , strtoupper($m."INC"));
                                    
                                    
                                    $objPHPExcel->getActiveSheet()->getStyle('A'.($row1))->applyFromArray($heading);
                                    $objPHPExcel->getActiveSheet()->getStyle('B'.($row1))->applyFromArray($heading);
                                    $objPHPExcel->getActiveSheet()->getStyle('C'.($row1))->applyFromArray($heading);
                                    $objPHPExcel->getActiveSheet()->getStyle('D'.($row1))->applyFromArray($heading);
                                    $objPHPExcel->getActiveSheet()->getStyle('E'.($row1))->applyFromArray($heading);
                                    $objPHPExcel->getActiveSheet()->getStyle('F'.($row1))->applyFromArray($heading);
                                    $objPHPExcel->getActiveSheet()->getStyle('H'.($row1))->applyFromArray($heading);
                                    $objPHPExcel->getActiveSheet()->getStyle('I'.($row1))->applyFromArray($heading);
                                    $objPHPExcel->getActiveSheet()->getStyle('J'.($row1))->applyFromArray($heading);
                                    $objPHPExcel->getActiveSheet()->getStyle('K'.($row1))->applyFromArray($heading);
                                    
                                    $objPHPExcel->getActiveSheet()->getStyle('A'.($row1))->applyFromArray($hn);
                                    $objPHPExcel->getActiveSheet()->getStyle('B'.($row1))->applyFromArray($hn);
                                    $objPHPExcel->getActiveSheet()->getStyle('C'.($row1))->applyFromArray($hn);
                                    $objPHPExcel->getActiveSheet()->getStyle('D'.($row1))->applyFromArray($hn);
                                    $objPHPExcel->getActiveSheet()->getStyle('E'.($row1))->applyFromArray($hn);
                                    $objPHPExcel->getActiveSheet()->getStyle('F'.($row1))->applyFromArray($hn);
                                    $objPHPExcel->getActiveSheet()->getStyle('H'.($row1))->applyFromArray($hn);
                                    $objPHPExcel->getActiveSheet()->getStyle('I'.($row1))->applyFromArray($hn);
                                    $objPHPExcel->getActiveSheet()->getStyle('J'.($row1))->applyFromArray($hn);
                                    $objPHPExcel->getActiveSheet()->getStyle('K'.($row1))->applyFromArray($hn);
                                    
                                    
                                    
                                     $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('J'.($row1+5) , strtoupper("DR Exam"));
                                     
                                      $objPHPExcel->setActiveSheetIndex(0)
                                     ->setCellValue('A'.($row+6), "999");
                                    
                                    $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('B'.($row+6), $rec['tot_sem']);
                                    
                                     
                                    $row=$row+7;
                                    $row1=$row1+7;
                                    
                                    //$row++; $row1++;$row++; $row1++;
                                    
                                   
                                    
                         
                         
                         //-----------------------------------------------------                  
                    
                    //$row=$row+10;
                   
               }                    // shows Number of rows in AA1
                                    $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('AA1' , $row);
                                    $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('AB1' , round($row/$tot_rec));
                                     $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('AC1' , $tot_rec);
                                     $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('AD1', $rec['tot_sem']);
                                     
                                     
                                           
               
         

             //-----------------Writing to Excel Must Starts-----------------------   
                $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

                if (!is_dir('./assets/sem_report/')) {
                    mkdir('./assets/sem_report/', 0777, true);
                }
                $output_file = './assets/sem_report/Stu_Report_' . $dept . ".xls";
                $objWriter->save($output_file);

                header('Content-Type: application/csv');
                header('Content-Disposition: attachment;  filename=student_list.xls');
                header('Content-Length: ' . filesize($output_file));
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                ob_get_clean();
                echo file_get_contents($output_file);
                ob_end_flush();
             //-----------------Writing to Excel Must Ends-----------------------   
            }
        } else {
            $this->session->set_flashdata('flashError', ' Record Not Found.');
            redirect('/transcript/reports/index', 'refresh');
        }
    }

//-------------------------------Excel End-------------------------------------------------------------------------
}

?>