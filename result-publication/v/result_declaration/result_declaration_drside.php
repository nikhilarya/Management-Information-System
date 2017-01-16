<?php
$sy = $this->input->post('session_year'); 
$sess = $this->input->post('session');
$et = $this->input->post('exm_type');
$deptid = $this->input->post('dept');
$sec_name = $this->input->post('section_name');
$rd_type = $this->input->post('rd_type');
 
?>
<input type="hidden" id="hsyear" name="syear" value="<?php echo $sy; ?>">
<input type="hidden" id="hsess" name="hsess" value="<?php echo $sess; ?>">
<input type="hidden" id="hetype" name="hetype" value="<?php echo $et; ?>">
<input type="hidden" id="hdeptid" name="hdeptid" value="<?php echo $deptid; ?>">
<input type="hidden" id="hsec_name" name="hsec_name" value="<?php echo $sec_name; ?>">
<input type="hidden" id="rd_type" name="rd_type" value="<?php echo $rd_type; ?>">
<div class="nav-tabs-custom">
    <div class="tab-content">        
        <div class="row">                
            <?php
            $ui = new UI();
            $col = $ui->col()->width(4)->open();
            $col->close();
            $col = $ui->col()->width(4)->open();
            $row = $ui->row()->open();
            $box1 = $ui->box()->title('Result Declaration')->uiType('primary')->solid()->open();
            $form = $ui->form()->action('result_declaration/result_declaration_drside/show_details')->open();
            $ui->select()
                    ->label('Session Year')
                    ->name('session_year')
                    ->id('session_year_attendance')
                    ->classes('gS')
                    ->required()
                    //if($sy) $ui->select()->options(array($ui->option()->value($sy)->text($sy)->selected()));
                  // else
                    ->options(array($ui->option()->value('0')->text('Select')->disabled()))
                   ->show();
            if($sess){
             echo '<label class="control-label">Session</label>' . form_dropdown('session', array('Monsoon'=>'Monsoon','Winter'=>'Winter','Summer'=>'Summer'), $sess, 'id="session_attendance"  class="form-control" required="required"');    
            }else{
            $ui->select()
                    ->label('Session')
                    ->name('session')
                    ->id('session_attendance')
                    ->required()
                    -> options(array($ui->option()->value('0')->text('Select')->selected(),
                        $ui->option()->value('Monsoon')->text('Monsoon'),
                        $ui->option()->value('Winter')->text('Winter'),
                        $ui->option()->value('Summer')->text('Summer')))
                    ->show();
            }
            
           if($et) {
            echo '<label class="control-label">Exam Type</label>' . form_dropdown('exm_type', array('regular'=>'Regular','other'=>'Other','spl'=>'Special','jrf'=>'JRF','prep'=>'PREP'), $et, 'id="exm_type"  class="form-control" required="required"');      
           }else{
            $ui->select()
                    ->label('Exam Type')
                    ->name('exm_type')
                    ->id('exm_type')
                    ->required()
                    ->options(array($ui->option()->value('0')->text('Select')->selected(),
                        $ui->option()->value('regular')->text('Regular'),
                        $ui->option()->value('other')->text('Other'),
                        $ui->option()->value('spl')->text('Special'),
                        $ui->option()->value('jrf')->text('JRF'),
                        $ui->option()->value('prep')->text('PREP')
                    ))
                    ->show(); 
           }
            echo '<label class="control-label">Department</label>' . form_dropdown('dept', $deptList, (($deptid)?$deptid:$dept_sel), 'id="dept"  class="form-control" required="required"');
            echo "<div id='sec'> <label class='control-label'>Section</label>";            
            echo form_dropdown('section_name', array('all'=>'All'),$sec_name, 'id="section_id"  class="form-control" ');           
            echo "</div>";
            if($rd_type) {
           echo '<label class="control-label">Exam Type</label>' . form_dropdown('rd_type',((in_array("hod", $this->session->userdata('auth')) or in_array("ft", $this->session->userdata('auth')))?array('F'=>'Declared'): array('W'=>'Pending','F'=>'Declared')), $rd_type, 'id="rd_type"  class="form-control" required="required"');      
           }else{
            if(in_array("hod", $this->session->userdata('auth')) or in_array("ft", $this->session->userdata('auth'))){
				 $ui->select()
                    ->label('Result Declaration Type')
                    ->name('rd_type')
                    ->id('rd_type')
                    ->required()
                    ->options(array(
                        $ui->option()->value('F')->text('Declared')
                       
                    ))
                    ->show();
			}else{
            $ui->select()
                    ->label('Result Declaration Type')
                    ->name('rd_type')
                    ->id('rd_type')
                    ->required()
                    ->options(array($ui->option()->value('0')->text('Select')->selected(),
                        $ui->option()->value('W')->text('Pending'),
                        $ui->option()->value('F')->text('Declared'),
                       
                    ))
                    ->show();
           } 
		  } 
            $ui->button()
                    ->submit(true)
                    ->value("Submit")
                    ->id('submit')
                    ->uiType("primary")
                    ->show();

            $form->close();
            $box1->close();
            $row->close();
            $col->close();
            ?>
        </div>

    </div>      
</div>

<!-- Table Creation-->
<?php if (!empty($dept_list)) { ?>
    <div class="box box-solid box-primary">
        <div class="table-responsive">				
           <h2 class="page-header"></h2>
           <div id='msg'></div>
            <table class="table table-bordered table-striped" id="ex_mod">
                <thead>
                    <tr>
                        <td align="center">Course</td>
                        <td align="center">Branch</td>
                        <td align="center">Semester</td>
                        <td align="center">Action</td>
                        <?php if (in_array("exam_dr", $this->session->userdata('auth'))) { ?>
                            <td align="center">Declaration Status</td>
                        <?php } ?>
                     </tr>
                </thead>
                <tbody>
                    <?php
                   // print_r($dept_list);
                    $i = 0;
                    foreach ($dept_list as $b) {
                       // $ps = $this->result_declaration_model->get_published_status($s_year, $sess, $dept_id, $b->course_id, $b->branch_id, $b->semester, $ex_type, ($dept_id == "comm" ? $b->group : null), $sec_name);
                        //echo $this->db->last_query(); 
                        //print_r($ps);                       
                        //$pst = $ps[0]['status'];
                        //print_r($pst);
                        ?>  
                        <tr>
                        <?php
                        echo form_open('result_declaration/result_declaration_drside/show_data_for_viewPDF');
                        ?> 
                    <input type="hidden" id="hsyear" name="syear" value="<?php echo $sy; ?>">
                    <input type="hidden" id="hsess" name="hsess" value="<?php echo $sess; ?>">
                    <input type="hidden" id="hetype" name="hetype" value="<?php echo $et; ?>">
                    <input type="hidden" id="hdeptid" name="hdeptid" value="<?php echo $deptid; ?>">
                    <input type="hidden" id="hsec_name" name="hsec_name" value="<?php echo $sec_name; ?>">
                    <input type="hidden" id="hcid-<?php echo $i; ?>" name="hcid" value="<?php echo ($b->course_id); ?>">
                    <input type="hidden" id="hbid-<?php echo $i; ?>" name="hbid" value="<?php echo ($b->branch_id); ?>">
                    <input type="hidden" id="hsem-<?php echo $i; ?>" name="hsem" value="<?php echo ($b->semester); ?>">
                    <input type="hidden" id="published-<?php echo $i; ?>" name="published" value="<?php echo date('d-m-Y', strtotime($b->published_on));?>">
                    

        <?php
        $btn_pdf = array(
            'type' => 'image',
            'src' => base_url() . 'assets/images/ism/pdf.jpg',
            'name' => 'submit',
            'width' => '15',
            'height' => '15',
            'value' => '',
            'class' => 'form-submit',
            'title' => 'Download Declared Result List in PDF Format '
        );
        ?>

                    <td width="15%"><?php echo ( ($b->course_nm == 'Executive Master of Business Administration' || $b->course_nm == 'Master of Business Administration' ) ? 'Master of Business Administration' : $b->course_nm); ?>
                       <!-- <input type="hidden" id="hcid-<?php echo $i; ?>" name="hcid" value="<?php echo ($b->course_id); ?>">-->
                    </td>
                    <td width="35%"><?php echo ($b->course_nm == 'Executive Master of Business Administration' ? 'Master of Business Administration(Three Years)' : ($b->course_nm == 'Master of Business Administration' ? 'Master of Business Administration(Two Years)' : ($deptid == 'comm' ? $b->branch_nm . " /Group-" . $b->group . " / Section-" . $sec_name : $b->branch_nm) )); ?>
                        <!--<input type="hidden" id="hbid-<?php echo $i; ?>" name="hbid" value="<?php echo ($b->branch_id); ?>">-->
                    </td>
                    <td width="10%" align="center"><?php echo ($b->course_id == 'jrf' ? 'N/A' : $b->semester); ?>
                        <!--<input type="hidden" id="hsem-<?php echo $i; ?>" name="hsem" value="<?php echo ($b->semester); ?>">-->
                    </td>
                    <td width="15%"  nowrap="nowrap" align="center">
                        <a href="" data-toggle="modal" value="" id="rv1" onclick="my_fun_view('<?php echo $i; ?>','<?php echo date('d-m-Y', strtotime($b->published_on))?>')" data-target="#viewreport2" title=" view Declared Result List" class="glyphicon glyphicon-zoom-in"></a> 
                        &nbsp;&nbsp;&nbsp;&nbsp;  
                        <a href="" <?php echo '<center><img src="' . base_url() . 'assets/images/ism/excel.jpg" width="15" height="15" /></center>'; ?></a>
                        &nbsp;&nbsp;&nbsp;&nbsp;                            
        <?php echo form_submit($btn_pdf); ?>
                    </td>
                        <?php if (in_array("exam_dr", $this->session->userdata('auth'))) { ?>
                        <?php if ($b->status == 1) { ?>
                            <td width="25%" align="center">
                                <!--<a class="label label-success" data-toggle="modal" value="" id="rv" onclick="my_fun_edit('<?php echo $i; ?>')" data-target="#viewreport"  >Declared on <?php echo date('d-m-Y', strtotime($b->published_on)); ?></a>-->
                                <label class='label label-success'>Declared on <?php echo date('d-m-Y', strtotime($b->published_on)); ?> </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="" onclick="my_fun_undo('<?php echo $b->id; ?>')" class="glyphicon glyphicon-repeat"></a>
                                &nbsp;<a class="label label-warning" data-toggle="modal"  title="Choose this button to held particular student(s) results"   value="" id="rv" onclick="pdeclare('<?php echo $i; ?>','<?php echo $b->id; ?>','<?php echo $b->id; ?>')" data-target="#viewreport1" >With-held</a></td>
                        <?php } else { ?>
                            <td width="25%" align="center"><a class="label label-warning" data-toggle="modal" value="" id="rv" onclick="my_fun_edit('<?php echo $i; ?>')" data-target="#viewreport"  >Pending</a></td>
            <?php }
        } ?>

                    <?php echo form_close(); ?>        

                    </tr>
                    <?php
                              $wh_redate= $this->result_declaration_model->get_with_held_republished_date_id($b->id);
                             //print_r($wh_redate);
                             if(count($wh_redate) > 0){ 
                                 foreach($wh_redate as $c){
                                           echo form_open('result_declaration/result_declaration_drside/show_data_for_PDF_redeclare');
                        ?> 
                    <input type="hidden" id="hsyear" name="syear" value="<?php echo $sy; ?>">
                    <input type="hidden" id="hsess" name="hsess" value="<?php echo $sess; ?>">
                    <input type="hidden" id="hetype" name="hetype" value="<?php echo $et; ?>">
                    <input type="hidden" id="hdeptid" name="hdeptid" value="<?php echo $deptid; ?>">
                    <input type="hidden" id="hsec_name" name="hsec_name" value="<?php echo $sec_name; ?>">
                    <input type="hidden" id="hcid-<?php echo $i; ?>" name="hcid" value="<?php echo ($b->course_id); ?>">
                    <input type="hidden" id="hbid-<?php echo $i; ?>" name="hbid" value="<?php echo ($b->branch_id); ?>">
                    <input type="hidden" id="hsem-<?php echo $i; ?>" name="hsem" value="<?php echo ($b->semester); ?>">

                     <input type="hidden" id="published_on"  name="published" value="<?php echo ($c->published_on); ?>">
                     <input type="hidden" id="id"  name="id" value="<?php echo ($b->id); ?>">

                  
                                 <tr>
                                       <td width="15%"><?php echo ( ($b->course_nm == 'Executive Master of Business Administration' || $b->course_nm == 'Master of Business Administration' ) ? 'Master of Business Administration' : $b->course_nm); ?>
                       <!-- <input type="hidden" id="hcid-<?php echo $i; ?>" name="hcid" value="<?php echo ($b->course_id); ?>">-->
                    </td>
                    <td width="35%"><?php echo ($b->course_nm == 'Executive Master of Business Administration' ? 'Master of Business Administration(Three Years)' : ($b->course_nm == 'Master of Business Administration' ? 'Master of Business Administration(Two Years)' : ($deptid == 'comm' ? $b->branch_nm . " /Group-" . $b->group . " / Section-" . $sec_name : $b->branch_nm) )); ?>
                        <!--<input type="hidden" id="hbid-<?php echo $i; ?>" name="hbid" value="<?php echo ($b->branch_id); ?>">-->
                    </td>
                    <td width="10%" align="center"><?php echo ($b->course_id == 'jrf' ? 'N/A' : $b->semester); ?>
                        <!--<input type="hidden" id="hsem-<?php echo $i; ?>" name="hsem" value="<?php echo ($b->semester); ?>">-->
                    </td>
                    <td width="15%"  nowrap="nowrap" align="center">
                        <a href="" data-toggle="modal" value="" id="rv1" onclick="my_fun_view_redeclare('<?php echo $b->id; ?>','<?php echo $c->published_on;  ?>','<?php echo $i; ?>')" data-target="#viewreport2" title=" view Declared Result List" class="glyphicon glyphicon-zoom-in"></a> 
                        &nbsp;&nbsp;&nbsp;&nbsp;  
                        <a href="" <?php echo '<center><img src="' . base_url() . 'assets/images/ism/excel.jpg" width="15" height="15" /></center>'; ?></a>
                        &nbsp;&nbsp;&nbsp;&nbsp;                            
        <?php echo form_submit($btn_pdf); ?>
                    </td>
                     <td width="25%" align="center">
                                <!--<a class="label label-success" data-toggle="modal" value="" id="rv" onclick="my_fun_edit('<?php echo $i; ?>')" data-target="#viewreport"  >Declared on <?php echo date('d-m-Y', strtotime($b->published_on)); ?></a>-->
                                <label class='label label-primary'>Re-Declared on <?php echo date('d-m-Y', strtotime($c->published_on)); ?> </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                </td> 
                                <?php echo form_close(); ?>        
                                 </tr>
                                 
                                 <?php  } } 
                    
                    $i++;
                  
                }
                ?>
                </tbody>

            </table>

        </div>
    </div>

<?php } ?>
<!-- Model For Date-->
<div class="modal fade" tableindex="-1"  role="dialog" aria-hidden="true" id="viewreport">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="text-align: center">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div id="reportresult">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Model For Date-->
<div class="modal modal-wide fade" tableindex="-1"  role="dialog" aria-hidden="true" id="viewreport2">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="text-align: center">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div id="viewdataone">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Model For view data-->

<div class="modal modal-wide fade" tableindex="-1" role="dialog" aria-hidden="true" id="viewreport1">  
    <div class="modal-dialog">
        <div class="modal-content">
            
    </div>
</div>

 

 

<style>
    .modal-wide .modal-content {
        width: 1000px;
        margin-left: -150px;
    }
</style>

<script>
    //function love(){ $('#rdagain').modal(true); }    
    function my_fun_edit(row)
    {
       //alert(row);
        var sy = $("#hsyear").val();
        var sess = $("#hsess").val();
        var et = $("#hetype").val();
        var did = $("#hdeptid").val();
        var sec = $("#hsec_name").val();
        var cid = $("#hcid-" + row).val();
        var bid = $("#hbid-" + row).val();
        var sem = $("#hsem-" + row).val()
//    alert(sy);alert(sess);alert(et);alert(did);alert(sec);alert(cid);alert(bid);alert(sem);
        $.ajax({
            url: '<?php echo site_url('result_declaration/result_declaration_drside/date_show') ?>',
            type: "POST",
            async: false,
            data: {"vsy": sy, "vsess": sess, "vet": et, "vdid": did, "vsec": sec, "vcid": cid, "vbid": bid, "vsem": sem},
            success: function (data)
            {
                $('#reportresult').html(data);
                $('#viewreport1').modal({
                    show: true
                     });
            }
        });
    }
    function pdeclare(row,row_id,decId)
    {               
        var sy = $("#hsyear").val();
        var sess = $("#hsess").val();
        var et = $("#hetype").val();
        var did = $("#hdeptid").val();
        var sec = $("#hsec_name").val();
        var cid = $("#hcid-" + row).val();
        var bid = $("#hbid-" + row).val();
        var sem = $("#hsem-" + row).val();        
        
           $.ajax({
            url: '<?php echo site_url('result_declaration/result_declaration_drside/stulist_with_date_show') ?>',
            type: "POST",
            async: false,
            data: {"vsy": sy, "vsess": sess, "vet": et, "vdid": did, "vsec": sec, "vcid": cid, "vbid": bid, "vsem": sem, row_id:row_id, decid:decId},
            success: function (data)
            {
               // alert(data);
                $('#viewreport1 .modal-content').html(data);
                
            }
        }); 
        
    }
    function my_fun_undo(id)
    {
//alert(id);
        var r = confirm("Do You Really Want to Undo the Declaration");
        if (r == true)
        {
            $.ajax({
                url: "<?php echo site_url('result_declaration/result_declaration_drside/undo_record') ?>",
                type: "POST",
                async: false,
                data: {"rid": id},
                success: function (data)
                {
                    //alert(data);
                    if (data)
                    {
                        alert("Undo Done Successfully");
                    }
                }

            });
        }
        else
        {

            return false;
        }

    }
    function my_fun_view(row,published_on)
    {
        //alert(id);
        var sy = $("#hsyear").val();
        var sess = $("#hsess").val();
        var et = $("#hetype").val();
        var did = $("#hdeptid").val();
        var sec = $("#hsec_name").val();
        var cid = $("#hcid-" + row).val();
        var bid = $("#hbid-" + row).val();
        var sem = $("#hsem-" + row).val();

        $.ajax({
            url: '<?php echo site_url('result_declaration/result_declaration_drside/show_data_for_view') ?>',
            type: "POST",
            data: {"vsy": sy, "vsess": sess, "vet": et, "vdid": did, "vsec": sec, "vcid": cid, "vbid": bid, "vsem": sem  ,published:published_on },
            success: function (data)
            {
                //alert(data);
                $('#viewdataone').html(data);
            }
        });
    }
    
    function my_fun_view_redeclare(id,publishedOn,row){
    
         var sy = $("#hsyear").val();
        var sess = $("#hsess").val();
        var et = $("#hetype").val();
        var did = $("#hdeptid").val();
        var sec = $("#hsec_name").val();
        var cid = $("#hcid-" + row).val();
        var bid = $("#hbid-" + row).val();
        var sem = $("#hsem-" + row).val();
     
         $.ajax({
            url: '<?php echo site_url('result_declaration/result_declaration_drside/show_data_for_view_redeclare') ?>',
            type: "POST",
            data: {"id": id, "published": publishedOn,"vsy": sy, "vsess": sess, "vet": et, "vdid": did, "vsec": sec, "vcid": cid, "vbid": bid, "vsem": sem},
            success: function (data)
            {
                //alert(data);
                $('#viewdataone').html(data);
            }
        });
    
    }



    function printDiv(div) {
        // Create and insert new print section
        var elem = document.getElementById(div);
        var domClone = elem.cloneNode(true);
        var $printSection = document.createElement("div");
        $printSection.id = "printSection";
        $printSection.appendChild(domClone);
        document.body.insertBefore($printSection, document.body.firstChild);

        window.print();

        // Clean up print section for future use
        var oldElem = document.getElementById("printSection");
        if (oldElem != null) {
            oldElem.parentNode.removeChild(oldElem);
        }
        //oldElem.remove() not supported by IE

        return true;
    }
    
     
   
$(document).ready(function () {   
    
     if($('#hdeptid').val()!='comm')$('#sec').hide(); 
     else { 
          $('#sec').show();
          $("#dept").append('<option value="comm">Common</option>');
          $('#dept').val('comm');        
          $.ajax({url: site_url("result_declaration/result_declaration_drside/get_section_common2/" +  $('#hsyear').val()), type: "json",
                success: function (result) {
                    $('#section_id').html(result);
                    $('#section_id > option[value=""]').remove();                    
                    $('#section_id').val( $('#hsec_name').val());
                }});
     }
    $.ajax({
        url: site_url("attendance/attendance_ajax/get_session_year_exam"),
        success: function (result) {
            $('.gS').html(result);
        }
    });
    $('#exm_type').on('change', function () {
        var exists = false;
        var exists1 = false;
        $('#dept option').each(function () {
            if (this.value == 'comm') {
                exists = true;
            }
            else if (this.value == 'all') {
                exists1 = true;
            }
        });
        if (this.value == "regular") {
            if (exists == true)
                $("#dept option[value='comm']").remove();
            else
                $("#dept").append('<option value="comm">Common</option>');
            if (exists1 == true)
                $("#dept option[value='all']").remove();
        }
        else if (this.value == "prep") {
            if (exists1 == true)
                $("#dept option[value='all']").remove();
            else
                $("#dept").append('<option value="all">All</option>');

            if (exists == true)
                $("#dept option[value='comm']").remove();
            $('#dept').val('all');
        }
        else {
            if (exists1 == true)
                $("#dept option[value='all']").remove();
            if (exists == true)
                $("#dept option[value='comm']").remove();
        }
    });
  
   $('#dept').on('change', function (){
        var session_year = $('#session_year_attendance').val();
        if (this.value == "comm") {
            $('#sec').show();
            $.ajax({url: site_url("result_declaration/result_declaration_drside/get_section_common2/" + session_year), type: "json",
                success: function (result) {
                    $('#section_id').html(result);
                    $('#section_id > option[value=""]').remove();
                    $('#section_id option').eq(0).before('<option value="all">ALL</option>');
                   $('#section_id').val('all');
                }});
        } else {
            $('#sec').hide();
        }
    });
    }); 


</script>


