<?php 
header("Content-Type: application/json");

session_name("AuthCat-SSO");
session_start();

if (!array_key_exists("user", $_SESSION)) {
    die("{\"status\": \"fail\", \"message\": \"Not logged in.\"}");
}

// Get the meta data of all the files owned by this user, and build their file tree based on this.
$conn = new mysqli("localhost:3306", "cloud", "", "CloudCat");
$stmt = $conn->prepare("SELECT filePath, name, displayPath FROM files WHERE `owner` = ?");
$stmt->bind_param("i", $_SESSION["user"]["id"]);
$stmt->execute(); $set = $stmt->get_result();
$directories = [];

while ($r = $set->fetch_assoc()) {
    $path = explode("/", $r["displayPath"]);

    $currentParent = &$directories;
    for ($i = 1; $i < count($path); $i++) {
        if ($path[$i] === "") break;

        if (!array_key_exists($path[$i], $currentParent)) {
            $currentParent[$path[$i]] = [];
        }

        $currentParent = &$currentParent[$path[$i]];
    }

    if (!array_key_exists(".", $currentParent)) {
        $currentParent["."] = [];
    }

    array_push($currentParent["."], $r);
}

$out = [
    "status" => "success",
    "tree" => $directories
];

echo json_encode($out);
?>