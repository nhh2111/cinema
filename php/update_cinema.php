<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cinema'])) {
    session_start();
    $_SESSION['selected_cinema'] = $_POST['cinema'];
    echo json_encode(['status' => 'success', 'cinema' => $_POST['cinema']]);
    exit;
}
http_response_code(400);
echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
?>
