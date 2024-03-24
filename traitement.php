<?php


// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    // You can also set this to a specific domain
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");



    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the raw POST data
        $inputJSON = file_get_contents('php://input');

        $data = json_decode($inputJSON, true);
        if (isset($data['dataType'])) {
            switch ($data['dataType']) {
                case 'filazana':
                    addNewFilazana($data);
                    break;
                case 'raharaha':
                    addNewMpitondraRaharaha($data);
                    break;
                default:
                    break;
            }
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $type = isset($_GET['type']) ? $_GET['type'] : null;

        if (!is_null($type)) {

            $data = file_get_contents('data/' . $type . '.json');
            $data = json_decode($data, true);

            // Set the Content-Type header to application/json
            header("Content-Type: application/json");

            // Encode the PHP array as a JSON string and echo it
            echo json_encode($data);
        } else {
            // Set the Content-Type header to application/json
            header("Content-Type: application/json");

            // Return an error message in JSON format
            echo json_encode(['error' => 'Type parameter is missing or null']);
        }

        // It's a good practice to exit after sending the response
        exit();
    }
}


function addNewMpitondraRaharaha($andraikitraArray)
{
    $mpitondraRaharaha  = file_get_contents('data/data.json');
    $mpitondraRaharaha = json_decode($mpitondraRaharaha, true);
    foreach ($andraikitraArray as $key => $arr) {

        $newData = [
            'id' => $arr['membreId'],
            'idRaharaha' => $arr['andraikitraId'],
            'prenom' => "",
            'andraikitra' => "",
            'date' =>  $arr['date']
        ];

        $mpitondraRaharaha[] = $newData;
    }

    header("Content-Type: application/json");

    try {
        $appendedJsonData = json_encode($mpitondraRaharaha, JSON_PRETTY_PRINT);
        if (file_put_contents('data/data.json', $appendedJsonData)) {
            // Success
            echo json_encode(['status' => 'success', 'message' => 'Enregistrement effectué avec succès']);
        } else {
            // Failure
            echo json_encode(['status' => 'error', 'message' => 'Erreur enregistrement']);
        }
    } catch (\Throwable $th) {
        // Exception handling
        echo json_encode(['status' => 'error', 'message' => 'Erreur enregistrement: ' . $th->getMessage()]);
    }

    exit();
}

function addNewFilazana($filazanaArray)
{
    $filazanaFile = 'data/filazana.json';
    $filazanaRehetra  = file_get_contents($filazanaFile);
    $filazanaRehetra = json_decode($filazanaRehetra, true);
    $filazanaRehetra[] = $filazanaArray;

    header("Content-Type: application/json");

    try {
        $appendedJsonData = json_encode($filazanaRehetra, JSON_PRETTY_PRINT);
        if (file_put_contents($filazanaFile, $appendedJsonData)) {
            // Success
            echo json_encode(['status' => 'success', 'message' => 'Enregistrement effectué avec succès']);
        } else {
            // Failure
            echo json_encode(['status' => 'error', 'message' => 'Erreur enregistrement']);
        }
    } catch (\Throwable $th) {
        // Exception handling
        echo json_encode(['status' => 'error', 'message' => 'Erreur enregistrement: ' . $th->getMessage()]);
    }

    exit();
}
