<?php

class Result_declaration_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_depart_details($sy, $sess, $did, $etype, $dec_type='F' ,$sec = null) {
        
        
        if ($etype == 'regular' || $etype == 'jrf') {
            if ($etype == 'regular') {
                $str_crs_replace = " and course_id!='honour' and course_id!='jrf' ";
                $semester_replace1 = " , semester ";
                $semester_replace2 = " and A.semester=B.semester ";
                $semester_replace3 = ",A.semester ";
            } else if ($etype == 'jrf') {
                $str_crs_replace = " and course_id='jrf' ";
                $semester_replace1 = "";
                $semester_replace2 = "";
                $semester_replace3 = "";
            }

            if (in_array("hod", $this->session->userdata('auth')) or in_array("ft", $this->session->userdata('auth'))) {
                $left = 'inner ';
                $replace = " status=1   and  type= '".$dec_type."'  and ";
                if ($did == 'comm') {
                    $replace2_1 = ',section';
                    $replace2_2 = ',A.section';
                    $replace2_3 = ' ,section ';
                    $replace2_4 = ' and A.section=B.section ';
                    if (( $sec != NULL && $sec == 'ALL') or ( $sec == NULL ))
                        $where_add = "";
                    if ($sec != NULL && $sec != 'ALL')
                        $where_add = "  and  section = '" . $sec . "' ";
                }else {
                    $replace2_1 = '';
                    $replace2_2 = '';
                    $replace2_3 = '';
                    $replace2_4 = '';
                    $where_add = '';
                }
            } else if (in_array("exam_dr", $this->session->userdata('auth'))) {
                $left = 'inner ';                                 
                
                
                if ($did == 'comm') {
                    $replace2_1 = ',section';
                    $replace2_2 = ',A.section';
                    $replace2_3 = ' ,section ';
                    $replace2_4 = ' and A.section=B.section ';
                    if (( $sec != NULL && $sec == 'ALL') or ( $sec == NULL ))
                        $where_add = "";
                    if ($sec != NULL && $sec != 'ALL')
                        $where_add = "  and  section = '" . $sec . "' ";
                }else {
                    $replace2_1 = '';
                    $replace2_2 = '';
                    $replace2_3 = '';
                    $replace2_4 = '';
                    $where_add = '';
                }
            }
               if($dec_type=='W'){                            
			   $replace = " type= 'F'  and ";
             $myquery = " select A.*, null as status, null as id, null as published_on  from  
              (select a.*,b.name as course_nm,c.name as branch_nm from subject_mapping a 
               inner join  cs_courses b on a.course_id=b.id
               inner join  cs_branches c on a.branch_id=c.id
               where session_year='" . $sy . "' and session='" . $sess . "' and dept_id='" . $did . "'  " . $where_add . "  " . $str_crs_replace . "  group by  dept_id,course_id,branch_id " . $semester_replace1 . " " . $replace2_1 . "  )A   
               where not exists (select B.id  from result_declaration_log B 
               where " . $replace . "  B.exam_type='" . $etype . "' and A.session_year=B.s_year and A.session=B.session and A.dept_id=B.dept_id  and A.course_id=B.course_id and A.branch_id=B.branch_id 
               " . $semester_replace2 . "  " . $replace2_4 . "   
                ) order by A.course_nm,A.branch_nm " . $semester_replace3 . " " . $replace2_2 . "";
            }
            else 
            {
				$replace = " type= '".$dec_type."'  and ";
            $myquery = "select A.*, B.status,B.id,B.published_on from  
(select a.*,b.name as course_nm,c.name as branch_nm from subject_mapping a 
inner join  cs_courses b on a.course_id=b.id
inner join  cs_branches c on a.branch_id=c.id
where session_year='" . $sy . "' and session='" . $sess . "'
and dept_id='" . $did . "'  " . $where_add . "  " . $str_crs_replace . "  group by  dept_id,course_id,branch_id " . $semester_replace1 . " " . $replace2_1 . "    )A
" . $left . "  join 
(select  id,published_on,s_year,dept_id,course_id,branch_id,semester,session ,status " . $replace2_3 . " from result_declaration_log  where   " . $replace . "  exam_type='" . $etype . "')B

 on A.session_year=B.s_year and A.session=B.session and A.dept_id=B.dept_id  and A.course_id=B.course_id and A.branch_id=B.branch_id " . $semester_replace2 . "  " . $replace2_4 . "   order by A.course_nm,A.branch_nm " . $semester_replace3 . " " . $replace2_2 . "
";
			} 
			}  else if ($etype == 'other' || $etype == 'spl') {
            if ($etype == 'other')
                $type = 'O';
            else if ($etype == 'spl')
                $type = 'S';
            
            if($dec_type=='W'){            
                /*if (in_array("hod", $this->session->userdata('auth')) or in_array("ft", $this->session->userdata('auth'))) {                
                $replace = " B.status=1   and  B.type= '".$dec_type."'  and ";
            } else*/ if (in_array("exam_dr", $this->session->userdata('auth'))) {                
                //$replace = " B.type= '".$dec_type."'  and ";
                $replace = " B.type= 'F'  and ";
            }
               $myquery = " select A.course as course_id,A.branch as branch_id,A.semester,A.dept,A.session_yr,A.session, A.course_nm,A.branch_nm,null as status, null as id, null as published_on   from  (select x.*,b.name as course_nm,c.name as branch_nm from final_semwise_marks_foil x  
               inner join  cs_courses b on x.course=b.id  
               inner join  cs_branches c on x.branch=c.id  and  x.session_yr='" . $sy . "' AND x.session='" . $sess . "' AND x.dept='" . $did . "'  AND x.`type`='" . $type . "'   
               GROUP BY x.session_yr,x.session,x.dept,x.course,x.branch,x.semester)A   
               where not exists (select B.id  from result_declaration_log B 
               where " . $replace . "  B.exam_type='" . $etype . "' and A.session_yr=B.s_year and A.session=B.session and A.dept=B.dept_id  and A.course=B.course_id and A.branch=B.branch_id  and A.semester=B.semester  
              ) order by A.course_nm,A.branch_nm,A.semester";               
            }
          else{    
            if (in_array("hod", $this->session->userdata('auth')) or in_array("ft", $this->session->userdata('auth'))) {
                $left = 'inner ';
                $replace = " status=1   and  type= '".$dec_type."'  and ";
            } else if (in_array("exam_dr", $this->session->userdata('auth'))) {
                $left = 'inner ';
                $replace = " type= '".$dec_type."'  and ";
            }
            
            $myquery = "  select A.course as course_id,A.branch as branch_id,A.semester,A.dept,A.session_yr,A.session, A.course_nm,A.branch_nm,B.status,B.id ,B.published_on  from  (select x.*,b.name as course_nm,c.name as branch_nm from final_semwise_marks_foil x  
                 inner join  cs_courses b on x.course=b.id  inner join  cs_branches c on x.branch=c.id  and  x.session_yr='" . $sy . "' AND x.session='" . $sess . "' AND x.dept='" . $did . "'  AND x.`type`='" . $type . "'    GROUP BY x.session_yr,x.session,x.dept,x.course,x.branch,x.semester)A   
                 " . $left . "  join 
                (select id,published_on,s_year,dept_id,course_id,branch_id,semester,session ,status  from result_declaration_log  where   " . $replace . "  exam_type='" . $etype . "')B
                 on A.session_yr=B.s_year and A.session=B.session and A.dept=B.dept_id  and A.course=B.course_id and A.branch=B.branch_id  and A.semester=B.semester order by A.course_nm,A.branch_nm,A.semester ";
           }
        } 
        $query = $this->db->query($myquery);
      //  echo $this->db->last_query(); die();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    function insert_result_declaration($data) {
       //print_r($data);
        if ($this->db->insert('result_declaration_log', $data))
            return TRUE;
        else
            return FALSE;
    }

    function get_published_status($sy, $sess, $did, $cid, $bid, $sem, $etype, $grp, $section) {
        /* $myquery = "select * from result_declaration_log where s_year='".$sy."'
          and session='".$sem."' and exam_type='".$et."' and dept_id='".$did."' "; */
        //echo 'did'.$did;
        if ($did == "comm") {
            $where_add = " and  section= '" . $section . "' ";
        } else {
            $where_add = "";
        }
        if ($cid != 'jrf') {
            $semester_replace = " and semester='" . $sem . "' ";
        } else {
            $semester_replace = "";
        }

        $myquery = "select * from result_declaration_log where s_year='" . $sy . "'
and session='" . $sess . "' and dept_id='" . $did . "' and course_id='" . $cid . "'
and branch_id='" . $bid . "' " . $semester_replace . "  and exam_type='" . $etype . "'  " . $where_add . "  order by id desc limit 1";


        $query = $this->db->query($myquery);
//echo $this->db->last_query(); die();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    function update_decl_status($id) {
        $myquery = "update result_declaration_log set status=0 ,type='W' where id=" . $id;

        $query = $this->db->query($myquery);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
    /// Get stop Stu
    
    function get_stop_stu($id){
        //echo $id; die();
        $q="select * from result_declaration_log_partial_details where res_dec_id=? and (status='M' OR status='P')";
        
            $q=$this->db->query($q,array($id));
 //echo $this->db->last_query();
        if($q->num_rows() > 0){
            return $q->result_array();
        }
        return array();
    }
    
    function get_stop_stu_by_id($id){
        $q=$this->db->get_where('result_declaration_log_partial_details',array('id'=>$id));
        if($q->num_rows() > 0){
            return $q->row();
        }
    }

    function get_result_for_view($sy, $sess, $etype, $deptid, $sem, $cid, $bid, $sec_name,$admn_no=null) {
        
        $admn_no=preg_replace('/\s+/', '',$admn_no);
           if($admn_no==null){
                      $where_add2="";                    
                     }
                  else{
                    if(substr_count($admn_no, ',')>0){                               
                     $admn_no="'". implode("','", explode(',', $admn_no)) ."'";
                     $where_add2=" and a.admn_no in(".$admn_no.") ";                                                              
                     }else{                  
                      $where_add2=" and a.admn_no='".$admn_no."' ";                      
                    }                    
                   }
        /*if ($etype == 'R') {
              
            if ($cid == 'minor') {
                $myquery = "			 
                               SELECT CONCAT_WS(' ',u.first_name, u.middle_name, u.last_name) AS st_name, dpt.name as dept_name,  G.*
                               FROM (
                                    SELECT x.admn_no,
		                      SUM(IF((x.course_id= 'minor'), x.totcrdpts, NULL)) AS core_tot, 
   	 SUM(IF((x.course_id= 'minor'), x.credit_hours, NULL)) AS core_crdthr,
  	    IF 
			 (COUNT(DISTINCT CASE WHEN (x.course_id= 'minor' AND (x.grade is null || x.grade = 'F')) THEN 1 END),'INC', 
			 (SUM(IF((x.course_id= 'minor'), x.totcrdpts, NULL)) / SUM(IF((x.course_id='minor'), x.credit_hours, NULL)))) AS core_GPA, 
         IF(COUNT(DISTINCT CASE WHEN (x.course_id ='minor' AND (x.grade is null || x.grade = 'F')) THEN 1 END),'FAIL', 'PASS') AS core_status,
		    SUM(IF ((x.course_id= 'minor' AND (x.grade is null || x.grade = 'F')), 1, 0)) AS count_core_failed_sub, 
		 	GROUP_CONCAT((IF((x.course_id= 'minor' AND (x.grade is null || x.grade = 'F')),x.sub, NULL)) SEPARATOR ',') AS core_fail_sub_list,GROUP_CONCAT( concat( x.sub,'(',x.grade,')') SEPARATOR ',') as sublist
		 
		 
		 
FROM (
SELECT grade_points.points, (grp.credit_hours*grade_points.points) AS totcrdpts, grp.*
FROM (
SELECT A.admn_no,A.grade,c.name,c.subject_id AS sub,c.credit_hours, d.aggr_id,d.semester,d.sequence, e.course_id
FROM (
SELECT a.admn_no,a.grade,b.subject_id, b.sub_map_id
FROM marks_subject_description AS a
INNER JOIN marks_master AS b ON a.marks_master_id=b.id
WHERE b.session_year='" . $sy . "' and  b.session='" . $sess . "' and b.`type`='" . $etype . "' ". $where_add2." ) A
INNER JOIN subjects AS c ON A.subject_id=c.id
INNER JOIN course_structure AS d ON A.subject_id=d.id
INNER JOIN subject_mapping AS e ON A.sub_map_id = e.map_id
WHERE e.dept_id='" . $deptid . "' and e.semester='" . $sem . "'  AND ( e.course_id='" . $cid . "' ) and e.branch_id='" . $bid . "'
GROUP BY A.admn_no,A.subject_id)grp
LEFT JOIN grade_points ON grade_points.grade=grp.grade
ORDER BY grp.admn_no, grp.semester,grp.sequence ASC) x
GROUP BY x.admn_no
)G
LEFT JOIN user_details u ON u.id=G.admn_no
LEFT JOIN departments dpt ON dpt.id=u.dept_id 
INNER join ( select hf2.admn_no,hm_minor_details.branch_id from hm_form hf2  
                    inner join hm_minor_details on hm_minor_details.form_id=hf2.form_id 
                          and hm_minor_details.offered='1' and hf2.minor='1' and hf2.minor_hod_status='Y'
          )K 
on G.admn_no=K.admn_no
ORDER BY G.admn_no
";
            } else {


                if ($deptid == 'comm') {
                    $where_add = " and  e.section='" . $sec_name . "'";
                } else {
                    $where_add = "";
                }


                if ($sem >= 5 and $cid == 'b.tech')
                    $replace = "(e.course_id='" . $cid . "' or  e.course_id='honour')";
                else
                    $replace = "e.course_id='" . $cid . "' ";
                
                
                   

                $myquery = "   
  select concat_ws(' ',u.first_name, u.middle_name, u.last_name) as st_name ,G.* from
( select  x.admn_no, 	 
  sum( IF((x.course_id!= 'honour' and x.course_id!= 'minor'  ), x.totcrdpts,null) )  as core_tot,	    
  sum( IF((x.course_id!= 'honour' and x.course_id!= 'minor'  ), x.credit_hours,null) )  as core_crdthr,	    

 IF(COUNT(DISTINCT CASE WHEN (x.course_id!= 'honour' and x.course_id!= 'minor'  and  (x.grade is null || x.grade = 'F')) THEN 1  END),'INC',
	(  sum( IF((x.course_id!= 'honour' and x.course_id!= 'minor'  ), x.totcrdpts,null) ) / sum( IF((x.course_id!= 'honour' and x.course_id!= 'minor'  ), x.credit_hours,null))  )
	) as core_GPA,  
	  	    	    		    	
  IF(COUNT(DISTINCT CASE WHEN (x.course_id!= 'honour' and x.course_id!= 'minor'  and (x.grade is null ||  x.grade = 'F')) THEN 1  END),'FAIL', 'PASS')  as  core_status,	
  SUM( IF ( (x.course_id!= 'honour' and x.course_id!= 'minor'  and  (x.grade is null || x.grade = 'F')), 1, 0))as  count_core_failed_sub,		  
  GROUP_CONCAT(( IF( (x.course_id!= 'honour' and x.course_id!= 'minor'  and  (x.grade is null || x.grade = 'F')),x.sub,null) ) SEPARATOR ',') as  core_fail_sub_list,
  
   sum( IF((x.course_id= 'honour'), x.totcrdpts,null) )  as H_only,
   
   ( sum( IF((x.course_id!= 'honour' and x.course_id!= 'minor'  ), x.totcrdpts,null) ) + 
     sum( IF((x.course_id= 'honour'), x.totcrdpts,null) ) 
	)  as H_tot	,
	
	 sum( IF((x.course_id= 'honour'), x.credit_hours,null) )  as H_crdthr,
	(sum( IF((x.course_id!= 'honour' and x.course_id!= 'minor'  ), x.credit_hours,null)) +   sum( IF((x.course_id= 'honour'), x.credit_hours,null) )  ) as crdthr_with_H,
	
	    IF(COUNT(DISTINCT CASE WHEN ((x.course_id= 'honour' and x.grade = 'F')or (x.course_id!= 'honour' and x.course_id!= 'minor'  and (x.grade is null || x.grade = 'F') )) THEN 1 END),'INC',
  ( ( sum( IF((x.course_id!= 'honour' and x.course_id!= 'minor' ), x.totcrdpts,null) ) + sum( IF((x.course_id= 'honour'), x.totcrdpts,null) ) ) / (sum( IF((x.course_id!= 'honour' and x.course_id!= 'minor' ), x.credit_hours,null)) + sum( IF((x.course_id= 'honour'), x.credit_hours,null) ) ) ) ) as GPA_with_H,
  
  
   IF(COUNT(DISTINCT CASE WHEN ((x.course_id= 'honour' and (x.grade is null ||  x.grade = 'F'))or (x.course_id!= 'honour' and x.course_id!= 'minor'  and (x.grade is null || x.grade = 'F' ))) THEN 1 END),'FAIL', IF(COUNT(DISTINCT CASE WHEN (x.course_id= 'honour' and (x.grade <> 'F'  and x.grade is not null)) THEN 1 END),'PASS','N/A') ) as H_status, 	
    
		SUM( IF ( (x.course_id= 'honour' and  (x.grade is null || x.grade = 'F')), 1, 0))  as  count_H_failed_sub,	 
      COALESCE(  GROUP_CONCAT(( IF( (x.course_id= 'honour' and (x.grade is null || x.grade = 'F')),x.sub,null) ) SEPARATOR ','),'N/A') as  H_fail_sub_list	,GROUP_CONCAT( (concat( x.sub,'(',x.grade,')')) SEPARATOR ',') as sublist    	    		    	
	
	    

     from (
      select grade_points.points ,  (grp.credit_hours*grade_points.points)  as totcrdpts , grp.* from
     (select A.admn_no,A.grade,c.name,c.subject_id as  sub,c.credit_hours,   d.aggr_id,d.semester,d.sequence,e.course_id from 
     (select a.admn_no,a.grade,b.subject_id, b.sub_map_id  from marks_subject_description as a
     inner join marks_master as b on a.marks_master_id=b.id where b.session_year='" . $sy . "' and  b.session='" . $sess . "' and b.`type`='" . $etype . "'   ". $where_add2.") A 
     inner join subjects as c on A.subject_id=c.id
	  inner join course_structure as d on A.subject_id=d.id   
	  inner join subject_mapping as e on A.sub_map_id = e.map_id where e.dept_id='" . $deptid . "' and e.semester='" . $sem . "'  and   " . $replace . "        and e.branch_id='" . $bid . "'     " . $where_add . "  
	   group by A.admn_no,A.subject_id )grp
      left join grade_points on grade_points.grade=grp.grade  order by grp.admn_no, grp.semester,grp.sequence asc	  ) x
      group by x.admn_no  order by x.admn_no )G
      left join user_details u on u.id=G.admn_no";
            }
        }*/
          if ($etype == 'R') {
            if ($cid == 'minor') {
                $myquery = "			 
                               SELECT CONCAT_WS(' ',u.first_name, u.middle_name, u.last_name) AS st_name, dpt.name as dept_name,  G.*
                               FROM (
                                    SELECT x.admn_no,
		                      SUM(IF((x.course_id= 'minor'), x.totcrdpts, NULL)) AS core_tot, 
   	 SUM(IF((x.course_id= 'minor'), x.credit_hours, NULL)) AS core_crdthr,
  	    IF 
			 (COUNT(DISTINCT CASE WHEN (x.course_id= 'minor' AND x.grade = 'F') THEN 1 END),'INC', 
			 (SUM(IF((x.course_id= 'minor'), x.totcrdpts, NULL)) / SUM(IF((x.course_id='minor'), x.credit_hours, NULL)))) AS core_GPA, 
         IF(COUNT(DISTINCT CASE WHEN (x.course_id ='minor' AND x.grade = 'F') THEN 1 END),'FAIL', 'PASS') AS core_status,
		    SUM(IF ((x.course_id= 'minor' AND x.grade = 'F'), 1, 0)) AS count_core_failed_sub, 
                    SUM(IF ((x.course_id= 'minor' AND x.grade <> 'F'), 1, 0)) as  count_core_passed_sub,
		 	GROUP_CONCAT((IF((x.course_id= 'minor' AND x.grade = 'F'),x.sub, NULL)) SEPARATOR ',') AS core_fail_sub_list,GROUP_CONCAT( concat( x.sub,'(',x.grade,')') SEPARATOR ',') as sublist
		 
		 
		 
FROM (
SELECT grade_points.points, (grp.credit_hours*grade_points.points) AS totcrdpts, grp.*
FROM (
SELECT A.admn_no,A.grade,c.name,c.subject_id AS sub,c.credit_hours, d.aggr_id,d.semester,d.sequence, e.course_id
FROM (
SELECT a.admn_no,a.grade,b.subject_id, b.sub_map_id
FROM marks_subject_description AS a
INNER JOIN marks_master AS b ON a.marks_master_id=b.id
WHERE b.session_year='" . $sy . "' and  b.session='" . $sess . "' and b.`type`='" . $etype . "' ) A
INNER JOIN subjects AS c ON A.subject_id=c.id
INNER JOIN course_structure AS d ON A.subject_id=d.id
INNER JOIN subject_mapping AS e ON A.sub_map_id = e.map_id
WHERE e.dept_id='" . $deptid . "' and e.semester='" . $sem . "'  AND ( e.course_id='" . $cid . "' ) and e.branch_id='" . $bid . "'
GROUP BY A.admn_no,A.subject_id)grp
LEFT JOIN grade_points ON grade_points.grade=grp.grade
ORDER BY grp.admn_no, grp.semester,grp.sequence ASC) x
GROUP BY x.admn_no
)G
LEFT JOIN user_details u ON u.id=G.admn_no
LEFT JOIN departments dpt ON dpt.id=u.dept_id 
INNER join ( select hf2.admn_no,hm_minor_details.branch_id from hm_form hf2  
                    inner join hm_minor_details on hm_minor_details.form_id=hf2.form_id 
                          and hm_minor_details.offered='1' and hf2.minor='1' and hf2.minor_hod_status='Y'
          )K 
on G.admn_no=K.admn_no
ORDER BY G.admn_no
";
            } else {


                if ($deptid == 'comm') {
                    $where_add = " and  e.section='" . $sec_name . "'";
                } else {
                    $where_add = "";
                }


                if ($sem >= 5 and $cid == 'b.tech')
                    $replace = "(e.course_id='" . $cid . "' or  e.course_id='honour')";
                else
                    $replace = "e.course_id='" . $cid . "' ";

                $myquery = "   
  select concat_ws(' ',u.first_name, u.middle_name, u.last_name) as st_name ,G.* from
( select  x.admn_no, 	 
  sum( IF((x.course_id!= 'honour' and x.course_id!= 'minor'  ), x.totcrdpts,null) )  as core_tot,	    
  sum( IF((x.course_id!= 'honour' and x.course_id!= 'minor'  ), x.credit_hours,null) )  as core_crdthr,	    

 IF(COUNT(DISTINCT CASE WHEN (x.course_id!= 'honour' and x.course_id!= 'minor'  and x.grade = 'F') THEN 1  END),'INC',
	(  sum( IF((x.course_id!= 'honour' and x.course_id!= 'minor'  ), x.totcrdpts,null) ) / sum( IF((x.course_id!= 'honour' and x.course_id!= 'minor'  ), x.credit_hours,null))  )
	) as core_GPA,  
	  	    	    		    	
  IF(COUNT(DISTINCT CASE WHEN (x.course_id!= 'honour' and x.course_id!= 'minor'  and x.grade = 'F') THEN 1  END),'FAIL', 'PASS')  as  core_status,	
  SUM( IF ( (x.course_id!= 'honour' and x.course_id!= 'minor'  and  x.grade = 'F'), 1, 0))as  count_core_failed_sub,		  
  SUM( IF ( (x.course_id!= 'honour' and x.course_id!= 'minor'  and  x.grade <> 'F'), 1, 0))as  count_core_passed_sub,
  
  GROUP_CONCAT(( IF( (x.course_id!= 'honour' and x.course_id!= 'minor'  and x.grade = 'F'),x.sub,null) ) SEPARATOR ',') as  core_fail_sub_list,
  
   sum( IF((x.course_id= 'honour'), x.totcrdpts,null) )  as H_only,
   
   ( sum( IF((x.course_id!= 'honour' and x.course_id!= 'minor'  ), x.totcrdpts,null) ) + 
     sum( IF((x.course_id= 'honour'), x.totcrdpts,null) ) 
	)  as H_tot	,
	
	 sum( IF((x.course_id= 'honour'), x.credit_hours,null) )  as H_crdthr,
	(sum( IF((x.course_id!= 'honour' and x.course_id!= 'minor'  ), x.credit_hours,null)) +   sum( IF((x.course_id= 'honour'), x.credit_hours,null) )  ) as crdthr_with_H,
	
	    IF(COUNT(DISTINCT CASE WHEN ((x.course_id= 'honour' and x.grade = 'F')or (x.course_id!= 'honour' and x.course_id!= 'minor'  and x.grade = 'F' )) THEN 1 END),'INC',
  ( ( sum( IF((x.course_id!= 'honour' and x.course_id!= 'minor' ), x.totcrdpts,null) ) + sum( IF((x.course_id= 'honour'), x.totcrdpts,null) ) ) / (sum( IF((x.course_id!= 'honour' and x.course_id!= 'minor' ), x.credit_hours,null)) + sum( IF((x.course_id= 'honour'), x.credit_hours,null) ) ) ) ) as GPA_with_H,
  
  
   IF(COUNT(DISTINCT CASE WHEN ((x.course_id= 'honour' and x.grade = 'F')or (x.course_id!= 'honour' and x.course_id!= 'minor'  and x.grade = 'F' )) THEN 1 END),'FAIL', IF(COUNT(DISTINCT CASE WHEN (x.course_id= 'honour' and x.grade <> 'F') THEN 1 END),'PASS','N/A') ) as H_status, 	
     /* if( x.course_id= 'honour',SUM( IF ( (x.course_id= 'honour' and  x.grade = 'F'), 1, 0)) ,'N/A') as  count_H_failed_sub,*/	
		SUM( IF ( (x.course_id= 'honour' and  x.grade = 'F'), 1, 0))  as  count_H_failed_sub,	 
      COALESCE(  GROUP_CONCAT(( IF( (x.course_id= 'honour' and x.grade = 'F'),x.sub,null) ) SEPARATOR ','),'N/A') as  H_fail_sub_list	,GROUP_CONCAT( (concat( x.sub,'(',x.grade,')')) SEPARATOR ',') as sublist    	    		    	
	
	    

     from (
      select grade_points.points ,  (grp.credit_hours*grade_points.points)  as totcrdpts , grp.* from
     (select A.admn_no,A.grade,c.name,c.subject_id as  sub,c.credit_hours,   d.aggr_id,d.semester,d.sequence,e.course_id from 
     (select a.admn_no,a.grade,b.subject_id, b.sub_map_id  from marks_subject_description as a
     inner join marks_master as b on a.marks_master_id=b.id where b.session_year='" . $sy . "' and  b.session='" . $sess . "' and b.`type`='" . $etype . "') A 
     inner join subjects as c on A.subject_id=c.id
	  inner join course_structure as d on A.subject_id=d.id   
	  inner join subject_mapping as e on A.sub_map_id = e.map_id where e.dept_id='" . $deptid . "' and e.semester='" . $sem . "'  and   " . $replace . "        and e.branch_id='" . $bid . "'     " . $where_add . "  
	   group by A.admn_no,A.subject_id )grp
      left join grade_points on grade_points.grade=grp.grade  order by grp.admn_no, grp.semester,grp.sequence asc	  ) x
      group by x.admn_no  order by x.admn_no )G
      left join user_details u on u.id=G.admn_no";
            }
       }
        else if ($etype == 'other' || $etype == 'spl') {
            if ($etype == 'other')
                $type = 'O';
            else if ($etype == 'spl')
                $type = 'S';
            $myquery = " SELECT CONCAT_WS(' ',u.first_name, u.middle_name, u.last_name) AS st_name, dpt.name AS dept_name,x.* 
FROM 
(
SELECT G.*,group_concat((IF((b.mis_sub_id ),concat( b.sub_code,'(',b.grade,')'), NULL)) SEPARATOR ',')  as sublist, 
SUM(IF (( b.grade = 'F'), 1, 0)) AS count_core_failed_sub, GROUP_CONCAT((IF(( b.grade= 'F'),b.sub_code, NULL)) SEPARATOR ',') AS core_fail_sub_list
,SUM(IF ((b.grade <> 'F'), 1, 0))  AS count_core_passed_sub
FROM
(
SELECT a.admn_no, a.id, a.tot_cr_hr AS core_crdthr,a.tot_cr_pts AS core_tot,
(CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE a.gpa END) AS core_GPA,
 a.`status` AS core_status,
 a.cgpa
FROM final_semwise_marks_foil a
WHERE a.session_yr='" . $sy . "' AND a.`session`='" . $sess . "' AND a.dept='" . $deptid . "' AND a.course='" . $cid . "' AND a.branch='" . $bid . "' AND a.semester='" . $sem . "' AND a.`type`='" . $type . "'  " . $where_add2." )G
INNER JOIN final_semwise_marks_foil_desc b ON G.id=b.foil_id AND G.admn_no=b.admn_no 
group by b.foil_id  order by b.admn_no 
)x
LEFT JOIN user_details u ON u.id=x.admn_no
LEFT JOIN departments dpt ON dpt.id=u.dept_id";
        }
        else if ($etype == 'jrf') {
            $myquery = " SELECT G.*
FROM (
SELECT x.st_name,x.admn_no, SUM(IF((x.course_id='jrf'), x.totcrdpts, NULL)) AS core_tot,
 SUM(IF((x.course_id= 'jrf'), x.credit_hours, NULL)) AS core_crdthr, 
 IF(COUNT(DISTINCT CASE WHEN (x.course_id='jrf' AND ( x.grade = 'F'|| x.grade = 'D')) THEN 1 END),'INC', 
 (SUM(IF((x.course_id='jrf'), x.totcrdpts, NULL)) / SUM(IF((x.course_id='jrf'), x.credit_hours, NULL)))) AS core_GPA,
  IF(COUNT(DISTINCT CASE WHEN (x.course_id='jrf' AND ( x.grade = 'F'|| x.grade = 'D')) THEN 1 END),'FAIL', 'PASS') AS core_status, 
  SUM(IF ((x.course_id='jrf' AND ( x.grade = 'F'|| x.grade = 'D')), 1, 0)) AS count_core_failed_sub, 
  SUM(IF ((x.course_id='jrf' AND ( x.grade <> 'F' and  x.grade <> 'D')), 1, 0)) AS count_core_passed_sub, 
  GROUP_CONCAT((IF((x.course_id='jrf' AND ( x.grade = 'F'|| x.grade = 'D')),x.sub, NULL)) SEPARATOR ',') AS core_fail_sub_list, GROUP_CONCAT((CONCAT(x.name,'(',x.sub,')',' / ',x.grade)) SEPARATOR '\\n') AS sublist

FROM (
SELECT grade_points.points, (grp.credit_hours*grade_points.points) AS totcrdpts, grp.*
FROM (
SELECT A.admn_no,A.grade,A.st_name,c.name,c.subject_id AS sub,c.credit_hours, 'jrf' as course_id
FROM (
SELECT a.admn_no,a.grade,b.subject_id, b.sub_map_id,CONCAT_WS(' ',u.first_name, u.middle_name, u.last_name) AS st_name
FROM marks_subject_description AS a
INNER JOIN marks_master AS b ON a.marks_master_id=b.id
inner  JOIN user_details u ON u.id=a.admn_no 
WHERE u.dept_id= '" . $deptid . "'  and  b.session_year='" . $sy . "' AND b.session='" . $sess . "' AND b.`type`='J'  ". $where_add2.") A
INNER JOIN subjects AS c ON A.subject_id=c.id

/*INNER JOIN subject_mapping AS e ON A.sub_map_id = e.map_id
inner join user_details u on u.dept_id= e.dept_id and  u.dept_id= '" . $deptid . "'
 WHERE e.dept_id='" . $deptid . "' AND  e.course_id='" . $cid . "' AND e.branch_id='" . $bid . "'
*/
GROUP BY A.admn_no,A.subject_id)grp
LEFT JOIN grade_points ON grade_points.grade=grp.grade
ORDER BY grp.admn_no ASC) x
GROUP BY x.admn_no
ORDER BY x.admn_no)G

";
        }




        $query = $this->db->query($myquery);
        //  echo   $this->db->last_query(); die();


        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
    function show_data_for_view_redeclared($id,$pub){
        $q="select   GROUP_CONCAT(x.admn_no) as admn_no from  result_declaration_log_partial_details x  where x.res_dec_id=?  and x.published_on=? and x.status='D'";
        $q=$this->db->query($q,array($id,$pub));
        if($q->num_rows() > 0)
            return $q->row()->admn_no;
        return false;
    }
            
     function insert_re_declared_data($data)
        {            
          /*  $sql = "insert into result_declaration_log_details (res_dec_id,admn_no)values (?, ?)";
            $this-> db-> query($sql,array($id,$admn_no));
            return TRUE;*/
            //print_r($data); die();
         if(count($data) > 0){
            if (!$this->db->insert_batch('result_declaration_log_partial_details', $data)) {
            return false;
           } else{ return true; }
        }else{
            return false;
        }
        }
        
         function update_re_declared_data($data,$con)
        {            
             
            $this->db->update('result_declaration_log_partial_details', $data,$con);
            if($this->db->affected_rows() > 0){
                return true;
            }
            return false;
        
        }
        
        function get_result_data_marks_master($adm,$s,$sy,$sem,$c,$b){
            $q = "SELECT b.sessional,b.theory,b.practical,b.total,b.grade,c.subject_id,c.name
                  FROM marks_master AS a
                  JOIN marks_subject_description AS b ON a.id=b.marks_master_id
                  JOIN subjects AS c ON a.subject_id=c.id
                  JOIN subject_mapping AS d ON d.map_id=a.sub_map_id
                  WHERE b.admn_no=? AND d.`session`=? AND d.session_year=? AND d.semester=? AND  d.course_id=? AND d.branch_id=?";
              $q =$this->db->query($q,array($adm,$s,$sy,$sem,$c,$b));
              if($q->num_rows() > 0){
                  return $q->result();
              }
              return false;
         }
        
         function get_result_data_marks_master_bkp($adm,$s,$sy,$sem,$c,$b){
            $q = "SELECT b.sessional,b.theory,b.practical,b.total,b.grade,c.subject_id,c.name
                  FROM marks_master AS a
                  JOIN marks_subject_description_backup AS b ON a.id=b.marks_master_id
                  JOIN subjects AS c ON a.subject_id=c.id
                  JOIN subject_mapping AS d ON d.map_id=a.sub_map_id
                  WHERE b.admn_no=? AND d.`session`=? AND d.session_year=? AND d.semester=?  AND d.course_id=? AND d.branch_id=?";
              $q =$this->db->query($q,array($adm,$s,$sy,$sem,$c,$b));
              if($q->num_rows() > 0){
                  return $q->result();
              }
              return false;
         }
      
         function get_with_held_republished_date_id($id){
             $q=" select   * from  result_declaration_log_partial_details x  where x.res_dec_id=?  and x.status='D'  group by x.published_on order by x.id ";
             $q=$this->db->query($q,array($id));
             if($q->num_rows() > 0){
                 return $q->result();
             }
             return array();
         }
}

?>
