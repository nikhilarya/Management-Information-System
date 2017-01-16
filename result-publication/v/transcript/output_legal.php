<style>
#result_data{
	font-size: 8px;
}
hr { margin: 0.1em auto; }
/*td { 
    padding: 2px;
}*/
.anuj {
	height: 100px;
	width: 400px;
	margin: 0; padding: 0;
	border-collapse: collapse;
}

.anuj td { 
	border: 1px solid ;
	border-spacing: 0;
	height: 10px;
	width: 100px;
	margin: 0; padding: 0;
}


</style>
<style type="text/css">
    @media screen {
        div.divFooter {
          // display: none;
         position: relative;
    bottom: 0;
    right: 0;
    width: 1000px;
    
        }
        div.divHeader {
            display: none;
        }

    }

    @media print {
        div.divFooter {
            position: relative;
            margin-top:500px;
            bottom: 0;
            
        }
    }
    @page { size:8.5in 11in; margin: 0; }
</style>




<?php $space=str_repeat('&nbsp;', 3);?>

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
    
    <table border = "0" align = "center" style = "width : 100%"> 
       
	<!-- First Row -->
            <tr>
            	<td align="center" style="font-size: 12px;">
            		<span style="font-size: 12px;font-weight:bold;">
            			Name: 
            		</span>
                    <b><?php echo strtoupper($stu_result['stu_name']); ?></b>
            	</td>
            	<td align="center" style="font-size: 12px;">
            		<span style="font-size: 12px;font-weight:bold;">
            				Admn No. 
            		</span>
                    <b><?php echo strtoupper($stu_result['admn_no']); ?></b>
            	</td>
         
            	<td align="center" style="font-size: 12px;">
            		<span style="font-size: 12px;font-weight:bold;">
            				Course: 
                        </span><b><?php echo $stu_result['cname']; ?></b>
            	</td>
                   	<td align="center" style="font-size: 12px;">
            		<span style="font-size: 12px;font-weight:bold;">
            				Branch: 
            		</span>
                    <b><?php echo $stu_result['bname']; ?></b>
            	</td>
            </tr>
        
    </table>
</div>
<div align = "left" ><input  id="printpagebutton" type="button" value="Print" onclick="printpage()"/></div>
<hr/>
<!-- cut from here starts -->
<div>
    <?php 
            for($key=0;$key<$stu_result['tot_sem'];$key++)
            {
               if(isset($stu_result[$key])){ 
                if(($key+1)%2 == 1){ 
		 echo '<table class="anuj"  border=1  style="border-spacing: 0; padding: 0; width : 49%; font-size: 8px; float: left">';
		 }
		 else 
		 { 
		echo '<table  class="anuj" border=1  style="border-spacing: 0; padding: 0; width : 49%; font-size: 8px; float: right">';
		 } 
		?>
		<thead>
                    <?php 
                 
                        //---------------------------------------------------
                         $psy=$stu_result[$key]['result'][0]['ysession'];
                         
                         
                         $psy1=intval($psy/100);
                            $psy2=$psy%100;
                            $sy="20".$psy1."-".$psy2;
                         //---------------------------------------------------

                         switch($sess=$stu_result[$key]['result'][0]['wsms'])
                        {


                            case "MS":
                                    $sess = "Monsoon " ;
                                        break;
                             case "WS":
                                    $sess = "WInter ";
                                        break;     
                             case "SS":
                                    $sess = "Summer ";
                                        break;
                        }  
                    
                     //---------------------------------------------------
                     
                     switch(trim($stu_result[$key]['result'][0]['examtype']))
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
                     
                     
                     
                     //---------------------------------------------------
                    ?>
			<tr>
				<th colspan="3"> <?php echo $space.($key+1)." Semester Examination";?></th>
                                <th><?php echo $space.$sy;?></th>
                                <th><?php echo $space.$sess;?></th>
                                <th><?php echo $space.$et1;?></th>
			</tr>
			<tr>							
				<th colspan="3" style="font-size: 8px;">&nbsp;&nbsp;&nbsp;Subject</th>
				<th style="font-size: 8px; width: 10%">&nbsp;&nbsp;&nbsp;Credits Hours</th>
                                <th style="font-size: 8px; width: 10%">&nbsp;&nbsp;&nbsp;Credits Points</th>
				<th style="font-size: 8px; width: 10%">&nbsp;&nbsp;&nbsp;Grade</th>
											
			</tr>
			</thead>
                        <tbody>
                            <?php
                            $flag=0;
                            $total_credit=0;
                            $total_points=0;
                            for($x=0;$x<$stu_result['nrows'];$x++) // inner loop
                            {
                                if(!empty($stu_result[$key]['result'][$x])){
                                $total_credit+=$stu_result[$key]['result'][$x]['crdhrs'];
				$total_points+=($stu_result[$key]['result'][$x]['crpts']);
                                
                                if($stu_result[$key]['result'][$x]['grade']=='F'){$flag=1;}
                                }
                	?>
                        <tr>
                            <?php if(!empty($stu_result[$key]['result'][$x])){
                                
                                ?>
                                <td colspan="3" style="font-size: 8px;"><?php echo $space.$stu_result[$key]['result'][$x]['subje_name'];?></td>
		<td align="center" style="font-size: 8px; width: 10%"><?php echo $stu_result[$key]['result'][$x]['crdhrs'];?></td>
                <td align="center" style="font-size: 8px; width: 10%"><?php echo $stu_result[$key]['result'][$x]['crpts'];?></td>
		<td align="center"style="font-size: 8px; width: 10%"><?php echo $stu_result[$key]['result'][$x]['grade'];?></td>
                            <?php } else {?>
                                <td colspan="3" style="font-size: 8px;"><?php echo " "; ?></td>
				<td style="font-size: 8px; width: 10%"><?php echo " "; ?></td>
                                <td style="font-size: 8px; width: 10%"><?php echo " "; ?></td>
			 	<td style="font-size: 8px; width: 10%"><?php echo " "; ?></td>
                            <?php } ?>
			 </tr> 
                            
                      <?php     
                            }?>
                         
                         <tr>
                            <td ><b><?php 
                            $pf=($flag==0)?"PASS":"FAIL";
                            echo $space."Result: ".$pf;?></td>
                            <td ><?php echo $space."<b>GPA:</b> ".$stu_result[$key]['result'][0]['gpa'];?></td>
                            <td ><?php echo $space."<b>OGPA:</b> ".$stu_result[$key]['result'][0]['ogpa'];?></td>
                            <td align="center"><?php echo $total_credit;?></td>
                            <td align="center"><?php echo $total_points;?></td>
                            <td></td>
                            </tr>
                        </tbody>
                        </table>
                    
     <?php           
            }
            
            
            }
            
    ?>
    
</div>
<!-- cut from here ends -->

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