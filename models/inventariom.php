<?php
require_once __DIR__ . "/sistemam.php";

class Inventario extends Sistema {

    public function read() {
        $this->connect();
        $sql = "SELECT * FROM inventario ORDER BY id_vehiculo DESC"; 
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
        try {
           
            $this->_DB->beginTransaction();

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

            $this->_DB->commit();
           
            return $stmt->rowCount();
        } catch (Exception $e) {
            $this->_DB->rollback();
          
            return 0;
        }
    }

    public function update($data, $id_vehiculo) {
        $this->connect();
        try {
           
            $this->_DB->beginTransaction();

         
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

            $this->_DB->commit();
            return true; 
        } catch (Exception $e) {
            $this->_DB->rollback();
            return false;
        }
    }

    public function delete($id_vehiculo) {
        if (!is_numeric($id_vehiculo)) return 0;

        $this->connect();
        try {
            $this->_DB->beginTransaction();

            $sql = "DELETE FROM inventario WHERE id_vehiculo = :id_vehiculo";
            $stmt = $this->_DB->prepare($sql);
            $stmt->bindParam(':id_vehiculo', $id_vehiculo, PDO::PARAM_INT);
            $stmt->execute();

            $this->_DB->commit();
            return $stmt->rowCount();
        } catch (Exception $e) {
            $this->_DB->rollback();
            return 0;
        }
    }
}
?>