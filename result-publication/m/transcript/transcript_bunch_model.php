<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transcript_bunch_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function get_all_student($did, $cid, $bid,$sem) {

        $sql = "SELECT a.id, CONCAT(a.first_name,' ',a.middle_name,' ',a.last_name)as stu_name,b.course_id,b.branch_id,b.semester,e.name as dname,c.name as cname,d.name as bname
FROM user_details a
INNER JOIN stu_academic b ON b.admn_no=a.id
inner join cs_courses c on c.id=b.course_id
inner join cs_branches d on d.id=b.branch_id
inner join departments e on e.id=a.dept_id
WHERE a.dept_id=? AND b.course_id=? AND b.branch_id=? AND b.semester=? order by a.id";



        $query = $this->db->query($sql, array($did, $cid, $bid,$sem));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

}

?>