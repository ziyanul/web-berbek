<?php

class Home_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('filler_model');

	}

//     public function get_plan_data()
// {
//     $tanggal_awal = date('Y-m-d', strtotime('-30 days'));

//     $this->db->select('p.*, v.varian, v.uuid as varian_uuid');
//     $this->db->from('t_planning p');
//     $this->db->join('varian v', 'v.uuid = p.varian', 'left');
//     $this->db->where('p.deleted_at IS NULL', null, false);
//     $this->db->where('p.tanggal >=', $tanggal_awal);
//     $this->db->order_by('p.tanggal', 'ASC');
//     $this->db->order_by('p.created_at', 'ASC');

//     return $this->db->get()->result();
// }


//     public function get_performa_data()
//     {
//         $planning_data = $this->get_plan_data();
//         $chart_data = array();

//         foreach ($planning_data as $planning) {
//             $performa = $this->filler_model->get_counter_by_t_planning_uuid($planning->uuid);

//             $formatted_date = date("d M Y", strtotime($planning->tanggal));

//             $total_target = 0;
//             $total_counters = 0;
//             $total_losses = 0;
//             $total_downtime = 0;
//             $total_performa = 0;
//             $total_quality_persen = 0;
//             $count = count($performa);
//             foreach ($performa as $row) {
//                 $total_target += $row->target;
//                 $total_counters += $row->counters;
//                 $total_losses += $row->total_losses;
//                 $total_downtime += $row->total_downtime;
//                 $total_performa += $row->performa;
//                 $total_quality_persen += $row->quality_persen;
//             }

//             $average_performa = $count > 0 ? $total_performa / $count : 0;
//             $average_quality_persen = $count > 0 ? $total_quality_persen / $count : 0;
//             $average_losses = $count > 0 ? $total_losses / $count : 0;
//             $average_downtime = $count > 0 ? $total_downtime / $count : 0;

//             $chart_data[] = array(
//                 'date' => $formatted_date,
//                 // 'varian' => $varian,
//                 'rata_performa' => $average_performa
//             );
//         }

//         return $chart_data;
//     }

//     public function get_downtime_data()
//     {
//         $planning_data = $this->get_plan_data();
//         $chart_data = array();

//         foreach ($planning_data as $planning) {
//             $performa = $this->filler_model->get_counter_by_t_planning_uuid($planning->uuid);

//             $formatted_date = date("d M Y", strtotime($planning->tanggal));


//             $total_downtime_persen = 0;           
            
//             $count = count($performa);
//             foreach ($performa as $row) {
               
//                 $total_downtime_persen += $row->downtime_persen;
//             }
//             $rata_downtime_persen = $count > 0 ? $total_downtime_persen / $count : 0;
//                 // $total_downtime = $count > 0 ? $total_downtime / $count : 0;

//             $chart_data[] = array(
//                 'date' => $formatted_date,
//                 // 'varian' => $varian,
//                 'total_downtime' => $rata_downtime_persen
//             );
//         }

//         return $chart_data;
//     }

//     public function get_quality_data()
//     {
//         $planning_data = $this->get_plan_data();
//         $chart_data = array();

//         foreach ($planning_data as $planning) {
//             $performa = $this->filler_model->get_counter_by_t_planning_uuid($planning->uuid);

//             $formatted_date = date("d M Y", strtotime($planning->tanggal));

//             if ($planning->varian == 1) {
//                 $varian = 'Okey';
//             } else if ($planning->varian == 2) {
//                 $varian = 'Champ Ayam';
//             } else if ($planning->varian == 3) {
//                 $varian = 'Champ Sapi';
//             } else if ($planning->varian == 4) {
//                 $varian = 'Champ Otak-Otak';
//             }


//             $total_counters = 0;
//             $total_quality_persen = 0;           
            
//             $count = count($performa);
//             foreach ($performa as $row) {
//                $total_counters += $row->counters;
               
//                $total_quality_persen += $row->quality;
//            }
//            $rata_quality_persen = ($total_counters > 0) ? ($total_quality_persen / $total_counters * 100) : 0;

//                 // $total_downtime = $count > 0 ? $total_downtime / $count : 0;

//            $chart_data[] = array(
//             'date' => $formatted_date,
//             // 'varian' => $varian,
//             'total_quality' => $rata_quality_persen
//         );
//        }

//        return $chart_data;
//    }

}