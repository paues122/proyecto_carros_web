<?php

require_once __DIR__ . "/sistemam.php";

class Perfil extends Sistema {


    public function readOne($id_usuario) {
        $this->connect();
       
        $sql = "SELECT * FROM usuarios WHERE id_usuario = :id_usuario";
        $stmt = $this->_DB->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

 
    public function update($id_usuario, $data, $files) {
        $this->connect();

        $ine_actual = isset($data['ine_actual']) ? $data['ine_actual'] : '';
        $ruta_ine = $this->uploadFile($files['ine'], $id_usuario, 'ine');
      
        if ($ruta_ine === null) {
            $ruta_ine = $ine_actual;
        }

 
        $domicilio_actual = isset($data['domicilio_actual']) ? $data['domicilio_actual'] : '';
        $ruta_domicilio = $this->uploadFile($files['comprobante_domicilio'], $id_usuario, 'domicilio');
        if ($ruta_domicilio === null) {
            $ruta_domicilio = $domicilio_actual;
        }

        $sql = "UPDATE usuarios SET 
                    nombre_completo = :nombre_completo,
                    edad = :edad,
                    ingresos_mensuales = :ingresos_mensuales,
                    buro_credito = :buro_credito,
                    vehiculo_interes = :vehiculo_interes,
                    ine_documento = :ine_documento,
                    domicilio_documento = :domicilio_documento
                WHERE id_usuario = :id_usuario";
        
        $stmt = $this->_DB->prepare($sql);
     
        $edad = !empty($data['age']) ? $data['age'] : null;
        $ingresos = !empty($data['monthlyIncome']) ? $data['monthlyIncome'] : null;

        $stmt->bindParam(':nombre_completo', $data['name'], PDO::PARAM_STR);
        $stmt->bindParam(':edad', $edad, PDO::PARAM_INT);
        $stmt->bindParam(':ingresos_mensuales', $ingresos);
        $stmt->bindParam(':buro_credito', $data['creditBureau'], PDO::PARAM_STR);
        $stmt->bindParam(':vehiculo_interes', $data['vehicleInterest'], PDO::PARAM_STR);
        $stmt->bindParam(':ine_documento', $ruta_ine, PDO::PARAM_STR);
        $stmt->bindParam(':domicilio_documento', $ruta_domicilio, PDO::PARAM_STR);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->rowCount();
    }

    private function uploadFile($file, $id_usuario, $prefix) {
       
        if (isset($file['error']) && $file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $safe_prefix = preg_replace("/[^a-zA-Z0-9_]/", "", $prefix);
       
            $fileName = $safe_prefix . '_' . $id_usuario . '_' . time() . '.' . $extension;
            $uploadPath = $uploadDir . $fileName;

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                return $fileName; 
            }
        }
        return null; 
}


}
