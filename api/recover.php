<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS');

if($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include 'config.php';
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$input = $_POST;
if(empty($input)) {
    $input = json_decode(file_get_contents('php://input'), true) ?? [];
}

$email = trim($input['email'] ?? '');

if(empty($email)) {
    echo json_encode(['status' => 'error', 'message' => 'Email obrigatório para recuperação']);
    exit;
}

function recoverPassword($pdo, $email) {
    // Verifica se o email existe
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$user) {
        return ['status' => 'error', 'message' => 'Email não encontrado'];
    }

    // Aqui você pode implementar a lógica de recuperação, como enviar um email com um link de redefinição
    // Por simplicidade, vamos apenas retornar uma mensagem de sucesso
    return ['status' => 'success', 'message' => 'Instruções de recuperação enviadas para o email'];
}