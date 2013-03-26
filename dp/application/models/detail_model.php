<?php
/* This model handles all the database requests that involve mainly,
 * the details table and its relations */
class Detail_model extends CI_Model{
    public function  __construct() {
        parent::__construct();
    }

    /* check if the $new_detail is already a detail in the details database table
     * and if it is, return its id
     */
    public function is_detail($new_detail)
    {
        $this->db->select('detail_id, detail_name')
                 ->from('details')
                 ->where('detail_name', strtolower($new_detail));

        $query = $this->db->get();

        if($query->num_rows() > 0){
            $data = $query->result();
            return $data[0]->detail_id;
        }

        return false;
    }

    /* check if the detail with the id = $detail_id belongs to the product name with the id = $pn_id*/
    public function forProductName($detail_id, $pn_id)
    {
        $this->db->select('detail_id, pn_id')
                 ->from('pn_details')
                 ->where('detail_id', $detail_id)
                 ->where('pn_id', $pn_id);

        $query = $this->db->get();

        if($query->num_rows() > 0){
            return true;
        }

        return false;

    }

    /* insert a row in the pn_details table */
    public function insertRow($detail_id, $pn_id)
    {
        $data = array(
                        'detail_id' => $detail_id,
                        'pn_id' => $pn_id
                     );

        $this->db->insert('pn_details', $data);
    }

    /* insert the detail into the details database table */
    public function insertDetail($new_detail)
    {
        $data = array(
                        'detail_name' => $new_detail
                     );

        $this->db->insert('details', $data);
    }

    /* get the last id inserted in the database */
    public function last_id()
    {
        return $this->db->insert_id();
    }

    /* get the name of the detail with the detail_id = $is */
    public function getDetailName($id)
    {
        $this->db->select('detail_name')
                 ->from('details')
                 ->where('detail_id', $id);

        $query = $this->db->get();

        return $query->result();
    }

}
