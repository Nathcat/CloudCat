<?php 
header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
header("Access-Control-Allow-Credentials: true");
header("Accept: application/json");
header("Content-Type: application/json");

session_name("AuthCat-SSO");
session_start();

if (!array_key_exists("user", $_SESSION)) {
    die("{\"status\": \"fail\", \"message\": \"Not logged in.\"}");
}

$_POST = json_decode(file_get_contents("php://input"));

if (!array_key_exists("filePath", $_POST)) {
    die("{\"status\": \"fail\", \"message\": \"Invalid request\"}");
}

$conn = new mysqli("localhost:3306", "cloud", "", "CloudCat");
$stmt = $conn->prepare("SELECT `owner`, name, displayPath FROM files WHERE filePath = ?");
$stmt->bind_param("i", $_POST["filePath"]);
$stmt->execute(); $set = $stmt->get_result();

if ($r = $set->fetch_assoc()) {
    if ($r["owner"] == $_SESSION["user"]["id"]) {
        $out = [
            "status" => "success",
            "file" => json_encode($r)
        ];

        echo json_encode($out);
    }
}
else {
    $out = [
        "status" => "fail",
        "message" => "Cannot complete request."
    ];

    die(json_encode($out));
}

?>