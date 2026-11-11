<?php

require_once "db.php";

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    case 'GET':

        $sql = $pdo->query("SELECT * FROM assignments");

        echo json_encode($sql->fetchAll(PDO::FETCH_ASSOC));

        break;


    case 'POST':

        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['title'])) {
            http_response_code(400);
            echo json_encode(["message" => "Title is required"]);
            break;
        }

        $sql = $pdo->prepare(
            "INSERT INTO assignments (title) VALUES (?)"
        );

        $sql->execute([$data['title']]);

        echo json_encode([
            "message" => "Assignment added successfully"
        ]);

        break;


    case 'PUT':

        $data = json_decode(file_get_contents("php://input"), true);

        if (
            empty($data['id']) ||
            empty($data['title'])
        ) {
            http_response_code(400);
            echo json_encode(["message" => "ID and title are required"]);
            break;
        }

        $sql = $pdo->prepare(
            "UPDATE assignments SET title = ? WHERE id = ?"
        );

        $sql->execute([$data['title'], $data['id']]);

        echo json_encode([
            "message" => "Assignment updated successfully"
        ]);

        break;


    case 'DELETE':

        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['id'])) {
            http_response_code(400);
            echo json_encode(["message" => "ID is required"]);
            break;
        }

        $sql = $pdo->prepare(
            "DELETE FROM assignments WHERE id = ?"
        );

        $sql->execute([$data['id']]);

        echo json_encode([
            "message" => "Assignment deleted successfully"
        ]);

        break;


    default:

        http_response_code(405);

        echo json_encode([
            "message" => "Method not allowed"
        ]);
}
