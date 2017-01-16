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
	
	margin: 0; padding: 0;
	border-collapse: collapse;
}

.anuj td { 
	border: 1px solid ;
	border-spacing: 0;
	height: 10px;
	
	margin: 0; padding: 0;
}

.heading{
    font-size: 10px;
    font-weight: bold;
    text-align: center;
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

<?php
$space=str_repeat('&nbsp;', 3);
$i=0;
foreach ($stu_result['result'] as $rec){ ?>
<div class="bg" style="page-break-after: always;">
    <div style="clear:both; position:relative;">


    <table>
        <tr>
           <div style="float:left;width:15%;" >
		<img src="<?php echo base_url() ?>assets/images/ism/ism.jpg" width="85 " height="70" />
	</div>
<div style="float:center;width:85%;">
	<div  style="font-size:15px; font-weight:bold; text-align:center;">INDIAN SCHOOL OF MINES</div>
	<div  style="font-size:15px; font-weight:bold; text-align:center;">DHANBAD - 826004</div>
	<div  style="font-size:12px; text-align:center;">CONSOLIDATED GRADE CARD</div>
</div>
        </tr>
    </table>  
  <br>
<hr/>

	<table border =  0 align = "center" style = "width : 100%"> 
	<!-- First Row -->
            <tr>
            	<td align="center" style="font-size: 8px;">
            		<span style="font-size: 8px;font-weight:bold;">
            			Name: 
            		</span>
            		<?php echo $rec['stu_name']; ?>
            	</td>
            	<td align="center" style="font-size: 8px;">
            		<span style="font-size: 8px;font-weight:bold;">
            				Admn No. 
            		</span>
            		<?php echo ucwords($rec['admn_no']); ?>
            	</td>
           
                <td align="center" style="font-size: 8px;">
            		<span style="font-size: 8px;font-weight:bold;">
            				Course: 
            		</span><?php echo $rec['cname']; ?>
            	</td>
            	<td align="center" style="font-size: 8px;">
            		<span style="font-size: 8px;font-weight:bold;">
            				Branch: 
            		</span>
            		<?php echo $rec['bname']; ?>
            	</td>
            
            </tr>
    </table>

<!-- cut from here starts -->

<div>
    <?php 
            for($key=0;$key<$rec['tot_sem'];$key++)
            {
               if(isset($rec[$key])){ 
                if(($key+1)%2 == 1){ 
                  
		 echo '<table style="width:800px;"><tr><td style="width:200px;" ><table class="anuj" border=1  >';
		 }
		 else 
		 { 
		echo '<td style="width:100px;"></td><td style="width:200px;"><table class="anuj" border=1 >';
		 } 
		?>
		<thead>
                    <?php 
                 
                        //---------------------------------------------------
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
                                    $sess = "WInter ";
                                        break;     
                             case "SS":
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
                     
                     
                     
                     //---------------------------------------------------
                    ?>
			<tr>
				<td colspan="3" class="heading"> <?php echo $space.($key+1)." Semester Examination";?></td>
                                <td class="heading"><?php echo $space.$sy;?></td>
                                <td class="heading"><?php echo $space.$sess;?></td>
                                <td class="heading"><?php echo $space.$et1;?></td>
			</tr>
			<tr>							
				<td colspan="3" style="font-size: 8px;">&nbsp;&nbsp;&nbsp;Subject</th>
				<td style="font-size: 8px; width: 10%">&nbsp;&nbsp;&nbsp;Credits Hours</td>
                                <td style="font-size: 8px; width: 10%">&nbsp;&nbsp;&nbsp;Credits Points</td>
				<td style="font-size: 8px; width: 10%">&nbsp;&nbsp;&nbsp;Grade</td>
											
			</tr>
			</thead>
                        <tbody>
                            <?php
                            $flag=0;
                            $total_credit=0;
                            $total_points=0;
                            for($x=0;$x<$rec['nrows'];$x++) // inner loop
                            {
                                if(!empty($rec[$key]['result'][$x])){
                                $total_credit+=$rec[$key]['result'][$x]['crdhrs'];
				$total_points+=($rec[$key]['result'][$x]['crpts']);
                                
                                if($rec[$key]['result'][$x]['grade']=='F'){$flag=1;}
                                }
                	?>
                        <tr>
                            <?php if(!empty($rec[$key]['result'][$x])){
                                
                                ?>
                                <td colspan="3" style="font-size: 8px;"><?php echo $space.$rec[$key]['result'][$x]['subje_name'];?></td>
		<td align="center" style="font-size: 8px; width: 10%"><?php echo $rec[$key]['result'][$x]['crdhrs'];?></td>
                <td align="center" style="font-size: 8px; width: 10%"><?php echo $rec[$key]['result'][$x]['crpts'];?></td>
		<td align="center"style="font-size: 8px; width: 10%"><?php echo $rec[$key]['result'][$x]['grade'];?></td>
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
                            <td class="heading" ><b><?php 
                            $pf=($flag==0)?"PASS":"FAIL";
                            echo $space."Result: ".$pf;?></td>
                            <td class='heading'><?php echo $space."<span >GPA:</span> ".$rec[$key]['result'][0]['gpa'];?></td>
                            <td class='heading'><?php echo $space."<span >OGPA:</span> ".$rec[$key]['result'][0]['ogpa'];?></td>
                            <td class='heading' align="center"><?php echo $total_credit;?></td>
                            <td class='heading' align="center"><?php echo $total_points;?></td>
                            <td></td>
                            </tr>
                        </tbody>
                        </table>
               <?php
                if(($key+1)%2 == 1){ 
                  
		 echo '</td>';
		 }
		 else 
		 { 
		echo '</td></tr></table>';
		 } 
               
               
            }
           
            
            
            }
             if($rec['tot_sem'] % 2 == 1){
                echo '</tr></table>';
            }
            
    ?>
    
</div>
<!-- cut from here ends -->
</div>

</div>
<?php }  ?>


