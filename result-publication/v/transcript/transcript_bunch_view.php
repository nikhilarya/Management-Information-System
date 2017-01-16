

<div class="row">
    <div class="col-md-4 col-md-offset-4 ">

        <?php echo form_open('transcript/transcript_bunch/check_generate'); ?>



        <div class="box box-solid box-primary">
            <?php echo form_close(); ?>
            <div class="box-header">
                <h3 class="box-title"></h3>
            </div>

            <div class="box-body">

                <div class="row">
                        <div class="col-md-12">
                        <div class="form-group">
                               <label for="syear">Session Year</label>
            <select name="selsyear" id="selsyear" class="form-control" >
                <!--      <option value="none" selected="selected">Session Year</option> -->
                <?php foreach ($session_year as $drop) {
                    ?>
                    <option value="<?php echo $drop->session_year; ?>" ><?php echo $drop->session_year; ?></option>

                <?php } ?>
            </select> 
                      
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="department">Department </label>
                            <select name="seldept" id="seldept" class="form-control" >
                                <option value="none" selected="selected">Select Department</option>

                                <?php foreach ($dept_list as $r): ?>
                                    <option value="<?php echo $r->id ?>"><?php echo $r->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="course">Course </label>

                            <select name="courselist" id="courselist" class="form-control" >
                                <option value="none" selected="selected">Select Course</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="branch">Branch </label>
                            <select name="branchlist" id="branchlist" class="form-control" >
                                <option value="none" selected="selected">Select Branch</option>

                            </select>
                        </div>
                    </div>
                    
                      <div class="col-md-12">
                        <div class="form-group">
                            <label for="sem">Semester </label>
                          <?php echo form_input(array('name'=>'sem','id'=>'sem','placeholder'=>'Semester','class'=>'form-control',)) ?>
                        </div>
                    </div> 

                </div>


            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="col-md-6">
                        <?php echo form_submit('pdf', 'PDF', 'class="btn btn-primary"'); ?>
                    </div>
                     <div class="col-md-6 pull-right" style="text-align:right">
                        <?php echo form_submit('excel', 'Excel', 'class="btn btn-primary"'); ?>
                    </div>
                </div>

            </div>


        </div>






    </div>
    <?php echo form_close( ); ?>
</div>


<style>
    .box-primary{
        border:1px groove #3c8dbc !important;
    }
</style>
<script>
    $(document).ready(function () {
        $('#seldept').change(function () {
            var id = $(this).val();

            $.ajax({
                url: "<?Php echo base_url(); ?>index.php/transcript/transcript_bunch/get_course_by_dept/",
                type: "POST",
                data: {"dept_id": id},
                success: function (data)
                {
                    var json = $.parseJSON(data);
                    $('#courselist').html('');
                    $('#courselist').append("<option value='none' selected='selected'>Select Branch</option>");
                    $.each(json.course_list, function (key, value) {

                        $('#courselist').append($("<option></option>")
                                .attr("value", value.id)
                                .text(value.name));
                    });
                }
            });
        });

        //--------------------------------Branch---------------------------------------------

        $('#courselist').change(function () {
            var cid = $(this).val();
            var did = $('#seldept').val();
            $.ajax({
                url: "<?Php echo base_url(); ?>index.php/transcript/transcript_bunch/get_branch_by_course_dept/",
                type: "POST",
                data: {"dept_id": did, "course_id": cid},
                success: function (data)
                {
                    var json = $.parseJSON(data);
                    $('#branchlist').html('');
                    $.each(json.br_list, function (key, value) {

                        $('#branchlist').append($("<option></option>")
                                .attr("value", value.id)
                                .text(value.name));
                    });
                }
            });
        });

        //---------------------------------------------------------------------------------------------
    });
</script>
