<?php
	class Stu_details extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}
		function get_details($admn_no)
		{
			$this->load->database();
			/*$q = "SELECT admn_no,enrollment_year,course_id,branch_id,semester
						FROM stu_academic WHERE admn_no = '$admn_no'"; */
                    /*   $q="SELECT a.admn_no,a.enrollment_year,a.course_id,a.branch_id,a.semester,concat(b.first_name,' ',b.middle_name,' ',b.last_name) as stu_name
FROM stu_academic a
INNER JOIN user_details b ON a.admn_no=b.id
WHERE a.admn_no='".$admn_no."'";*/
                        $q="SELECT a.admn_no,a.enrollment_year,b.dept_id,a.course_id,a.branch_id,a.semester,
concat(b.first_name,' ',b.middle_name,' ',b.last_name) as stu_name,c.duration,(c.duration*2)as tot_sem,c.name as cname,d.name as bname
FROM stu_academic a
INNER JOIN user_details b ON a.admn_no=b.id
inner join cs_courses c on c.id=a.course_id
inner join cs_branches d on d.id=a.branch_id
WHERE a.admn_no='".$admn_no."'";
                       
                       
                       
			$query = $this->db->query($q);
                      // echo $this->db->last_query(); die();
			return $query->result();
		}
		function get_branch($branch_id)
		{
			$this->load->database();
			$q = "SELECT name FROM branches WHERE id = '$branch_id'";
			$query = $this->db->query($q);
			return $query->result();
		}
		function get_course($course_id)
		{
			$this->load->database();
			$q = "SELECT name FROM courses WHERE id = '$course_id'";
			$query = $this->db->query($q);
			return $query->result();
		}
		function get_result($admn_no)
		{
			$this->load->database();
			/*$q = "SELECT DISTINCT stu_name,adm_no,crdhrs,subje_name,tabulation1.grade,sem,f.points
				  FROM tabulation1 
				  INNER JOIN dip_m_semcode on tabulation1.sem_code = dip_m_semcode.semcode
				  INNER JOIN grade_points as f on tabulation1.grade = f.grade
				  WHERE adm_no = '$admn_no' ORDER BY sem";*/
                        
                              $q = "SELECT DISTINCT stu_name,adm_no,crdhrs,subje_order,subje_name,theory,sessional,practiocal,totalmarks,tabulation1.grade,gpa,ogpa,sem,f.points,ysession,wsms,examtype,rpublished
        FROM tabulation1
        INNER JOIN dip_m_semcode ON tabulation1.sem_code = dip_m_semcode.semcode
        INNER JOIN grade_points AS f ON tabulation1.grade = f.grade
        WHERE adm_no = '".$admn_no."'
        ORDER BY sem,subje_order";
			$query = $this->db->query($q);
                        //echo $this->db->last_query();die();
			return $query->result();
		}
		function grade_sheet_details($admn_no) 
		{
			   
			$sql = "select C.*,f.points,e.dept_id,e.aggr_id,e.course_id,e.branch_id,e.semester,e.`group` from 
			(select B.*,d.sequence  as seq from
			(select A.*,c.name,c.subject_id as sid,c.credit_hours,c.`type` from 
			(select a.sessional,a.theory,a.practical,a.total,a.grade,a.stu_status,a.sub_type,b.subject_id,b.`session`,b.session_year,b.sub_map_id from marks_subject_description as a
			inner join marks_master as b on a.marks_master_id=b.id  where a.admn_no='".$admn_no."' and b.`status`='Y' ) A
			inner join subjects as c on A.subject_id=c.id ) B
			inner join course_structure as d on B.subject_id=d.id ) C
			inner join subject_mapping as e on C.sub_map_id = e.map_id 
			inner join grade_points as f on C.grade = f.grade
			 group by C.sid order by e.semester,C.seq+0 asc";



			        $query = $this->db->query($sql);
				
			        if ($this->db->affected_rows() >= 0) {
			            return $query->result();
			        } else {
			            return false;
			        }
		}
		function new_table_fail($adm_no)
		{
			$sql = "select final_semwise_marks_foil.semester, final_semwise_marks_foil.status, concat_ws('',s.subje_name,e.name) as name
					from final_semwise_marks_foil
					inner join final_semwise_marks_foil_desc 
					on final_semwise_marks_foil.id = final_semwise_marks_foil_desc.foil_id
					left join tabulation1 s on s.subje_code=final_semwise_marks_foil_desc.sub_code
					inner join subjects as e on final_semwise_marks_foil_desc.mis_sub_id = e.id
					
					where final_semwise_marks_foil.admn_no = '$adm_no'";



			        $query = $this->db->query($sql);
				//echo $this->db->last_query();die();
			        if ($this->db->affected_rows() >= 0) {
			            return $query->result();
			        } else {
			            return false;
			        }
		}
		 function get_others_sub_marks($adm_no,$sem)
	    {
	        /*$sql="Select A.*,s.name from (SELECT * FROM final_semwise_marks_foil_desc WHERE foil_id=(SELECT id FROM final_semwise_marks_foil
	              WHERE admn_no='".$adm_no."' AND semester like '%".$sem."%' and type='".$type."' ))A
	               inner join subjects s on s.subject_id=A.sub_code
	                 group by A.sub_code";*/
					 $sql ="select A.*,concat_ws('',s.subje_name,c.name) as name from (SELECT * FROM final_semwise_marks_foil_desc WHERE foil_id=(SELECT id FROM final_semwise_marks_foil
	              WHERE admn_no='".$adm_no."' AND semester like '%".$sem."%'))A
	               left join tabulation1 s on s.subje_code=A.sub_code
	               left join subjects as c on A.mis_sub_id=c.id
	                 group by A.sub_code";
	        $query = $this->db->query($sql);        
	         echo $this->db->last_query(); die();             
	        if ($this->db->affected_rows() > 0) {
	            return $query->result();
	        } else {
	            return FALSE;
	        }        
	    }  
            
            
            //--------------------@anuj------------------------------------------------------
            function max_exam_type_semBased($admn_no,$sem)
            {
                $sql="SELECT b.sem,MAX(a.examtype) AS max_exam,a.sem_code FROM tabulation1 AS a
                        inner join dip_m_semcode b on a.sem_code=b.semcode
                        WHERE a.adm_no=? AND b.sem=?";
               

                    $query = $this->db->query($sql,array($admn_no,$sem));
                    
                   // echo $this->db->last_query(); 
                    if($this->db->affected_rows() >=0)
                    { 
                      
                         return $query->result();
                    }
                    else
                    {
                        return false;
                    }
            }
            
            
            function get_result_from_tabulation1($admn_no,$semcode,$et)
            {
                /*$sql="select * from tabulation1 where adm_no=? and sem_code=? and examtype=? order by subje_order";*/
                
                $sql="select a.*,b.sem from tabulation1 a
inner join dip_m_semcode b on b.semcode=a.sem_code
where a.adm_no=? and a.sem_code=? and a.examtype=? group by subje_code order by a.subje_order";
               

                    $query = $this->db->query($sql,array($admn_no,$semcode,$et));
                    
                    //echo $this->db->last_query(); die();
                    if($this->db->affected_rows() >=0)
                    { 
                      
                         return $query->result();
                    }
                    else
                    {
                        return false;
                    }
            }
            
            function get_number_of_semester($admn_no)
            {
               $sql="select group_concat(semester) as sem from reg_regular_form where admn_no=? and hod_status='1' and acad_status='1' group by admn_no";
                    $query = $this->db->query($sql,array($admn_no));
                    
                    //echo $this->db->last_query(); die();
                    if($this->db->affected_rows() >=0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
                        return false;
                    }
            }
            
             function get_number_of_semester_from_other($admn_no)
            {
               $sql="(SELECT GROUP_CONCAT(semester) AS sem
FROM reg_other_form
WHERE admn_no=? AND hod_status='1' AND acad_status='1'
GROUP BY admn_no)
union
(SELECT GROUP_CONCAT(semester) AS sem
FROM reg_exam_rc_form
WHERE admn_no=? AND hod_status='1' AND acad_status='1'
GROUP BY admn_no)
";
                    $query = $this->db->query($sql,array($admn_no,$admn_no));
                    
                  //  echo $this->db->last_query(); die();
                    if($this->db->affected_rows() >=0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
                        return false;
                    }
            }
            

             function get_exam_type($admn_no,$sem)
            {
               $sql="select `type` from reg_exam_rc_form where admn_no='".$admn_no."'  and semester like '%".$sem."%' AND hod_status='1' AND acad_status='1'";
                    $query = $this->db->query($sql);
                    
                  //  echo $this->db->last_query(); die();
                    if($this->db->affected_rows() >=0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
                        return false;
                    }
            }
            
            function get_final_details($id)
            {
               $sql="select * from final_semwise_marks_foil where id=".$id;
                    $query = $this->db->query($sql);
                    
                  //  echo $this->db->last_query(); die();
                    if($this->db->affected_rows() >=0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
                        return false;
                    }
            }
            
            
            
            
            //-------------------------------------------------------------------------------------

	}
?>