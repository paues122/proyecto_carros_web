<?php
header('Content-Type: application/json');
require_once(__DIR__ . "/../../models/inventariom.php");

$app = new Inventario();
$action = $_SERVER['REQUEST_METHOD'];
$data = [];
$id = isset($_GET['id']) ? $_GET['id'] : null;

function saveBase64Image($base64String) {
  
    if (preg_match('/^data:(image\/[a-zA-Z]+);base64,/', $base64String, $matches)) {
        $mime = $matches[1]; 
        $ext = explode('/', $mime)[1];
        $base64Data = substr($base64String, strpos($base64String, ',') + 1);
        $decoded = base64_decode($base64Data);
        if ($decoded === false) return false;
        $fileName = time() . "_" . bin2hex(random_bytes(6)) . "." . $ext;
        $ruta = __DIR__ . "/../../public/img/" . $fileName;
        file_put_contents($ruta, $decoded);
        return $fileName;
    }
    return false;
}

switch ($action) {
    case 'POST':
        
        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? $_SERVER["CONTENT_TYPE"] : '';
        if (strpos($contentType, 'application/json') !== false) {
            $raw = file_get_contents("php://input");
            $json = json_decode($raw, true);
            if (!$json) {
                http_response_code(400);
                echo json_encode(["error" => "JSON inválido"]);
                exit;
            }
            
            if (!empty($json['imagen_base64'])) {
                $fileName = saveBase64Image($json['imagen_base64']);
                if ($fileName === false) {
                    http_response_code(400);
                    echo json_encode(["error" => "Imagen base64 inválida"]);
                    exit;
                }
                $json['imagen'] = $fileName;
                unset($json['imagen_base64']);
            }
            $data = $json;
        } else {
           
            if (!empty($_FILES['imagen']['name']) && is_uploaded_file($_FILES['imagen']['tmp_name'])) {
                
                $original = basename($_FILES['imagen']['name']);
                $ext = pathinfo($original, PATHINFO_EXTENSION);
                $fileName = time() . "_" . bin2hex(random_bytes(6)) . "." . $ext;
                $ruta = __DIR__ . "/../../public/img/" . $fileName;
                move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta);
                $_POST['imagen'] = $fileName;
            }
            $data = $_POST;
        }

        if ($id) {
            $row = $app->update($data, $id);
            $data['mensaje'] = $row ? "Auto modificado correctamente" : "No se modificó el auto";
        } else {
            $row = $app->create($data);
            $data['mensaje'] = $row ? "Auto creado correctamente" : "No se creó el auto";
        }
        break;

    case 'DELETE':
        if ($id) {
            $row = $app->delete($id);
            $data['mensaje'] = $row ? "Auto eliminado correctamente" : "No se eliminó el auto";
        } else {
            $data['mensaje'] = "ID no proporcionado";
        }
        break;

    case 'GET':
    default:
        if ($id) {
            $data = $app->readOne($id);
        } else {
            $data = $app->read();
        }
        break;
}

echo json_encode($data, JSON_PRETTY_PRINT);
?>