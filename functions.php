<?php
include_once 'config.php';

function db($host, $user, $password, $db) {
    $conn = new mysqli($host, $user, $password, $db);

    $query = "set names utf8";
    $conn->query($query) or exit(mysqli_error());

    return $conn;
}

//Функция получения массива каталога
function get_cat() {

    $conn = db(HOST, USER, PASSWORD, DB);
    //запрос к базе данных

    $query = "SELECT * FROM categories";
    $result = $conn->query($query) or exit(mysqli_error());
    if (!$result) {
        return NULL;
    }
    $arr_cat = array();

    $num_rows = $result->num_rows;
    if ($num_rows != 0) {

        //В цикле формируем массив
        for ($i = 0; $i < $num_rows; $i++) {
            $row = $result->fetch_array();

            //Формируем массив, где ключами являются адишники на родительские категории
            if (empty($arr_cat[$row['parent_id']])) {
                $arr_cat[$row['parent_id']] = array();
            }
            $arr_cat[$row['parent_id']][] = $row;
        }
        //возвращаем массив
        return $arr_cat;
    }
}

//вывод каталогa с помощью рекурсии		
function view_cat($arr, $parent_id = 0, $root = TRUE) {

    //Условия выхода из рекурсии
    if (empty($arr[$parent_id])) {
        return;
    }
    
    echo '<ul>';
    for ($i = 0; $i < count($arr[$parent_id]); $i++) {
        echo '<li class="closed"><a href="#"><span class="file">'. $arr[$parent_id][$i]['name'] .'</span></a>';
        view_cat($arr, $arr[$parent_id][$i]['id']);
        echo '</li>';
    }
    echo '</ul>';
}
