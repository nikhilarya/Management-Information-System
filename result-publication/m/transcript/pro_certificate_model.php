<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pro_certificate_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function get_stu_details($adm_no) {

        $sql = "select a.id,concat(a.first_name,' ',a.middle_name,' ',a.last_name) as stu_name,b.course_id,b.branch_id,c.name as cname from user_details a inner join stu_academic b on a.id = b.admn_no
inner join cs_branches c on c.id=b.branch_id where a.id=?";



        $query = $this->db->query($sql, array($adm_no));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

}

?>