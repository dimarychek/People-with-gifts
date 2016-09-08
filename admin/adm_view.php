<?php
class AdmView
{
    public function admview()
    {
        global $wpdb;

        $per_page=30;
        $q = $wpdb->get_var("SELECT count(*) FROM `pbru_ip`");
        $count_pages=ceil($q/$per_page);
        $count_show_pages = 10;
        $active = $_GET['pages'];
        $left = $active - 1;
        $right = $count_pages - $active;
        if ($left < floor($count_show_pages / 2)) {
            $starts = 1;
        } else {
            $starts = $active - floor($count_show_pages / 2);
        }
        $end = $starts + $count_show_pages - 1;
        if ($end > $count_pages) {
            $starts -= ($end - $count_pages);
            echo $starts;
            $end = $count_pages;
            if ($starts < 1)
                $starts = 1;
        }

        if (isset($_GET['pages'])) $page=($_GET['pages']-1); else $page=0;
        $start=abs($page*$per_page);
        $q = $wpdb->get_results("SELECT * FROM `pbru_ip` LIMIT $start,$per_page", ARRAY_A);
        $ip = "<table class='table_p'><tr>";
        $ip .= "<th>IP</th><th>Балл</th><th>Дата</th>";
        for($i = 0; $i < count($q); $i++) {
            $ip .= "<tr><td>" . $q[$i]['ip'] . "</td><td>" . $q[$i]['mark'] . "</td><td>" . $q[$i]['date'] . "</td></tr>";
        }
        $ip .= "</tr></table>";

            $ip .= '<div class="allpag">';
            if($page > 4){
                $test = $page - 3;
                //$ip .= '...';
            }else{
                $test = 1;
            }

        for($i=$test;$i<=$end;$i++) {
            if ($i-1 == $page) {
                $ip .= "<span class='curpag'>".$i."</span>";
            } else {
                $ip .= '<a class="pag" href="'.$_SERVER['PHP_SELF'].'?page=people_with_gifts&tab=ip&pages='.$i.'">'.$i."</a> ";
            }
        }
//        if($page < ($end - 4)) {
//            $ip .= '...';
//        }
        $ip .= '</div>';
        return $ip;
    }
}
