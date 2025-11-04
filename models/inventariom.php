<?php

require_once __DIR__ . "/sistemam.php";

class Inventario extends Sistema{

    public function read() {
        $this->connect();
        $sql = "SELECT * FROM inventario ORDER BY marca, nombre";
        $stmt = $this->_DB->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data){
        $this->connect();
        $sql = "INSERT INTO inventario(nombre, modelo, marca, anio, precio, mensualidad, enganche) 
                VALUES (:nombre, :modelo, :marca, :anio, :precio, :mensualidad, :enganche)";
        
        $stmt = $this->_DB->prepare($sql);
        $stmt->bindParam(':nombre', $data['nombre'], PDO::PARAM_STR);
        $stmt->bindParam(':modelo', $data['modelo'], PDO::PARAM_STR);
        $stmt->bindParam(':marca', $data['marca'], PDO::PARAM_STR);
        $stmt->bindParam(':anio', $data['anio'], PDO::PARAM_INT);
        $stmt->bindParam(':precio', $data['precio']);
        $stmt->bindParam(':mensualidad', $data['mensualidad']);
        $stmt->bindParam(':enganche', $data['enganche']);
        
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function delete($id_vehiculo){
        if (!is_numeric($id_vehiculo)) {
            return 0;
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