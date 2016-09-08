<?php
/*
Plugin Name: People With Gifts
Plugin URI: http://#
Description: This plugin gives people gifts.
Version: 1.0
Author: Rychek Dmitriy
Author URI: http://#
*/

/*  Copyright 2015  Rychek Dmitriy  (email: dimarychek@gmail.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//test
//test_new

header("Content-Type: text/html; charset=utf-8");
require_once('model/database.php');
require_once('view/view.php');
require_once('admin/adm_view.php');

class people_with_gifts extends WP_Widget
{

    // Добавления информации о виджете / The information of the widget
    public function __construct()
    {
        parent::__construct
        (
            'people_with_gifts', // ID виджета / ID widget
            'People With Gifts', // Название / Name
            array( 'description' => __( 'This plugin gives people gifts.', 'text_domain' ), ) // Описание / Description
        );
    }

    // Сохранение массива настроек / Saving array configuration
    public function update( $new_instance, $old_instance )
    {
        $instance = array(); // Инциализация массива настроек / Initializing the array settings
        $instance['title'] = strip_tags( $new_instance['title'] ); // Добавление настройки для заголовка / Adding a setup for the header
        return $instance;
    }

    // Добавление настроек в форму в админке / Adding settings in the admin form
    public function form( $instance )
    {
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Заголовок</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
        </p>
    <?php
    }

    // Вывод информации на сайт / Displays information on the site
    /**
     * @param $args
     * @param $instance
     */
    public function widget( $args, $instance )
    {
        ?>
        <div class="mywidget">
            <?php echo $instance[ 'title' ]; ?>
        </div>
        <?php

        // Вызов функции выборки из бд / Calling a function of the sample database
        $data = new DatBas;
        global $dab;
        $dab = $data->db();

        // Вызов функции шаблона формы / Calling a function template form
        $view1 = new View1;
        $views = $view1->views();
        print_r ($views);
    }
}

///////////AJAX///////////
add_action( 'wp_enqueue_scripts', 'jQuery' );

wp_localize_script( 'jquery', 'myajax',
    array(
        'ajaxurl'   => admin_url( 'admin-ajax.php' )
    ));

add_action( 'wp_ajax_nopriv_myajax_submit', 'myajax_submit' );
add_action( 'wp_ajax_myajax_submit', 'myajax_submit' );

function myajax_submit() {

// Принимаем данные с формы
    global $wpdb;
    global $us_ip;
    global $mail;

    $user_ip = $_SERVER["REMOTE_ADDR"];
    $email = $_POST['email'];
    $whatever = $_GET[''];

    // Выборка из бд
    $user_ip = $_SERVER["REMOTE_ADDR"];
    $insert = $wpdb->get_results("SELECT * FROM `pbru_people`' WHERE `ip` = '" . $user_ip . "'", ARRAY_A);
    for($i = 0; $i < count($insert); $i++) {
        $ip .= $insert[$i]['ip'];
        $em[] = $insert[$i]['email'];
        $data .= $insert[$i]['date'];
    }
    for($i = 0; $i < count($em); $i++) {
    }

    // Проверяем наличие в базе и записываем
    ?>
    <script>
        $(document).ready(function(){
            $('.mail').submit(function(){
                <?php
                 $ins = $wpdb->insert('pbru_people' , array('date' => current_time('mysql'), 'ip' => $user_ip, 'email' => $email));
                 ?>
            });
        });
    </script>
    <?php
    exit;
}
///////////END-AJAX///////////

// Регистрация виджета / Registration widget
add_action( 'widgets_init', function()
{
    register_widget( 'people_with_gifts' );
});

// Добавление страницы настроек / Adding settings page
add_action('admin_menu', 'CreateMyPluginMenu');

function CreateMyPluginMenu()
{
    if (function_exists('add_options_page'))
    {
        add_options_page('Настройки плагина People With Gifts', 'People With Gifts', 'manage_options', 'people_with_gifts', 'MyPluginPageOptions');
    }
}

function MyPluginPageOptions()
{
    // Вызов функции выборки из бд / Calling a function of the sample database
    $data = new DatBas;
    global $dab;
    $dab = $data->db();
    global $status;
    global $us_ip;
    global $email;
    global $date;
    global $all;
    global $alld;
    global $wpdb;
    global $user_ip;
    global $count_req;

    echo "<h2>Настройки плагина 'People With Gifts'.</h2>";

// Вкладки в админке
    function ilc_admin_tabs( $current = 'ip' )
    {
        global $od;
        global $id;

        $tabs = array( 'ip' => 'IP', 'requests' => 'Заявки', 'Approved' => 'Одобренные' );
        echo '<div id="icon-themes"><br></div>';
        echo '<h2>';
        foreach( $tabs as $tab => $name ){
            $class = ( $tab == $current ) ? ' nav-tab-active' : '';
            echo "<a class='nav-tab$class' href='?page=people_with_gifts&tab=$tab&pages=1'>$name</a>";

        }
        echo '</h2>';
    }

    function ilc_settings_page()
    {
        global $pagenow;
        global $adm_v;

        $settings = get_option( "ilc_theme_settings" );

        if ( isset ( $_GET['tab'] ) ) {
            ilc_admin_tabs($_GET['tab']);
        }
        else {
            ilc_admin_tabs('ip');
        }
    }

    ilc_settings_page();

    if ( isset ( $_GET['tab'] ) )
        $tab = $_GET['tab'];
    else $tab = 'ip';

    switch ( $tab ){
        case 'ip' :
            // Вызов функции шаблона формы (админ) / Calling a function template form (admin)
            $adm_v = new AdmView;
            echo $adm_v->admview();
            break;

        case 'requests' :
            if(empty($all))
            {
                echo "<strong>У Вас нет заявок на подарок.</strong>";
            }
            else
            {
                echo "<table class='table_p'><tr>";
                echo "<th>IP</th><th>E-mail</th><th>Дата</th><th>Статус</th><th>Действие</th>";
                for ($i = 0; $i < count($all); $i++) {
                    $id = $all[$i]['id'];
                    echo "<tr><td>" . $all[$i]['ip'] . "</td><td>" . $all[$i]['email'] . "</td><td>" . $all[$i]['date'] . "</td><td>" . $all[$i]['status'] . "</td><td>
                <form method='GET' action='http://practicalbinary.ru/wp-admin/options-general.php'>
                <input type='hidden' name='tab' value='requests'>
                <input type='hidden' name='page' value='people_with_gifts'>
                <input type='hidden' name='id' value='$id'>
                <input type='hidden' name='stat' value='Одобренно'>
                <button class='odob' type='submit'>Одобрить</button>
                </form>
                </td></tr>";
                }
                echo "</tr></table>";

                $res = $_GET['stat'];
                $resid = $_GET['id'];

                if ($res == 'Одобренно') {
                    $upd = $wpdb->update('pbru_people', array('date' => current_time('mysql'), 'status' => $res), array('id' => $resid));
                    $u = $wpdb->update('pbru_ip', array('date' => current_time('mysql'), 'mark' => '1'), array('mark' => '100'));
                    header("Location: http://practicalbinary.ru/wp-admin/options-general.php?tab=requests&page=people_with_gifts");
                    exit();
                }
            }
            break;

        case 'Approved' :
            if(empty($alld))
            {
                echo "<strong>У Вас нет одобренных заявок.</strong>";
            }
            else
            {
                echo "<table class='table_p'><tr>";
                echo "<th>IP</th><th>E-mail</th><th>Дата</th><th>Статус</th>";
                for ($i = 0; $i < count($alld); $i++) {
                    echo "<tr><td>" . $alld[$i]['ip'] . "</td><td>" . $alld[$i]['email'] . "</td><td>" . $alld[$i]['date'] . "</td><td>" . $alld[$i]['status'] . "</td></tr>";
                }
                echo "</tr></table>";
            }
            break;
    }
}

// Регистрация стилей / Register styles
function register_style()
{
    wp_register_style('general', plugins_url('css/general.css', __FILE__));
    wp_enqueue_style( 'general' );
}
add_action('wp_enqueue_scripts', 'register_style');

// Регистрация стилей / Register styles
function mystylesheet()
{
    wp_register_style('general', plugins_url('css/general.css', __FILE__));
    wp_enqueue_style( 'general' );
}
add_action('admin_init', 'mystylesheet');

// Регистрация скриптов / Register scripts
function wptuts_scripts_basic()
{
    wp_register_script( 'people', plugins_url( 'js/people.js', __FILE__ ) );
    wp_enqueue_script( 'people' );
}
add_action( 'wp_enqueue_scripts', 'wptuts_scripts_basic' );

// Регистрация скриптов / Register scripts
function my_scripts_method()
{
    wp_register_script( 'people', plugins_url( 'js/people.js', __FILE__ ) );
    wp_enqueue_script( 'people' );
}
add_action( 'admin_init', 'my_scripts_method' );

/////////////////bar-menu////////////
function add_mycms_admin_bar_link() {
    global $wp_admin_bar;
    global $wpdb;

    $get = $wpdb->get_results("SELECT * FROM `pbru_people` WHERE `status` = 'На рассмотрение'", ARRAY_A);
    $count_req = count($get);

    if ( !is_super_admin() || !is_admin_bar_showing() )
        return;
    $wp_admin_bar->add_menu( array(
        'id' => 'people_with_gifts', // Could be anything make sure its unique
        'title' => __( '<div class="count_req">Gifts <span class="cr">'.$count_req.'</span></div>'), //This will appear on the Menu
        'href' => __('http://practicalbinary.ru/wp-admin/options-general.php?page=people_with_gifts&tab=requests'),
    ));
}
add_action('admin_bar_menu', 'add_mycms_admin_bar_link',999);