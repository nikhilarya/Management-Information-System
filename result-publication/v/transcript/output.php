<style>
#result_data{
	font-size: 8px;
}
hr { margin: 0.1em auto; }
td { 
    padding: 1px;
}

</style>
<style type="text/css">
    @media screen {
        div.divFooter {
            display: none;
        }
        div.divHeader {
            display: none;
        }

    }

    @media print {
        div.divFooter {
            position: relative;
            margin-top:650px;
            bottom: 0;
        }
    }
    //@page { size: A4;   margin: 0;}
    
    @page { size:8.5in 11in; margin: 0; }
    
</style>
<div class = "divHeader">
	<div style="float:left;width:15%;" >
		<img src="<?php echo base_url() ?>assets/images/ism/ism.jpg" width="85 " height="70" />
	</div>
<div style="float:center;width:85%;">
	<div  style="font-size:15px; font-weight:bold; text-align:center;">INDIAN SCHOOL OF MINES</div>
	<div  style="font-size:15px; font-weight:bold; text-align:center;">DHANBAD - 826004</div>
	<div  style="font-size:12px; text-align:center;">CONSOLIDATED GRADE CARD</div>
</div>

<hr/>
	<table border =  0 align = "center" style = "width : 100%"> 
	<!-- First Row -->
            <tr>
            	<td align="center" style="font-size: 8px;">
            		<span style="font-size: 8px;font-weight:bold;">
            			Name: 
            		</span>
                    <b><?php echo $name_of_student; ?></b>
            	</td>
            	<td align="center" style="font-size: 8px;">
            		<span style="font-size: 8px;font-weight:bold;">
            				Admn No. 
            		</span>
                    <b><?php echo strtoupper($admn_no); ?></b>
            	</td>
         
            	<td align="center" style="font-size: 8px;">
            		<span style="font-size: 8px;font-weight:bold;">
            				Course: 
                        </span><b><?php echo $course_id; ?></b>
            	</td>
                   	<td align="center" style="font-size: 8px;">
            		<span style="font-size: 8px;font-weight:bold;">
            				Branch: 
            		</span>
                    <b><?php echo $branch_id; ?></b>
            	</td>
            </tr>
    </table>
</div>
<div align = "left" ><input  id="printpagebutton" type="button" value="Print" onclick="printpage()"/></div>
<hr/>

<div>
		<?php
		$total_gpa=0;
		$total_sem_credit=0;
		foreach($stu_result as $key=>$row)
		{
			if(empty($row)) continue;
			?>
			<div id = "result_data">
			<? if($key%2 == 1){ 
			echo '<table  border=1  style="border-spacing: 0; padding: 0; width : 49%; font-size: 8px; float: left">';
			 }
			 else 
			 { 
			echo '<table  border=1  style="border-spacing: 0; padding: 0; width : 49%; font-size: 8px; float: right">';
			 } 
			?>
			<thead>
			<tr>
				<th colspan="6">
					 <?php echo $key." Semester Examination";?>
				</th>
			</tr>
			<tr>							
				<th colspan="3" style="font-size: 8px;">Subject</th>
				<th style="font-size: 8px; width: 10%">Credits Hours</th>
                                <th style="font-size: 8px; width: 10%">Credits Points</th>
				<th style="font-size: 8px; width: 10%">Grade</th>
											
			</tr>
			</thead>
			<?php	
			$total_credit=0;
			$total_points=0;
                       $flag=0;
			foreach($row as $key1=>$row1)
			{
				//print_r($row);
                                print_r($row1);
                                die();
                            //echo $row1['credit'];
                           // echo ($row1['credit']*$row1['points']);
				$total_credit+=$row1['credit'];
				$total_points+=($row1['credit']*$row1['points']);
                                if($row1['grade']=='F'){$flag=1;}

				?><tr>
                                <td colspan="3" style="font-size: 8px;"><?echo $key1;?></td>
				<td style="font-size: 8px; width: 10%"><?echo $row1['credit'];?></td>
                                <td style="font-size: 8px; width: 10%"><?echo $row1['credit']*$row1['points'];?></td>
			 	<td style="font-size: 8px; width: 10%"><?echo $row1['grade'];?></td>

			 	</tr><?php
			}
				$total_gpa+=$total_points;
				$total_sem_credit+=$total_credit;
			?>
                        
                                <tr>
                                    <td ><b><?php echo "Result: ".($flag==0)?"PASS":"FAIL";?></b></td>
                                <td ><?php echo "<b>GPA: ". round($total_points/$total_credit,2)."</b>";?></td>
                                <td ><?echo "<b>OGPA: ".round($total_gpa/$total_sem_credit,2)."</b>";?></td>
                                <td><?php echo $total_credit;?></td>
                                <td><?php echo $total_points;?></td>
                                <td></td>
			 	</tr>
			 	</table>
			 </div>
			 	<?php
            }
	?> 
	<br/>
	<div class="divFooter" >

<b>Date</b>
</div>
</div>
<script type="text/javascript">
    function printpage() {
        //Get the print button and put it into a variable
        var printButton = document.getElementById("printpagebutton");
        //Set the print button visibility to 'hidden' 
        printButton.style.visibility = 'hidden';
        //Print the page content
        window.print()
        //Set the print button to 'visible' again 
        //[Delete this line if you want it to stay hidden after printing]
        printButton.style.visibility = 'visible';
    }
</script>