<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_all_indexes extends CI_Migration
{
    private function addIndex($table, $name, $cols)
    {
        $q = $this->db->query("
            SELECT 1
            FROM INFORMATION_SCHEMA.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = ?
            AND INDEX_NAME = ?
        ", [$table, $name]);

        if (!$q->num_rows()) {
            $this->db->query("ALTER TABLE `$table` ADD INDEX `$name` ($cols)");
        }
    }

    private function dropIndex($table, $name)
    {
        $q = $this->db->query("
            SELECT 1
            FROM INFORMATION_SCHEMA.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = ?
            AND INDEX_NAME = ?
        ", [$table, $name]);

        if ($q->num_rows()) {
            $this->db->query("ALTER TABLE `$table` DROP INDEX `$name`");
        }
    }

    public function up()
    {
        /*
        |--------------------------------------------------------------------------
        | t_planning
        |--------------------------------------------------------------------------
        */
        $this->addIndex('t_planning', 'idx_tp_uuid', 'uuid');
        $this->addIndex('t_planning', 'idx_tp_tanggal', 'tanggal');
        $this->addIndex('t_planning', 'idx_tp_created', 'created_at');
        $this->addIndex('t_planning', 'idx_tp_deleted_created', 'deleted_at,created_at');
        $this->addIndex('t_planning', 'idx_tp_varian', 'varian');

        /*
        |--------------------------------------------------------------------------
        | t_speed
        |--------------------------------------------------------------------------
        */
        $this->addIndex('t_speed', 'idx_ts_uuid', 'uuid');
        $this->addIndex('t_speed', 'idx_ts_plan', 't_planning_uuid');
        $this->addIndex('t_speed', 'idx_ts_mesin', 'mesin_uuid');
        $this->addIndex('t_speed', 'idx_ts_plan_mesin', 't_planning_uuid,mesin_uuid');
        $this->addIndex('t_speed', 'idx_ts_plan_speed', 't_planning_uuid,speed');
        $this->addIndex('t_speed', 'idx_ts_mesin_speed', 'mesin_uuid,speed');

        /*
        |--------------------------------------------------------------------------
        | tbatch
        |--------------------------------------------------------------------------
        */
        $this->addIndex('tbatch', 'idx_tb_uuid', 'uuid');
        $this->addIndex('tbatch', 'idx_tb_plan', 't_planning_uuid');
        $this->addIndex('tbatch', 'idx_tb_batchke', 'batch_ke');
        $this->addIndex('tbatch', 'idx_tb_created', 'created_at');
        $this->addIndex('tbatch', 'idx_tb_plan_created', 't_planning_uuid,created_at');
        $this->addIndex('tbatch', 'idx_tb_plan_batch', 't_planning_uuid,batch_ke');

        /*
        |--------------------------------------------------------------------------
        | tcounter
        |--------------------------------------------------------------------------
        */
        $this->addIndex('tcounter', 'idx_tc_uuid', 'uuid');
        $this->addIndex('tcounter', 'idx_tc_batch', 'tbatch_uuid');
        $this->addIndex('tcounter', 'idx_tc_mesin', 'mesin_uuid');
        $this->addIndex('tcounter', 'idx_tc_batch_mesin', 'tbatch_uuid,mesin_uuid');

        /*
        |--------------------------------------------------------------------------
        | t_badpro
        |--------------------------------------------------------------------------
        */
        $this->addIndex('t_badpro', 'idx_badpro_uuid', 'uuid');
        $this->addIndex('t_badpro', 'idx_badpro_ref', 'ref_uuid');
        $this->addIndex('t_badpro', 'idx_badpro_ref_badpro', 'ref_uuid,badpro_uuid');

        /*
        |--------------------------------------------------------------------------
        | t_downtime
        |--------------------------------------------------------------------------
        */
        $this->addIndex('t_downtime', 'idx_td_uuid', 'uuid');
        $this->addIndex('t_downtime', 'idx_td_speed', 't_speed_uuid');
        $this->addIndex('t_downtime', 'idx_td_speed_created', 't_speed_uuid,created_at');

        /*
        |--------------------------------------------------------------------------
        | master_speed
        |--------------------------------------------------------------------------
        */
        $this->addIndex('master_speed', 'idx_ms_uuid', 'uuid');
        $this->addIndex('master_speed', 'idx_ms_varian', 'varian_uuid');
        $this->addIndex('master_speed', 'idx_ms_mesin', 'mesin_uuid');
        $this->addIndex('master_speed', 'idx_ms_varian_mesin', 'varian_uuid,mesin_uuid');

        /*
        |--------------------------------------------------------------------------
        | mesin
        |--------------------------------------------------------------------------
        */
        $this->addIndex('mesin', 'idx_mesin_uuid', 'uuid');
        $this->addIndex('mesin', 'idx_mesin_nama', 'nama_mesin');
        $this->addIndex('mesin', 'idx_mesin_area_nama', 'nama_area,nama_mesin');

        /*
        |--------------------------------------------------------------------------
        | maintenance
        |--------------------------------------------------------------------------
        */
        $this->addIndex('maintenance', 'idx_mt_uuid', 'uuid');
        $this->addIndex('maintenance', 'idx_mt_created', 'created_at');
        $this->addIndex('maintenance', 'idx_mt_mesin', 'mesin_uuid');

        /*
        |--------------------------------------------------------------------------
        | status_maintenance
        |--------------------------------------------------------------------------
        */
        $this->addIndex('status_maintenance', 'idx_sm_uuid', 'uuid');
        $this->addIndex('status_maintenance', 'idx_sm_maintenance', 'maintenance_uuid');
        $this->addIndex('status_maintenance', 'idx_sm_maintenance_created', 'maintenance_uuid,created_at');
        $this->addIndex('status_maintenance', 'idx_sm_status', 'status');

        /*
        |--------------------------------------------------------------------------
        | zanasi
        |--------------------------------------------------------------------------
        */
        $this->addIndex('zanasi', 'idx_zanasi_uuid', 'uuid');
        $this->addIndex('zanasi', 'idx_zanasi_varian', 'varian');
        $this->addIndex('zanasi', 'idx_zanasi_created', 'created_at');
        $this->addIndex('zanasi', 'idx_zanasi_user', 'user_uuid');

        /*
        |--------------------------------------------------------------------------
        | printing
        |--------------------------------------------------------------------------
        */
        $this->addIndex('printing', 'idx_printing_uuid', 'uuid');
        $this->addIndex('printing', 'idx_printing_zanasi', 'zanasi_uuid');
        $this->addIndex('printing', 'idx_printing_created', 'created_at');
        $this->addIndex('printing', 'idx_printing_user', 'user_uuid');
        $this->addIndex('printing', 'idx_printing_zanasi_created', 'zanasi_uuid,created_at');

        /*
        |--------------------------------------------------------------------------
        | varian
        |--------------------------------------------------------------------------
        */
        $this->addIndex('varian', 'idx_varian_uuid', 'uuid');
    }
    
    
        public function down()
    {
        /*
        |--------------------------------------------------------------------------
        | t_planning
        |--------------------------------------------------------------------------
        */
        $this->dropIndex('t_planning', 'idx_tp_uuid');
        $this->dropIndex('t_planning', 'idx_tp_tanggal');
        $this->dropIndex('t_planning', 'idx_tp_created');
        $this->dropIndex('t_planning', 'idx_tp_deleted_created');
        $this->dropIndex('t_planning', 'idx_tp_varian');

        /*
        |--------------------------------------------------------------------------
        | t_speed
        |--------------------------------------------------------------------------
        */
        $this->dropIndex('t_speed', 'idx_ts_uuid');
        $this->dropIndex('t_speed', 'idx_ts_plan');
        $this->dropIndex('t_speed', 'idx_ts_mesin');
        $this->dropIndex('t_speed', 'idx_ts_plan_mesin');
        $this->dropIndex('t_speed', 'idx_ts_plan_speed');
        $this->dropIndex('t_speed', 'idx_ts_mesin_speed');

        /*
        |--------------------------------------------------------------------------
        | tbatch
        |--------------------------------------------------------------------------
        */
        $this->dropIndex('tbatch', 'idx_tb_uuid');
        $this->dropIndex('tbatch', 'idx_tb_plan');
        $this->dropIndex('tbatch', 'idx_tb_batchke');
        $this->dropIndex('tbatch', 'idx_tb_created');
        $this->dropIndex('tbatch', 'idx_tb_plan_created');
        $this->dropIndex('tbatch', 'idx_tb_plan_batch');

        /*
        |--------------------------------------------------------------------------
        | tcounter
        |--------------------------------------------------------------------------
        */
        $this->dropIndex('tcounter', 'idx_tc_uuid');
        $this->dropIndex('tcounter', 'idx_tc_batch');
        $this->dropIndex('tcounter', 'idx_tc_mesin');
        $this->dropIndex('tcounter', 'idx_tc_batch_mesin');

        /*
        |--------------------------------------------------------------------------
        | t_badpro
        |--------------------------------------------------------------------------
        */
        $this->dropIndex('t_badpro', 'idx_badpro_uuid');
        $this->dropIndex('t_badpro', 'idx_badpro_ref');
        $this->dropIndex('t_badpro', 'idx_badpro_ref_badpro');

        /*
        |--------------------------------------------------------------------------
        | t_downtime
        |--------------------------------------------------------------------------
        */
        $this->dropIndex('t_downtime', 'idx_td_uuid');
        $this->dropIndex('t_downtime', 'idx_td_speed');
        $this->dropIndex('t_downtime', 'idx_td_speed_created');

        /*
        |--------------------------------------------------------------------------
        | master_speed
        |--------------------------------------------------------------------------
        */
        $this->dropIndex('master_speed', 'idx_ms_uuid');
        $this->dropIndex('master_speed', 'idx_ms_varian');
        $this->dropIndex('master_speed', 'idx_ms_mesin');
        $this->dropIndex('master_speed', 'idx_ms_varian_mesin');

        /*
        |--------------------------------------------------------------------------
        | mesin
        |--------------------------------------------------------------------------
        */
        $this->dropIndex('mesin', 'idx_mesin_uuid');
        $this->dropIndex('mesin', 'idx_mesin_nama');
        $this->dropIndex('mesin', 'idx_mesin_area_nama');

        /*
        |--------------------------------------------------------------------------
        | maintenance
        |--------------------------------------------------------------------------
        */
        $this->dropIndex('maintenance', 'idx_mt_uuid');
        $this->dropIndex('maintenance', 'idx_mt_created');
        $this->dropIndex('maintenance', 'idx_mt_mesin');

        /*
        |--------------------------------------------------------------------------
        | status_maintenance
        |--------------------------------------------------------------------------
        */
        $this->dropIndex('status_maintenance', 'idx_sm_uuid');
        $this->dropIndex('status_maintenance', 'idx_sm_maintenance');
        $this->dropIndex('status_maintenance', 'idx_sm_maintenance_created');
        $this->dropIndex('status_maintenance', 'idx_sm_status');

        /*
        |--------------------------------------------------------------------------
        | zanasi
        |--------------------------------------------------------------------------
        */
        $this->dropIndex('zanasi', 'idx_zanasi_uuid');
        $this->dropIndex('zanasi', 'idx_zanasi_varian');
        $this->dropIndex('zanasi', 'idx_zanasi_created');
        $this->dropIndex('zanasi', 'idx_zanasi_user');

        /*
        |--------------------------------------------------------------------------
        | printing
        |--------------------------------------------------------------------------
        */
        $this->dropIndex('printing', 'idx_printing_uuid');
        $this->dropIndex('printing', 'idx_printing_zanasi');
        $this->dropIndex('printing', 'idx_printing_created');
        $this->dropIndex('printing', 'idx_printing_user');
        $this->dropIndex('printing', 'idx_printing_zanasi_created');

        /*
        |--------------------------------------------------------------------------
        | varian
        |--------------------------------------------------------------------------
        */
        $this->dropIndex('varian', 'idx_varian_uuid');
    }
}