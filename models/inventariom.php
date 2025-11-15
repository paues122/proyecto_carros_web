<?php
require_once __DIR__ . "/sistemam.php";

class Inventario extends Sistema {

    public function read() {
        $this->connect();
        $sql = "SELECT * FROM inventario ORDER BY marca, nombre";
        $stmt = $this->_DB->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readOne($id_vehiculo) {
        if (!is_numeric($id_vehiculo)) return false;
        $this->connect();
        $sql = "SELECT * FROM inventario WHERE id_vehiculo = :id_vehiculo";
        $stmt = $this->_DB->prepare($sql);
        $stmt->bindParam(':id_vehiculo', $id_vehiculo, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $this->connect();
        $sql = "INSERT INTO inventario 
                (nombre, modelo, marca, anio, precio, mensualidad, enganche, imagen) 
                VALUES 
                (:nombre, :modelo, :marca, :anio, :precio, :mensualidad, :enganche, :imagen)";
        $stmt = $this->_DB->prepare($sql);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':modelo', $data['modelo']);
        $stmt->bindParam(':marca', $data['marca']);
        $stmt->bindParam(':anio', $data['anio'], PDO::PARAM_INT);
        $stmt->bindParam(':precio', $data['precio']);
        $stmt->bindParam(':mensualidad', $data['mensualidad']);
        $stmt->bindParam(':enganche', $data['enganche']);
        $stmt->bindParam(':imagen', $data['imagen']);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function update($data, $id_vehiculo) {
        $this->connect();
        $sql = "UPDATE inventario SET 
                    nombre = :nombre,
                    modelo = :modelo,
                    marca = :marca,
                    anio = :anio,
                    precio = :precio,
                    mensualidad = :mensualidad,
                    enganche = :enganche,
                    imagen = :imagen
                WHERE id_vehiculo = :id_vehiculo";

        $stmt = $this->_DB->prepare($sql);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':modelo', $data['modelo']);
        $stmt->bindParam(':marca', $data['marca']);
        $stmt->bindParam(':anio', $data['anio'], PDO::PARAM_INT);
        $stmt->bindParam(':precio', $data['precio']);
        $stmt->bindParam(':mensualidad', $data['mensualidad']);
        $stmt->bindParam(':enganche', $data['enganche']);
        $stmt->bindParam(':imagen', $data['imagen']);
        $stmt->bindParam(':id_vehiculo', $id_vehiculo, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function delete($id_vehiculo) {
        if (!is_numeric($id_vehiculo)) {
            return 0;
        }
       
        $row = $this->readOne($id_vehiculo);
        if ($row && !empty($row['imagen'])) {
            $imgPath = __DIR__ . "/../public/img/" . $row['imagen'];
            if (file_exists($imgPath)) unlink($imgPath);
        }

        $this->connect();
        $sql = "DELETE FROM inventario WHERE id_vehiculo = :id_vehiculo";
        $stmt = $this->_DB->prepare($sql);
        $stmt->bindParam(':id_vehiculo', $id_vehiculo, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
?>