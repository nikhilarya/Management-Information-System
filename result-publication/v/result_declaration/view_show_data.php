<style>
    @page { margin: 80px 50px 160px 50px; font-size: 12px; }
    body{ font-size: 11px; }
    #header { position: fixed; left: 0px; top: -190px; right: 0px; height: 80px; text-align: center; }
    #footer { position: fixed; left: 0px; bottom: -100px; right: 0px; height: 100px;  }
    #footer .page:after { content: counter(page, upper-roman); }
    h2{ font:16px; font-weight:bold;}
    table{ width:100%;}
    /*.form-control{ width:50px;}*/
    .pagenum:before {
        content: counter(page);
    }
    .table{border: 1px solid #000; border-collapse:collapse;}
    .table td,.table tr,.table th{border: 1px solid #000;} 
</style> 
<?php
if (count($result_list) <= 25) {
    $page = '1';
} else {
    $page = count($result_list) / 32;
}
$vdid=($vdid==null?$this->input->post('vdid'):$vdid);
$cnm = $this->sbasic_model->getCourseById($course_id);
$bnm = $this->sbasic_model->getBranchById1($branch_id);
$deptname=$this->sbasic_model->getDepatmentById($vdid);
 
?>
<div class='header'> 
    <div class="box box-solid box-primary">
        <h2 align="center"> INDIAN SCHOOL OF MINES, DHANBAD</h2>
        <h2 align="center" class="page-header">Result Notification (<?php echo strtoupper($exam_type) ?>-<?php echo $sess ?>-<?php echo $syear ?>)</h2>
        <h5><center> <?php echo  (($this->input->post('published')=='01-01-1970' || $this->input->post('published')==''|| $this->input->post('published')==null)? '': 'Published On - ' .$this->input->post('published'))?></center></h5>
        <h4><center><?php  echo ($exam_type == 'jrf'?'Department - ' . $deptname->name ."<br/>": $sem. "- Semester  -");?><?php echo (($cnm->name == 'Executive Master of Business Administration' || $cnm->name == 'Master of Business Administration' ) ? 'Master of Business Administration' : $cnm->name); ?> (<?php echo ($cnm->name == 'Executive Master of Business Administration' ? 'Three Years' : ($cnm->name == 'Master of Business Administration' ? 'Two Years' : ($vdid == 'comm' ? $bnm->name . " / Section-" . $sec_nm : (($sem >= 5 && $course_id == 'minor' ? 'MINOR - ' : '') . $bnm->name) ))); ?>)</center></h4>
        <hr> 
        <h6>Following candidate(s) is/are declared to have Passed/Failed in the above mentioned examination. </h6>
    </div>
</div>      
<div id="footer" class="page">
    <table border="0" style="">
        <tr>
            <td><div align='right' style='padding-left:5px;'><p><i>** System generated information  doesn't require any signature. ** </i><p></div></td>
        </tr>
        <tr>
            <td align="center">Page: <span class="pagenum"></span><!--/<?php echo ceil($page); ?>--></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
    </table>
</div>
<div class="row">
    <div class="col-md-12 ">         
        <table class="table table-bordered" id="fy_show">
            <thead>
                <tr> 
                    <td colspan="<?php echo ( ($exam_type == 'jrf')?'7': ( ($sem >= 5 and $course_id == 'b.tech') ? 8 : (( ($sem >= 5 and $course_id == 'minor') || ($exam_type == 'spl')|| ($exam_type == 'other') ) ? 7 : 6))) ?>">
                        <center>
                               <?php echo ($exam_type == 'jrf'?'Department - ' . $deptname->name ."<br/>": $sem. "- Semester  -");?><?php echo (($cnm->name == 'Executive Master of Business Administration' || $cnm->name == 'Master of Business Administration' ) ? 'Master of Business Administration' : $cnm->name); ?> ( <?php echo ($cnm->name == 'Executive Master of Business Administration' ? 'Three Years' : ($cnm->name == 'Master of Business Administration' ? 'Two Years' : ($vdid == 'comm' ? $bnm->name . " / Section-" . $sec_nm : (($sem >= 5 && $course_id == 'minor' ? 'MINOR - ' : '') . $bnm->name) ))); ?>)
                        
                        </center>
                     <?php echo "Total Students: " . $stu_ctr ?>
                    </td>                                                                        
                </tr>
                <tr>
                    <td align="center">Sr.No</td>
                    <td align="center">Adm No.</td>
                    <td align="center">Student Name</td>
                    <?php if ($sem >= 5 and $course_id == 'minor') { ?>
                        <td align="center">Department</td>
                    <?php } ?>
                    <td align="center"><?php  echo ($exam_type == 'jrf' ? "Subjects / Grade":"GPA");?></td>
                    <?php if ($exam_type == 'other') { ?>
                        <td align="center">CGPA</td>
                    <?php } ?>
                    <td align="center">Remarks</td>
<?php if ($sem >= 5 and $course_id == 'b.tech') { ?>
                        <td align="center">GPA with Hons.</td>
                        <td align="center">Remarks with Hons.</td>
                <?php } ?>						
                    <td align="center">No. Of Failed Subjects</td>						                        	
                      <?php if($exam_type == 'jrf'){?>
                    <td align="center">No. Of Passed Subjects</td>						                        	
                    <?php }?>
                </tr>
            </thead>		
            <tbody>                    
<?php
$i = 0;
 //print_r($result_list); die();
foreach ($result_list as $b) {
    ?>
                    <tr>
                        <td align="center"><?php echo $i + 1; ?></td>
                        <td><?php echo strtoupper($b->admn_no); ?></td>
                        <td><?php echo ($b->st_name); ?></td>
                        <?php if ($sem >= 5 and $course_id == 'minor') { ?>
                            <td><?php echo ($b->dept_name); ?></td>
                        <?php } ?>
                        <?php if($exam_type == 'jrf'){?>
                            <td align="left" nowrap="nowrap"><?php   echo nl2br($b->sublist) ?></td>
                        <?php } else{ ?>
                            <td align="center"><?php echo $b->core_GPA == 'INC' ? $b->core_GPA : round($b->core_GPA, 2); ?></td>
                        <?php }?>
    <?php if ($exam_type == 'other') { ?>                            
                            <td><?php $pow = pow(10, 2);
        echo ($b->core_GPA == 'INC' ? 'INC' : ($b->cgpa == null ? 'N/A' : (int) (($b->cgpa * $pow)) / $pow )); ?></td>
                            <?php } ?>           						   


                        <td align="center">
                            <?php
                            if ($b->core_status == "FAIL") {
                                $txt = null;
                                $txt = '<b style="color:red;">' . ($b->core_status) . '</b>:';
                                $f_sub_list = null;
                                $f_sub_list = explode(',', $b->core_fail_sub_list);
                                for ($k = 0; $k < count($f_sub_list); $k++)
                                    $txt.= (($k == 4 or $k == 8 or $k == 12 or $k == 16) ? '<br>' : '') . $f_sub_list[$k] . ($k == count($f_sub_list) - 1 ? '' : ',');
                                echo ($txt == '<b style="color:red;">' . ($b->core_status) . '</b>:' ? "<b style='color:red;'>FAIL</b> in Aggregate" : $txt);
                            } else {
                                ?>
                                <?php echo ($b->core_status); ?>
                            <?php } ?>
                        </td>

                            <?php if ($sem >= 5 and $course_id == 'b.tech') { ?>
                            <td align="center"><?php echo $b->GPA_with_H == 'INC' ? $b->GPA_with_H : round($b->GPA_with_H, 2); ?></td>
                            <td align="center">
                                <?php
                                if ($b->H_status == "FAIL") {
                                    $txt = null;
                                    $txt = '<b style="color:red;">' . ($b->H_status) . '</b>:';
                                    $f_sub_list = null;
                                    $f_sub_list = explode(',', $b->H_fail_sub_list);
                                    for ($k = 0; $k < count($f_sub_list); $k++)
                                        $txt.= (($k == 4 or $k == 8 or $k == 12 or $k == 16) ? '<br>' : '') . $f_sub_list[$k] . ($k == count($f_sub_list) - 1 ? '' : ',');
                                    echo $txt;
                                } else {
                                    ?>
            <?php echo ($b->H_status); ?>
        <?php } ?>
                            </td>
                    <?php } ?>
                        <td align="center">
                    <?php
                    echo ((($sem >= 5 and $course_id == 'minor') || ($exam_type == 'other')||  ($exam_type == 'spl')||($exam_type == 'jrf')) ? $b->count_core_failed_sub :
                            (($b->count_H_failed_sub == '0' || $b->count_H_failed_sub == 'N/A') ? $b->count_core_failed_sub : $b->count_core_failed_sub . "+" . $b->count_H_failed_sub . "(H)" )
                    );
                     
                    ?> </td>
                  
                     <?php if($exam_type == 'jrf'){?>
                       <td align="center">
                         <?php echo $b->count_core_passed_sub;
                       } ?>
                    </tr>
    <?php
    $i++;
}
?>
            </tbody>	

        </table>
    <!--<div align='right' style='padding-left:5px;'><p><i>** System generated information  doesn't require any signature. ** </i><p></div>
    <div align='right'><p>Deputy Registrar(Academic & Examination)</p></div>-->

        <!--</div>-->

    </div>
</div>


<script>
    $(function () {

        $('#fy_show').dataTable();



    });
</script>


