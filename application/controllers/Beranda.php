<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beranda extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		if (!$this->session->userdata('id_user')) {
			redirect('auth/login');
		}
	}
	
	public function index()
	{
		$d['page'] = 'beranda';

		//Total Shift Pagi harian (id_shift = 1)
		$condition 			 = [];
		$condition[]		 = ['id_shift', 1, 'where'];
		$condition[]		 = ['date(date)', date('Y-m-d'), 'where'];
		$condition[]		 = ['status', 1, 'where'];
		$get_shift_pg 		 = $this->M_core->get_tbl_ra('t_production', 'total', $condition);
		$get_shift_pg		 = array_column($get_shift_pg, "total");
		$total_shift_pg		 = array_sum($get_shift_pg);

		//Total Shift Sore Harian (id_shift = 2)
		$condition 			 = [];
		$condition[]		 = ['id_shift', 2, 'where'];
		$condition[]		 = ['date(date)', date('Y-m-d'), 'where'];
		$condition[]		 = ['status', 1, 'where'];
		$get_shift_sre 		 = $this->M_core->get_tbl_ra('t_production', 'total', $condition);
		$get_shift_sre		 = array_column($get_shift_sre, "total");
		$total_shift_sre	 = array_sum($get_shift_sre);

		//Total Shift Pagi Mingguan (id_shift = 1)
		$total_mggu_pg 		 = $this->M_core->get_mingguan_pagi();

		//Total Shift Sore Mingguan (id_shift = 2)
		$total_mggu_sre 	 = $this->M_core->get_mingguan_sore();

		//Total Shift Pagi Tahunan (id_shift = 1)
		$condition 			 = [];
		$condition[]		 = ['id_shift', 1, 'where'];
		$condition[]		 = ['YEAR(date)', date('Y'), 'where'];
		$condition[]		 = ['status', 1, 'where'];
		$get_thn_pg 		 = $this->M_core->get_tbl_ra('t_production', 'total', $condition);
		$get_thn_pg			 = array_column($get_thn_pg, "total");
		$total_thn_pg		 = array_sum($get_thn_pg);

		//Total Shift Sore Tahunan (id_shift = 2)
		$condition 			 = [];
		$condition[]		 = ['id_shift', 1, 'where'];
		$condition[]		 = ['YEAR(date)', date('Y'), 'where'];
		$condition[]		 = ['status', 1, 'where'];
		$get_thn_sre 		 = $this->M_core->get_tbl_ra('t_production', 'total', $condition);
		$get_thn_sre		 = array_column($get_thn_sre, "total");
		$total_thn_sre		 = array_sum($get_thn_sre);

		//Total Shift Pagi Bulanan (id_shift = 1)
		$condition 			 = [];
		$condition[]		 = ['id_shift', 1, 'where'];
		$condition[]		 = ['MONTH(date)', date('m'), 'where'];
		$condition[]		 = ['status', 1, 'where'];
		$get_bln_pg 		 = $this->M_core->get_tbl_ra('t_production', 'total', $condition);
		$get_bln_pg			 = array_column($get_bln_pg, "total");
		$total_bln_pg		 = array_sum($get_bln_pg);

		//Total Shift Sore Bulanan (id_shift = 2)
		$condition 			 = [];
		$condition[]		 = ['id_shift', 1, 'where'];
		$condition[]		 = ['MONTH(date)', date('m'), 'where'];
		$condition[]		 = ['status', 1, 'where'];
		$get_bln_sre 		 = $this->M_core->get_tbl_ra('t_production', 'total', $condition);
		$get_bln_sre		 = array_column($get_bln_sre, "total");
		$total_bln_sre		 = array_sum($get_bln_sre);


		//Total Tahunan Sekarang
		$condition 			 = [];
		$condition[]		 = ['YEAR(date)', date('Y'), 'where'];
		$condition[]		 = ['status', 1, 'where'];
		$get_all_now 		 = $this->M_core->get_tbl_ra('t_production', 'total', $condition);
		$get_all_now		 = array_column($get_all_now, "total");
		$total_all_now		 = array_sum($get_all_now);

		// Total Tahun Sebelumnya
		$condition 			 = [];
		$condition[]		 = ['YEAR(date)', date('Y', strtotime('-1 year')), 'where'];
		$condition[]		 = ['status', 1, 'where'];
		$get_all_ysterday 	 = $this->M_core->get_tbl_ra('t_production', 'total', $condition);
		$get_all_ysterday	 = array_column($get_all_ysterday, "total");
		$total_all_ysterday	 = array_sum($get_all_ysterday);

		//For Grafik
		$get_list_year 	     = $this->M_core->get_tbl_ra('v_production_list_year', 'jml_produksi_all', '');
		$get_list_year_ys 	 = $this->M_core->get_tbl_ra('v_production_list_year_ys', 'jml_produksi_all', '');

		$d['ttl_all_now_prsn'] 	 = ($total_all_now / 1000) * 100; 
		$d['ttl_all_ys_prsn']	 = ($total_all_ysterday / 1000) * 100; 

		//Total Mingguan All
		$d['total_mggu_all'] 	 = $total_mggu_pg + $total_mggu_sre;
		
		//Total Mingguan Kemaren
		$d['total_mggu_ys'] 	 = $this->M_core->get_mingguan_ys();

		$d['ttl_shift_pagi'] = number_format($total_shift_pg);
		$d['ttl_mggu_pagi']  = number_format($total_mggu_pg);
		$d['ttl_thn_pagi']   = number_format($total_thn_pg);
		$d['ttl_bln_pagi']   = number_format($total_bln_pg);
		$d['ttl_shift_sore'] = number_format($total_shift_sre);
		$d['ttl_mggu_sore']  = number_format($total_mggu_sre);
		$d['ttl_thn_sore']   = number_format($total_thn_sre);
		$d['ttl_bln_sore']   = number_format($total_bln_sre);
		$d['ttl_all_now']    = number_format($total_all_now);
		$d['ttl_all_ysterday']= number_format($total_all_ysterday);
		$d['list_year']	 	 = $get_list_year;
		$d['list_year_ys']	 = $get_list_year_ys;


		$this->load->view('layout', $d);
	}
}
