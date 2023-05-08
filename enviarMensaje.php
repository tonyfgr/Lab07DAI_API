<?php
if (!isset($_GET['codigo'])) {
    header('Location: index.php?mensaje=error');
    exit();
}

include 'model/conexion.php';
$codigo = $_GET['codigo'];

$sentencia = $bd->prepare("SELECT pro.promocion, pro.duracion , pro.id_usuarios, per.nombre_completo ,per.telefono , per.fecha_de_nacimiento 
  FROM promociones pro 
  INNER JOIN usuarios per ON per.id = pro.id_usuarios 
  WHERE pro.id = ?;");
$sentencia->execute([$codigo]);
$usuarios = $sentencia->fetch(PDO::FETCH_OBJ);

    $url = 'https://api.green-api.com/waInstance1101816943/SendMessage/40d6f69876c5402aa83d19aebca12b0b6100c1af74604008b1';
    $data = [
        "chatId" => "51".$usuarios->telefono."@c.us",
        "message" =>  'Estimado(a) *'.strtoupper($usuarios->nombre_completo).' * No se pierda *'.strtoupper($usuarios->promocion).'* valido solo *'.$usuarios->duracion.'*'
    ];
    $options = array(
        'http' => array(
            'method'  => 'POST',
            'content' => json_encode($data),
            'header' =>  "Content-Type: application/json\r\n" .
                "Accept: application/json\r\n"
        )
    );

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $response = json_decode($result);
   // header('Location: agregarPromocion.php?codigo='.$usuarios->id_usuarios);
?> 

