<style>
    body.modal-open .datepicker {
        z-index: 1200 !important;
    }
</style>

<div class="modal-header" style="text-align: center">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Re-Declaration</h4>
            </div>
<center><i><label for="rdate"> Department: </label>
<?php
                            if ($dept_id != 'comm') {
                                $dnm = $this->sbasic_model->getDepatmentById($dept_id);
                                echo $dnm->name;                              
                            } else {
                                echo 'COMMON';    
                            }
                            ?>
                            /
                             <label for="rdate"> Course: </label>
                            <?php
                            $cnm = $this->sbasic_model->getCourseById($course_id);
                            echo $cnm->name;
                                                      
                            ?>
                             /
                               <label for="rdate"> Branch:</label>
                             <?php
                             $bnm = $this->sbasic_model->getBranchById1($branch_id);
                            echo $bnm->name;
                                                        
                            ?>
                               /
                                <label for="rdate"> Semester: </label>
                            <?php echo $sem; ?> </i></center>
<!---------------------Slider 1 --------------------->
<div id="slider1">
        <?php //echo form_open('result_declaration/result_declaration_drside/save_data', array("id" => "fyears")); 
            //print_r($stop_stu);
            echo form_open();
                ?>
            <div class="modal-body">  
                <?php  if ($dept_id != 'comm') { ?>
<input type='hidden' id='dptname' name='dptname' value="<?php echo  $dnm->name ?>">
                <?php }else{ ?>
<input type='hidden' id='dptname' name='dptname' value='COMMON'>
                <?php } ?>
<input type='hidden' id='crs_name' name='crs_name' value="<?php echo $cnm->name ?>">
<input type='hidden' id='brn_name' name='brn_name' value="<?php $bnm->name ?>">
<input type="hidden" id="hsyear" name="hsyear" value="<?php echo $syear; ?>">
<input type="hidden" id="hsess" name="hsess" value="<?php echo $sess; ?>">
<input type="hidden" id="hetype" name="hetype" value="<?php echo $exam_type; ?>">
<input type="hidden" id="hdeptid" name="hdeptid" value="<?php echo $dept_id; ?>">
<input type="hidden" id="hsec_name" name="hsec_name" value="<?php echo $sec_nm; ?>">
<input type="hidden" id="hcid" name="hcid" value="<?php echo $course_id; ?>">
<input type="hidden" id="hbid" name="hbid" value="<?php echo $branch_id; ?>">
<input type="hidden" id="hsem" name="hsem" value="<?php echo $sem; ?>">
<input type="hidden" name="rowid" id="rowid" value="<?php echo $decId; ?>">     

                            

<?php if(!empty($stu_list)) { ?>
        
                        <table class="table table-bordered" id="ex_mod1">
                            <thead>
                                <tr>
                                    <th align="center">Admission No.</th>
                                    <th align="center">Name</th>
                                    <th align="center">status</th>
                                  
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                               $j=0;
                                foreach ($stu_list as $b) {
                                    //var_dump(in_array(strtolower($b->admn_no), $stop_stu));
                                    if(in_array(strtolower($b->admn_no), (array)$stop_stu)){  ?>
                                        <tr>
                                        <td><?php echo $b->admn_no; ?></td>
                                        <td><?php echo $b->st_name; ?></td>
                                        <td align="center" ><input type="checkbox" checked="checked" disabled="disabled" name="abc" id="abc<?php echo $stop_stu_main[$j]['id']; ?>" value="" ></td>
                                      
                                        </td>
                                    </tr>
                                  <?php $j++; }else{ 
                                    ?>
                                    <tr>
                                        <td><?php echo $b->admn_no; ?></td>
                                        <td><?php echo $b->st_name; ?></td>
                                        <td align="center" ><input type="checkbox" name="abc" id="abc" value="<?php echo $b->admn_no; ?>" ></td>
                                       
                                    </tr>
                                                                            
                                   
                                    <?php
                                   
                                } $i++;}
                                ?>
                            </tbody>
                        </table>
               
<?php } ?>

  <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rdate">Date of Result Publication</label>

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                         <?php echo form_input(array('name' => 'b_date', 'id' => 'b_date', 'placeholder' => 'Published Date', 'class' => 'form-control', 'data-date-format' => 'dd M yyyy', 'value' => date("d M Y"))) ?>
                        </div>
                    </div>	

                </div>                
    <div class="row" style="float:center">
        <div class="col-md-12">
            <div class="form-group">
                <input type="button" name="save_all" id="save_all" value="Save" class="btn btn-primary" /> &nbsp;&nbsp;&nbsp;&nbsp;<a  data-toggle="modal" onclick="sturedeclare('<?php echo $decId; ?>')"  class="btn btn-success btn-sm">Redeclare</a>
            </div>
        </div>
    </div>
<?php echo form_close(); ?>

           </div> <!-- model Body -->
</div><!-- Slider1 -->
<div id="slider2" style="display:none;">
    <div class="modal-body">
        <div class="alert alert-danger" id="rerror" style="display:none;"> </div>
        <?php echo form_open(); ?>
        <table class="table table-bordered" id="ex_mod2">
                            <thead>
                                <tr>
                                    <th align="center">Admission No.</th>
                                    <th align="center">Name</th>
                                    <th align="center">status</th>
                                    <th align="center">Status</th>
                                    <th align="center">View</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                $j=0;
                               
                                foreach ($stu_list as $b) {
                                    //var_dump(in_array(strtolower($b->admn_no), $stop_stu));
                                    if(in_array(strtolower($b->admn_no), (array)$stop_stu)){  ?>
                                        <tr id="tr<?php echo $stop_stu_main[$j]['id']; ?>">
                                        <td><?php echo $b->admn_no; ?></td>
                                        <td><?php echo $b->st_name; ?></td>
                                        <td align="center" >
                                            <?php if($stop_stu_main[$j]['status'] == 'P'){ ?>
                                            
                                            <input type="checkbox"  name="abc" id="abc" value="<?php echo $stop_stu_main[$j]['id']; ?>" ></td>
                                            <?php }else{ ?>
                                            <input type="checkbox" checked="checked"  name="abc" id="abc" value="<?php echo $stop_stu_main[$j]['id']; ?>" ></td>
                                            <?php } ?>
                                        <td align="center" id="status<?php echo $stop_stu_main[$j]['id']; ?>" >
                                            <?php if($stop_stu_main[$j]['status'] == 'P'){ ?>
                                            <span class="label label-warning">Pending</span>
                                            <?php }else{ ?>
                                            <span class="label label-success">Modified</span>&nbsp;&nbsp; <i  onclick="undo('<?php echo $stop_stu_main[$j]['id']; ?>')" style="size:15px; cursor: pointer; color:#e56c69;" class="fa fa-fw fa-undo"></i>
                                           <?php  } ?>
                                        </td>
                                        <td align="center" id="view<?php echo $stop_stu_main[$j]['id']; ?>"><a onclick="view('<?php echo $b->admn_no; ?>','<?php echo $b->st_name;  ?>');" class="btn btn-sm btn-primary">View</a></td>
                                    </tr>
                                <?php  $j++;} $i++; } ?>
                            </tbody>
        </table>
        
        <div class="form-group">
            <label for="date">Date </label>
            <input type="text" class="form-control" id="datere"/>
        </div>
        <div class="form-group">
            <label for="date" >Reason </label>
            <textarea id="rreason" class="form-control"></textarea>
        </div>
        <input type="hidden" id="radmn_no"/>
        <input type="hidden" id="rid"/>
        
        <?php echo form_close(); ?>
        <a class="btn btn-success" onclick="saveRe();"> << Redeclare</a>&nbsp;&nbsp;&nbsp;&nbsp; <a class="btn btn-primary" onclick="sback();"> << Back</a>
        <br><br>
    </div>
    
</div><!-- Slider2 --->
     <div id="slider3" style="display:none;">
         <div class="modal-body">
             
         </div>
     </div>



    
    <script>
         var oTablea = $("#ex_mod2").dataTable({
                "bPaginate": true,
                "bLengthChange": true,
                "bFilter": true,
                "bSort": true,
                "bInfo": true,
                "bAutoWidth": true,
                "bStateSave": true
            });
            
                
        function view(admn_no,name){
            $.ajax({
                    //result_declaration/result_declaration_drside/show_details
                    url: '<?php echo site_url('result_declaration/result_declaration_drside/view_result') ?>',
                    type: "POST",
                        async:false,
                    data: {"admn_no":admn_no,"session":'<?php echo $sess; ?>',"session_year":'<?php echo $syear ?>',"course_id":'<?php echo $course_id ?>',"branch_id":'<?php echo $branch_id; ?>',"semester":'<?php echo $sem; ?>','name':name },
                    success: function(r){
                                
                                  $('#slider3 .modal-body').html(r);
                                  viewshow();
                       }
                });  
        }        
                
        function undo(id){
            $.ajax({
                    //result_declaration/result_declaration_drside/show_details
                    url: '<?php echo site_url('result_declaration/result_declaration_drside/undo') ?>',
                    type: "POST",
                        async:false,
                    data: {"id":id },
                    success: function(r){
                                   r=$.parseJSON(r);
                                if(Boolean(r.status)== true){
                                        $('#status'+id).html('<span class="label label-warning">Pending</span>');
                                }
                       }
                });  
        }
        function saveRe(){
            
            if($('#datere').val() == ""){
               $( "#rerror" ).html("Please Enter Redeclartion Date First !").show();
                      setTimeout(function() {
                     $( "#rerror" ).fadeOut();
                 }, 1000 );
                 return false;
            }else if($( "#rreason").val() == ""){
                $("#rerror").html("Please Enter Redeclartion Reason !").show();
                      setTimeout(function() {
                     $( "#rerror" ).fadeOut();
                 }, 1000 ); 
                 return false;
            }else{
               // alert('hello');
                var chk1 = '';
                var id = $("#rowid").val();
                $("input:checked", oTablea.fnGetNodes()).each(function () {
                    chk1 += $(this).val() + "-";
                });
                chk1 = chk1.slice(0, -1);
                
                $.ajax({
                    //result_declaration/result_declaration_drside/show_details
                    url: '<?php echo site_url('result_declaration/result_declaration_drside/save_re_declared') ?>',
                    type: "POST",
                        async:false,
                    data: {"rid": $('#rid').val(), "res_re_id": chk1, "date":$('#datere').val() , "reason":$( "#rreason").val()},
                    success: function(r){
                                   r=$.parseJSON(r);
                                if(Boolean(r.status)== true){
                                   $("input:checked", oTablea.fnGetNodes()).each(function () {
                                            ad = $(this).val();
                                            $('#tr'+ad).remove();
                                            $('#abc'+ad).removeAttr('disabled');
                                      });
                                }else{
                                    alert("Please Check ! The student "+r.admn_no+" result is not Modify Yet.");
                                }
                       }
                });  
                
            }
            
        }
          function viewshow(){
            $( "#slider2" ).hide();
            $( "#slider3" ).show().effect( 'slide', {}, 500 );
        }
         function viewback(){
            $( "#slider3" ).hide();
            $( "#slider2" ).show().effect( 'slide', {}, 500 );
        }
        
        function sback(){
            $( "#slider2" ).hide();
            $( "#slider1" ).show().effect( 'slide', {}, 500 );
        }
        
        function resave(id){
           $('#form'+id).remove();
           $('#st'+id+' a').remove();
            $('#st'+id).html("Date");
        }
        
        function sturedeclare(id){
            
            //$('#radmn_no').val(admn_no);
             $('#rid').val(id);
            
            $( "#slider1" ).hide();
             $( "#slider2" ).show().effect( 'slide', {}, 500 );
          //  $('#st'+id+' a').hide();
            // $('#form'+id).show();
            // $('#form'+id+' a').show();
        }
      
                
        $(document).ready(function () {
            $('#b_date, #datere').datepicker({
                endDate: "+0d",
                autoclose: true
            }); 
            
           
            
        var oTable = $("#ex_mod1").dataTable({
                "bPaginate": true,
                "bLengthChange": true,
                "bFilter": true,
                "bSort": true,
                "bInfo": true,
                "bAutoWidth": true,
                "bStateSave": true
            });
            $('#save_all').on('click', function () {
                var bool="";
         var sy = $("#hsyear").val();
        var sess = $("#hsess").val();
        var et = $("#hetype").val();
        var did = $("#hdeptid").val();
        var sec = $("#hsec_name").val();
        var cid = $("#hcid").val();
        var bid = $("#hbid").val();
        var sem = $("#hsem").val();                       
                var chk1 = '';
                var id = $("#rowid").val();
                $("input:checked", oTable.fnGetNodes()).each(function () {
                    chk1 += $(this).val() + "-";
                });
                chk1 = chk1.slice(0, -1);
                $.ajax({
                    //result_declaration/result_declaration_drside/show_details
                    url: '<?php echo site_url('result_declaration/result_declaration_drside/save_admn_no_re_declared') ?>',
                    type: "POST",
                        async:false,
                    data: {"rid": id, "admno": chk1, "vsy": sy, "vsess": sess, "vet": et, "vdid": did, "vsec": sec, "vcid": cid, "vbid": bid, "vsem": sem, "b_date":$("#b_date").val()},
                    success: function(r){ 
                        if (Boolean(r)){ 
                           // alert(r);
                              bool=1;
                         }else{
                           //  alert(r);
                              bool=0;
                         }                             
                       $('#viewreport1').modal('toggle');               
                       }
                });                  
                console.log(Boolean(bool));                                                                                               
                    $.ajax({
                    url: '<?php echo site_url('result_declaration/result_declaration_drside/show_details') ?>',
                    type: "POST",
                    async:false,
                    data: { "session_year": sy, "session": sess, "exm_type": et, "dept": did, "section_name": sec},               
                });                 
                              
                     if (Boolean(bool)){                        
                           $("#msg").removeClass().addClass("alert alert-success");                            
                           $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-check'></i><strong>Re-Declaration done successfully  </strong>for Session_yr:" +sy+ ",Session:"+ sess+",Exam:"+et+",Dept:" +$("#dptname").val()+" "+ ((sec)? ",section:"+sec+"" : "") +",course:"+ $("crs_name").val() +",branch:"+ $("#brn_name").val()+ ",semseter:"+sem+"" );                                                      
                          } 
                          else {                                
                                $("#msg").removeClass().addClass("alert alert-danger");                               
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-exclamation'></i><strong>Re-Declaration Failed.</strong>for Session_yr:" +sy+ ",Session:"+ sess+",Exam:"+et+",Dept:" +$("#dptname").val() +" "+ ((sec)? ",section:"+sec+"" : "")+",course:"+ $("crs_name").val() +",branch:"+ $("#brn_name").val()+ ",semseter:"+sem+"");
                            }
                            
            });     
            
        }); 
    </script>










