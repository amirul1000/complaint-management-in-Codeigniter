<?php

 /**
 * Author: Amirul Momenin
 * Desc:Complaint Controller
 *
 */
class Complaint extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('session');
		$this->load->library('pagination');
		$this->load->library('Customlib');
		$this->load->helper(array('cookie', 'url')); 
		$this->load->database();  
		$this->load->model('Complaint_model');
		if(! $this->session->userdata('validated')){
				redirect('admin/login/index');
		}  
    } 
	
    /**
	 * Index Page for this controller.
	 *@param $start - Starting of complaint table's index to get query
	 *
	 */
    function index($start=0){
		$limit = 10;
        $data['complaint'] = $this->Complaint_model->get_limit_complaint($limit,$start);
		//pagination
		$config['base_url'] = site_url('admin/complaint/index');
		$config['total_rows'] = $this->Complaint_model->get_count_complaint();
		$config['per_page'] = 10;
		//Bootstrap 4 Pagination fix
		$config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination">';
		$config['full_tag_close']   = '</ul></nav></div>';
		$config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
		$config['num_tag_close']    = '</span></li>';
		$config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
		$config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
		$config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['next_tag_close']   = '<span aria-hidden="true"></span></span></li>';
		$config['next_tag_close']   = '<span aria-hidden="true">&raquo;</span></span></li>';
		$config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['prev_tag_close']   = '</span></li>';
		$config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
		$config['first_tag_close']  = '</span></li>';
		$config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['last_tag_close']   = '</span></li>';		
		$this->pagination->initialize($config);
        $data['link'] =$this->pagination->create_links();
		
        $data['_view'] = 'admin/complaint/index';
        $this->load->view('layouts/admin/body',$data);
    }
	
	 /**
     * Save complaint
	 *@param $id - primary key to update
	 *
     */
    function save($id=-1){   
		$file_picture = "";
 
		$created_at = "";
$updated_at = "";

		if($id<=0){
															 $created_at = date("Y-m-d H:i:s");
														 }
else if($id>0){
															 $updated_at = date("Y-m-d H:i:s");
														 }

		$params = array(
					 'users_id' => html_escape($this->input->post('users_id')),
'category_id' => html_escape($this->input->post('category_id')),
'subject' => html_escape($this->input->post('subject')),
'description' => html_escape($this->input->post('description')),
'file_picture' => $file_picture,
'cell_phone' => html_escape($this->input->post('cell_phone')),
'email' => html_escape($this->input->post('email')),
'address' => html_escape($this->input->post('address')),
'status' => html_escape($this->input->post('status')),
'created_at' =>$created_at,
'updated_at' =>$updated_at,

				);
		
						$config['upload_path']          = "./public/uploads/images/complaint";
						$config['allowed_types']        = "gif|jpg|png";
						$config['max_size']             = 100;
						$config['max_width']            = 1024;
						$config['max_height']           = 768;
						$this->load->library('upload', $config);
						
						if(isset($_POST) && count($_POST) > 0)     
							{  
							  if(strlen($_FILES['file_picture']['name'])>0 && $_FILES['file_picture']['size']>0)
								{
									if ( ! $this->upload->do_upload('file_picture'))
									{
										$error = array('error' => $this->upload->display_errors());
									}
									else
									{
										$file_picture = "uploads/images/complaint/".$_FILES['file_picture']['name'];
									    $params['file_picture'] = $file_picture;
									}
								}
								else
								{
									unset($params['file_picture']);
								}
							}
							
						    
		if($id>0){
							                        unset($params['created_at']);
						                          }if($id<=0){
							                        unset($params['updated_at']);
						                          } 
		$data['id'] = $id;
		//update		
        if(isset($id) && $id>0){
			$data['complaint'] = $this->Complaint_model->get_complaint($id);
            if(isset($_POST) && count($_POST) > 0){   
                $this->Complaint_model->update_complaint($id,$params);
                redirect('admin/complaint/index');
            }else{
                $data['_view'] = 'admin/complaint/form';
                $this->load->view('layouts/admin/body',$data);
            }
        } //save
		else{
			if(isset($_POST) && count($_POST) > 0){   
                $complaint_id = $this->Complaint_model->add_complaint($params);
                redirect('admin/complaint/index');
            }else{  
			    $data['complaint'] = $this->Complaint_model->get_complaint(0);
                $data['_view'] = 'admin/complaint/form';
                $this->load->view('layouts/admin/body',$data);
            }
		}
        
    } 
	
	/**
     * Details complaint
	 * @param $id - primary key to get record
	 *
     */
	function details($id){
        $data['complaint'] = $this->Complaint_model->get_complaint($id);
		$data['id'] = $id;
        $data['_view'] = 'admin/complaint/details';
        $this->load->view('layouts/admin/body',$data);
	}
	
    /**
     * Deleting complaint
	 * @param $id - primary key to delete record
	 *
     */
    function remove($id){
        $complaint = $this->Complaint_model->get_complaint($id);

        // check if the complaint exists before trying to delete it
        if(isset($complaint['id'])){
            $this->Complaint_model->delete_complaint($id);
            redirect('admin/complaint/index');
        }
        else
            show_error('The complaint you are trying to delete does not exist.');
    }
	
	/**
     * Search complaint
	 * @param $start - Starting of complaint table's index to get query
     */
	function search($start=0){
		if(!empty($this->input->post('key'))){
			$key =$this->input->post('key');
			$_SESSION['key'] = $key;
		}else{
			$key = $_SESSION['key'];
		}
		
		$limit = 10;		
		$this->db->like('id', $key, 'both');
$this->db->or_like('users_id', $key, 'both');
$this->db->or_like('category_id', $key, 'both');
$this->db->or_like('subject', $key, 'both');
$this->db->or_like('description', $key, 'both');
$this->db->or_like('file_picture', $key, 'both');
$this->db->or_like('cell_phone', $key, 'both');
$this->db->or_like('email', $key, 'both');
$this->db->or_like('address', $key, 'both');
$this->db->or_like('status', $key, 'both');
$this->db->or_like('created_at', $key, 'both');
$this->db->or_like('updated_at', $key, 'both');


		$this->db->order_by('id', 'desc');
		
        $this->db->limit($limit,$start);
        $data['complaint'] = $this->db->get('complaint')->result_array();
		$db_error = $this->db->error();
		if (!empty($db_error['code'])){
			echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
			exit;
		}
		
		//pagination
		$config['base_url'] = site_url('admin/complaint/search');
		$this->db->reset_query();		
		$this->db->like('id', $key, 'both');
$this->db->or_like('users_id', $key, 'both');
$this->db->or_like('category_id', $key, 'both');
$this->db->or_like('subject', $key, 'both');
$this->db->or_like('description', $key, 'both');
$this->db->or_like('file_picture', $key, 'both');
$this->db->or_like('cell_phone', $key, 'both');
$this->db->or_like('email', $key, 'both');
$this->db->or_like('address', $key, 'both');
$this->db->or_like('status', $key, 'both');
$this->db->or_like('created_at', $key, 'both');
$this->db->or_like('updated_at', $key, 'both');

		$config['total_rows'] = $this->db->from("complaint")->count_all_results();
		$db_error = $this->db->error();
		if (!empty($db_error['code'])){
			echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
			exit;
		}
		$config['per_page'] = 10;
		// Bootstrap 4 Pagination fix
		$config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination">';
		$config['full_tag_close']   = '</ul></nav></div>';
		$config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
		$config['num_tag_close']    = '</span></li>';
		$config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
		$config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
		$config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['next_tag_close']   = '<span aria-hidden="true"></span></span></li>';
		$config['next_tag_close']   = '<span aria-hidden="true">&raquo;</span></span></li>';
		$config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['prev_tag_close']   = '</span></li>';
		$config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
		$config['first_tag_close']  = '</span></li>';
		$config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['last_tag_close']   = '</span></li>';
		$this->pagination->initialize($config);
        $data['link'] =$this->pagination->create_links();
		
		$data['key'] = $key;
		$data['_view'] = 'admin/complaint/index';
        $this->load->view('layouts/admin/body',$data);
	}
	
    /**
     * Export complaint
	 * @param $export_type - CSV or PDF type 
     */
	function export($export_type='CSV'){
	  if($export_type=='CSV'){	
		   // file name 
		   $filename = 'complaint_'.date('Ymd').'.csv'; 
		   header("Content-Description: File Transfer"); 
		   header("Content-Disposition: attachment; filename=$filename"); 
		   header("Content-Type: application/csv; ");
		   // get data 
		   $this->db->order_by('id', 'desc');
		   $complaintData = $this->Complaint_model->get_all_complaint();
		   // file creation 
		   $file = fopen('php://output', 'w');
		   $header = array("Id","Users Id","Category Id","Subject","Description","File Picture","Cell Phone","Email","Address","Status","Created At","Updated At"); 
		   fputcsv($file, $header);
		   foreach ($complaintData as $key=>$line){ 
			 fputcsv($file,$line); 
		   }
		   fclose($file); 
		   exit; 
	  }else if($export_type=='Pdf'){
		    $this->db->order_by('id', 'desc');
		    $complaint = $this->db->get('complaint')->result_array();
		   // get the HTML
			ob_start();
			include(APPPATH.'views/admin/complaint/print_template.php');
			$html = ob_get_clean();
			include(APPPATH."third_party/mpdf60/mpdf.php");					
			$mpdf=new mPDF('','A4'); 
			//$mpdf=new mPDF('c','A4','','',32,25,27,25,16,13); 
			//$mpdf->mirrorMargins = true;
		    $mpdf->SetDisplayMode('fullpage');
			//==============================================================
			$mpdf->autoScriptToLang = true;
			$mpdf->baseScript = 1;	// Use values in classes/ucdn.php  1 = LATIN
			$mpdf->autoVietnamese = true;
			$mpdf->autoArabic = true;
			$mpdf->autoLangToFont = true;
			$mpdf->setAutoBottomMargin = 'stretch';
			$stylesheet = file_get_contents(APPPATH."third_party/mpdf60/lang2fonts.css");
			$mpdf->WriteHTML($stylesheet,1);
			$mpdf->WriteHTML($html);
			//$mpdf->AddPage();
			$mpdf->Output($filePath);
			$mpdf->Output();
			//$mpdf->Output( $filePath,'S');
			exit;	
	  }
	   
	}
}
//End of Complaint controller