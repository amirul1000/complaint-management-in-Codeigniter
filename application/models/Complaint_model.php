<?php

/**
 * Author: Amirul Momenin
 * Desc:Complaint Model
 */
class Complaint_model extends CI_Model
{
	protected $complaint = 'complaint';
	
    function __construct(){
        parent::__construct();
    }
	
    /** Get complaint by id
	 *@param $id - primary key to get record
	 *
     */
    function get_complaint($id){
        $result = $this->db->get_where('complaint',array('id'=>$id))->row_array();
		$db_error = $this->db->error();
		if (!empty($db_error['code'])){
			echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
			exit;
		}
		return $result;
    } 
	
    /** Get all complaint
	 *
     */
    function get_all_complaint(){
        $this->db->order_by('id', 'desc');
        $result = $this->db->get('complaint')->result_array();
		$db_error = $this->db->error();
		if (!empty($db_error['code'])){
			echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
			exit;
		}
		return $result;
    }
	
	/** Get limit complaint
	 *@param $limit - limit of query , $start - start of db table index to get query
	 *
     */
    function get_limit_complaint($limit, $start){
		$this->db->order_by('id', 'desc');
        $this->db->limit($limit, $start);
        $result = $this->db->get('complaint')->result_array();
		$db_error = $this->db->error();
		if (!empty($db_error['code'])){
			echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
			exit;
		}
		return $result;
    }
	
    /** Count complaint rows
	 *
     */
	function get_count_complaint(){
       $result = $this->db->from("complaint")->count_all_results();
	   $db_error = $this->db->error();
		if (!empty($db_error['code'])){
			echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
			exit;
		}
		return $result;
    }
	
	
	/** Get limit complaint
	 *@param $limit - limit of query , $start - start of db table index to get query
	 *
     */
    function get_limit_users_complaint($limit, $start){
		$this->db->where('users_id', $this->session->userdata('id'));
		$this->db->order_by('id', 'desc');
        $this->db->limit($limit, $start);
        $result = $this->db->get('complaint')->result_array();
		$db_error = $this->db->error();
		if (!empty($db_error['code'])){
			echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
			exit;
		}
		return $result;
    }
	
    /** Count complaint rows
	 *
     */
	function get_count_users_complaint(){
	   $this->db->where('users_id', $this->session->userdata('id'));	
       $result = $this->db->from("complaint")->count_all_results();
	   $db_error = $this->db->error();
		if (!empty($db_error['code'])){
			echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
			exit;
		}
		return $result;
    }
	
	
    /** function to add new complaint
	 *@param $params - data set to add record
	 *
     */
    function add_complaint($params){
        $this->db->insert('complaint',$params);
        $id = $this->db->insert_id();
		$db_error = $this->db->error();
		if (!empty($db_error['code'])){
			echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
			exit;
		}
		return $id;
    }
	
    /** function to update complaint
	 *@param $id - primary key to update record,$params - data set to add record
	 *
     */
    function update_complaint($id,$params){
        $this->db->where('id',$id);
        $status = $this->db->update('complaint',$params);
		$db_error = $this->db->error();
		if (!empty($db_error['code'])){
			echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
			exit;
		}
		return $status;
    }
	
    /** function to delete complaint
	 *@param $id - primary key to delete record
	 *
     */
    function delete_complaint($id){
        $status = $this->db->delete('complaint',array('id'=>$id));
		$db_error = $this->db->error();
		if (!empty($db_error['code'])){
			echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
			exit;
		}
		return $status;
    }
}
