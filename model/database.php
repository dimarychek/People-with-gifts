<?php
class DatBas
{
    public function db()
    {
        global $wpdb;
        global $user_ip;
        global $ip;
        global $bal;
        global $status;
        global $statusr;
        global $all;
        global $alld;
        global $count_req;

        $table_name = $wpdb->prefix . "ip";

        $sql = "CREATE TABLE " . $table_name . " (
                   id mediumint(9) NOT NULL AUTO_INCREMENT,
                   ip VARCHAR(55) NOT NULL,
                   mark VARCHAR(55) NOT NULL,
                   date DATE,
                   UNIQUE KEY id (id)
                   );";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $table_name1 = $wpdb->prefix . "people";

        $sql1 = "CREATE TABLE " . $table_name1 . " (
                   id mediumint(9) NOT NULL AUTO_INCREMENT,
                   ip VARCHAR(55) NOT NULL,
                   email VARCHAR(55) NOT NULL,
                   status VARCHAR(55) NOT NULL default 'На рассмотрение',
                   date DATE,
                   UNIQUE KEY id (id)
                   );";

        dbDelta($sql1);

        $user_ip = $_SERVER["REMOTE_ADDR"];
        $bal = 1;

        $od = $wpdb->get_results("SELECT * FROM `pbru_people` WHERE `status` = 'Одобренно'", ARRAY_A);
        for($i = 0; $i < count($od); $i++) {
            $alld[] = array(
                'ip' => $od[$i]['ip'],
                'email' => $od[$i]['email'],
                'date' => $od[$i]['date'],
                'status' => $od[$i]['status'],
            );
        }

        $str = $wpdb->get_results("SELECT * FROM `pbru_people` WHERE `ip` = '".$user_ip."' AND `status` = 'На рассмотрение'", ARRAY_A);
        for($i = 0; $i < count($str); $i++) {
            $statusr .= $str[$i]['status'];
        }

        $st = $wpdb->get_results("SELECT * FROM `pbru_people` WHERE `ip` = '".$user_ip."'", ARRAY_A);
        for($i = 0; $i < count($st); $i++) {
            $status .= $st[$i]['status'];
        }

        $get = $wpdb->get_results("SELECT * FROM `pbru_people` WHERE `status` = 'На рассмотрение'", ARRAY_A);
        $count_req = count($get);
        for($i = 0; $i < $count_req; $i++) {
            $all[] = array(
                'id' => $get[$i]['id'],
                'ip' => $get[$i]['ip'],
                'email' => $get[$i]['email'],
                'date' => $get[$i]['date'],
                'status' => $get[$i]['status'],
            );
        }

        $insert = $wpdb->get_results("SELECT * FROM `pbru_ip` WHERE `ip` = '" . $user_ip . "'", ARRAY_A);
        for($i = 0; $i < count($insert); $i++) {
            $ip .= $insert[$i]['ip'];
            $bal = $insert[$i]['mark'];
            $data = $insert[$i]['date'];
        }
        if ($ip != $user_ip)
        {
            $rows_ins = $wpdb->insert($table_name, array('date' => current_time('mysql'), 'ip' => $user_ip, 'mark' => $bal));
        }
        if ($ip == $user_ip)
        {
            if(strtotime($data) < strtotime(date("Y-m-d")))
            {
                if ($bal < 100)
                {
                    $bal = $bal + 1;
                    $rows_upd = $wpdb->update($table_name, array('date' => current_time('mysql'), 'mark' => $bal), array('ip' => $user_ip));
                }
            }
        }
    }
}