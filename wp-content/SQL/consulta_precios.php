<?php

    require_once('../../wp-config.php');
    global $wpdb;
    
    $id_producto = intval($_POST['id_producto']);
    $cantidad = intval($_POST['cantidad']);

    $sql = $wpdb -> prepare("SELECT escala, precio, vol_min FROM wpgb_escala_producto WHERE id_producto = $id_producto AND vol_min <= $cantidad ORDER BY vol_min DESC LIMIT 1;");

    $query = $wpdb -> get_results($sql);

    $result_query = [
        'escala' => ($query[0]->escala == '') ? 0 : $query[0]->escala,
        'vol_min' => ($query[0]->vol_min == '') ? 0 : $query[0]->vol_min,
        'precio' => ($query[0]->precio == '') ? 0 : number_format($query[0]->precio, 0, ',', '.'),
    ];
        
    echo json_encode($result_query);



?>
