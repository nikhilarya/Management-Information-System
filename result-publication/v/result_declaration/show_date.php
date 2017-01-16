




<div class="row">
    <div class="col-md-12 col-md-offset-4 ">
       
        <?php echo form_open('result_declaration/result_declaration_drside/save_data', array("id" => "fyears")); ?>

<input type="hidden" id="hsyear" name="hsyear" value="<?php echo $syear;?>">
<input type="hidden" id="hsess" name="hsess" value="<?php echo $sess;?>">
<input type="hidden" id="hetype" name="hetype" value="<?php echo $exam_type;?>">
<input type="hidden" id="hdeptid" name="hdeptid" value="<?php echo $dept_id;?>">
<input type="hidden" id="hsec_name" name="hsec_name" value="<?php echo $sec_nm;?>">
<input type="hidden" id="hcid" name="hcid" value="<?php echo $course_id; ?>">
<input type="hidden" id="hbid" name="hbid" value="<?php echo $branch_id; ?>">
<input type="hidden" id="hsem" name="hsem" value="<?php echo $sem; ?>">


        <div class="box box-solid box-primary">
            
            <div class="box-header">
                <h3 class="box-title">Date</h3>
            </div>
            
                <div class="box-body">

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="rdate">Department</label>
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                                <?php 
								if($dept_id!='comm'){
                                $dnm=$this->sbasic_model->getDepatmentById($dept_id);
                                echo $dnm->name;
								}else{
								echo 'COMMON';	
									
								}
								?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="rdate">Course</label>
                            </div>
                        </div>
                        
                         <div class="col-md-6">
                            <div class="form-group">
                                <?php 
                                $cnm=$this->sbasic_model->getCourseById($course_id);
                                echo $cnm->name;?>
                            </div>
                        </div>
                    </div>
                        <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="rdate">Branch</label>
                            </div>
                        </div>
                        
                          <div class="col-md-6">
                            <div class="form-group">
                                
                                <?php 
                                  $bnm=$this->sbasic_model->getBranchById1($branch_id);
                                echo $bnm->name;?>
                            </div>
                        </div>
                        </div>
                    <div class="row">
                         <div class="col-md-6">
                            <div class="form-group">
                                <label for="rdate">Semester</label>
                            </div>
                        </div>
                        
                           <div class="col-md-6">
                            <div class="form-group">
                                <?php echo $sem;?>
                            </div>
                        </div>
                    </div>  
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="rdate">Date of Result Publication</label>

                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">

                                <?php echo form_input(array('name' => 'b_date', 'id' => 'b_date', 'placeholder' => 'Budget Date', 'class' => 'form-control', 'data-date-format' => 'dd M yyyy', 'value' => date("d M Y"))) ?>
                            </div>
                        </div>	

                    </div>
                    </div>
                </div>
            <div class="box-footer">
                <div class="row">
                    <div class="col-md-8">
                        <?php echo form_submit('submit', 'Submit', 'class="btn btn-primary"'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php echo form_close(); ?>
<style>
    body.modal-open .datepicker {
        z-index: 1200 !important;
    }
</style>

<script>

    $(document).ready(function () {

        $('#b_date').datepicker({
            endDate: "+0d",
            autoclose: true
        });
    
    });
</script>










