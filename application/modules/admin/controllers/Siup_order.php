<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Siup_order extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Siup_order_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $crud = $this->generate_crud('siup_order');        
//        $this->unset_crud_fields('added_by', 'changed_by','last_modified');

        // only webmaster and admin can change member groups
        if ($this->ion_auth->in_group('staff'))
        {        
        	$crud->columns('id_order', 'date', 'id_unit', 'id_product');
        	$crud->add_fields('id_order', 'date', 'id_unit', 'id_product');
        	$crud->edit_fields('id_order', 'date', 'id_unit', 'id_product');
        	
        } else if ($this->ion_auth->in_group('purchasing'))
        {        
        	$crud->columns('id_order', 'date', 'id_unit', 'id_product','stver', 'stacc', 'stord', 'tgl_ver', 'tgl_acc', 'tgl_pch');
        	$crud->unset_add();
        	$crud->edit_fields('id_order', 'date', 'id_unit', 'id_product','stord','tgl_terima');
        } else if ($this->ion_auth->in_group('Accounting'))
        {
        	$crud->columns('id_order', 'date', 'id_unit', 'id_product','stver', 'stacc', 'tgl_ver', 'tgl_acc');
        	$crud->unset_add();
			$crud->edit_fields('id_order', 'date', 'id_unit', 'id_product','stacc','tgl_acc');
		} else if ($this->ion_auth->in_group('verifikator'))
		{
			$crud->columns('id_order', 'date', 'id_unit', 'id_product','stver', 'tgl_ver');
			$crud->unset_add();
			$crud->edit_fields('id_order', 'date', 'id_unit', 'id_product','stver','tgl_ver');
		}

        // only webmaster and admin can reset user password
        if ($this->ion_auth->in_group(array('webmaster', 'admin')))
        {
//                $crud->add_action('Add Kemasan', '', 'admin/siup_kemasan/add', 'fa fa-repeat');
        }
       // $crud->add_fields('id_order', 'date', 'id_unit', 'id_product');
		$crud->unset_add_fields(array('stver','stacc','stpch','stord'));
        // disable direct create / delete Frontend User
        //$crud->unset_add();
        //$crud->unset_delete();
		$crud->set_relation('id_product','siup_product','desc');
		$crud->set_relation('id_unit','siup_unit','desc');
		
        $this->mTitle = 'Data Order';
        $this->render_crud();
    }

    public function read($id) 
    {
        $row = $this->Siup_order_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id_order' => $row->id_order,
		'date' => $row->date,
		'id_unit' => $row->id_unit,
		'id_product' => $row->id_product,
		'stver' => $row->stver,
		'stacc' => $row->stacc,
		'stpch' => $row->stpch,
		'stord' => $row->stord,
		'ket' => $row->ket,
		'tgl_ver' => $row->tgl_ver,
		'tgl_acc' => $row->tgl_acc,
		'tgl_pch' => $row->tgl_pch,
		'tgl_terima' => $row->tgl_terima,
	    );
            $this->load->view('siup_order_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('siup_order'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('siup_order/create_action'),
	    'id_order' => set_value('id_order'),
	    'date' => set_value('date'),
	    'id_unit' => set_value('id_unit'),
	    'id_product' => set_value('id_product'),
	    'stver' => set_value('stver'),
	    'stacc' => set_value('stacc'),
	    'stpch' => set_value('stpch'),
	    'stord' => set_value('stord'),
	    'ket' => set_value('ket'),
	    'tgl_ver' => set_value('tgl_ver'),
	    'tgl_acc' => set_value('tgl_acc'),
	    'tgl_pch' => set_value('tgl_pch'),
	    'tgl_terima' => set_value('tgl_terima'),
	);
        $this->load->view('siup_order_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'date' => $this->input->post('date',TRUE),
		'id_unit' => $this->input->post('id_unit',TRUE),
		'id_product' => $this->input->post('id_product',TRUE),
		'stver' => $this->input->post('stver',TRUE),
		'stacc' => $this->input->post('stacc',TRUE),
		'stpch' => $this->input->post('stpch',TRUE),
		'stord' => $this->input->post('stord',TRUE),
		'ket' => $this->input->post('ket',TRUE),
		'tgl_ver' => $this->input->post('tgl_ver',TRUE),
		'tgl_acc' => $this->input->post('tgl_acc',TRUE),
		'tgl_pch' => $this->input->post('tgl_pch',TRUE),
		'tgl_terima' => $this->input->post('tgl_terima',TRUE),
	    );

            $this->Siup_order_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('siup_order'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Siup_order_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('siup_order/update_action'),
		'id_order' => set_value('id_order', $row->id_order),
		'date' => set_value('date', $row->date),
		'id_unit' => set_value('id_unit', $row->id_unit),
		'id_product' => set_value('id_product', $row->id_product),
		'stver' => set_value('stver', $row->stver),
		'stacc' => set_value('stacc', $row->stacc),
		'stpch' => set_value('stpch', $row->stpch),
		'stord' => set_value('stord', $row->stord),
		'ket' => set_value('ket', $row->ket),
		'tgl_ver' => set_value('tgl_ver', $row->tgl_ver),
		'tgl_acc' => set_value('tgl_acc', $row->tgl_acc),
		'tgl_pch' => set_value('tgl_pch', $row->tgl_pch),
		'tgl_terima' => set_value('tgl_terima', $row->tgl_terima),
	    );
            $this->load->view('siup_order_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('siup_order'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_order', TRUE));
        } else {
            $data = array(
		'date' => $this->input->post('date',TRUE),
		'id_unit' => $this->input->post('id_unit',TRUE),
		'id_product' => $this->input->post('id_product',TRUE),
		'stver' => $this->input->post('stver',TRUE),
		'stacc' => $this->input->post('stacc',TRUE),
		'stpch' => $this->input->post('stpch',TRUE),
		'stord' => $this->input->post('stord',TRUE),
		'ket' => $this->input->post('ket',TRUE),
		'tgl_ver' => $this->input->post('tgl_ver',TRUE),
		'tgl_acc' => $this->input->post('tgl_acc',TRUE),
		'tgl_pch' => $this->input->post('tgl_pch',TRUE),
		'tgl_terima' => $this->input->post('tgl_terima',TRUE),
	    );

            $this->Siup_order_model->update($this->input->post('id_order', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('siup_order'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Siup_order_model->get_by_id($id);

        if ($row) {
            $this->Siup_order_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('siup_order'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('siup_order'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('date', 'date', 'trim|required');
	$this->form_validation->set_rules('id_unit', 'id unit', 'trim|required');
	$this->form_validation->set_rules('id_product', 'id product', 'trim|required');
	$this->form_validation->set_rules('stver', 'stver', 'trim|required');
	$this->form_validation->set_rules('stacc', 'stacc', 'trim|required');
	$this->form_validation->set_rules('stpch', 'stpch', 'trim|required');
	$this->form_validation->set_rules('stord', 'stord', 'trim|required');
	$this->form_validation->set_rules('ket', 'ket', 'trim|required');
	$this->form_validation->set_rules('tgl_ver', 'tgl ver', 'trim|required');
	$this->form_validation->set_rules('tgl_acc', 'tgl acc', 'trim|required');
	$this->form_validation->set_rules('tgl_pch', 'tgl pch', 'trim|required');
	$this->form_validation->set_rules('tgl_terima', 'tgl terima', 'trim|required');

	$this->form_validation->set_rules('id_order', 'id_order', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Siup_order.php */
/* Location: ./application/controllers/Siup_order.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-03-28 17:17:20 */
/* http://harviacode.com */