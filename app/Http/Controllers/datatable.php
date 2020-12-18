<?php 

$app->post("/categorias/listar", function() use($app, $db){
    $method = $app->request()->getBody();
    $data = json_decode($method, true);
    //echo json_encode($data);

    
    // Condiciones
    $conditions = [];
    $params= [];

    if(!empty($data['nombre'])) {
      $conditions[] = 'C.nombre LIKE ?';
      $params[] = '%'.$data['nombre'].'%';
    }

    if(!empty($data['status'])) {
      $conditions[] = 'C.status = ?';
      $params[] = $data['status'];
    }

    // Si hay condiciones
    $where = '';
    if (!empty($conditions)) {
      $where = ' WHERE' . implode(' AND ', $conditions);
    }

    // Orden
    // Arreglo asociativo donde:
    // - Las claves son los name de las columnas del DataTable JS
    // - Los valores son los nombre de las columnas de la Tabla SQL
    $orderColumns = [
      'Nombre' => 'nombre',
      'Estado' => 'status',
    ];
    $orders = [];
    if (is_array($data['order'])) {
        foreach($data['order'] as $info) {

            $columnName = $data['columns'][$info['column']]['name'];
            $dir = $info['dir'] == 'asc' ? 'ASC' : 'DESC';
            if (isset($orderColumns [$columnName])) {

               $orders[] = $orderColumns [$columnName] . ' ' . $dir;
            }
        }
    }
    // Si no se indico el orden, ponemos un orden por defecto
    if (empty($orders)) {
      $orders[] = "nombre ASC";
    }
    $order = "ORDER BY " . implode(', ', $orders);

    // Paginado
    $offset = (int) $data['start'];
    $length = (int) $data['length'];
    // Si no se indico la cantidad por pagina
    if ($length < 1) {
        $length = 10;
    }
    $limit = 'LIMIT ' . $length . ' OFFSET ' . $offset;

    try {
        // Obtenemos el total de registros en la tabla
        $stmt = $db->prepare(
          "SELECT COUNT(1) AS total
           FROM categorias AS C"
        );
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $all = $row['total'];

        // Obtenemos el total de registros que cumplen el $where
        $stmt = $db->prepare(
          "SELECT COUNT(1) AS total
           FROM categorias AS C
           $where"
        );
        $stmt->execute($params);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $total = $row['total'];

        // Obtenemos los registros correspondientes al paginado y ordenados
        $stmt = $db->prepare(
          "SELECT nombre, status
           FROM categorias AS C
           $where
           $order
           $limit"
        );
        $stmt->execute($params);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $result = array(
            'data' => $rows, // registros de la pagina
            'recordsTotal' => $all, // total registros en la tabla
            'recordsFiltered' => $total, // total registros filtrados
            'draw' => $data['draw'], // Versión
        );
        echo json_encode($result);
    } catch (\PDOException $e) {
        echo json_encode($e->getMessage());
    }
});
?>