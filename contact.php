<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

function respond(int $status, bool $success, string $message): never
{
    http_response_code($status);
    echo json_encode(['success' => $success, 'message' => $message], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(405, false, 'Méthode non autorisée.');
}

$startedAt = filter_input(INPUT_POST, 'started_at', FILTER_VALIDATE_INT);
if (!$startedAt || (time() - $startedAt) < 4) {
    respond(400, false, 'Votre message n’a pas pu être validé. Merci de réessayer.');
}

$honeypot = trim((string)($_POST['website'] ?? ''));
if ($honeypot !== '') {
    respond(400, false, 'Votre message n’a pas pu être validé.');
}

function field(string $key, int $max): string
{
    $value = trim((string)($_POST[$key] ?? ''));
    $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value) ?? '';
    return mb_substr($value, 0, $max, 'UTF-8');
}

$name = field('name', 120);
$email = field('email', 160);
$phone = field('phone', 40);
$product = field('product', 80);
$message = field('message', 2000);
$consent = isset($_POST['consent']);
$allowedProducts = ['', 'Viaris Combi', 'Viaris Uni', 'Viaris ISI'];

if ($name === '' || $email === '' || $message === '' || !$consent) {
    respond(422, false, 'Merci de compléter les champs obligatoires.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(422, false, 'Merci de saisir une adresse email valide.');
}

if (mb_strlen($message, 'UTF-8') < 20) {
    respond(422, false, 'Merci d’ajouter un message plus détaillé.');
}

if (!in_array($product, $allowedProducts, true)) {
    respond(422, false, 'Le produit sélectionné n’est pas valide.');
}

$to = 'contact@evsolutions.ma';
$subject = 'Nouvelle demande EVSolutions';
$plain = "Nouvelle demande EVSolutions\n\n"
    . "Nom: {$name}\nEmail: {$email}\nTéléphone: {$phone}\nProduit: " . ($product ?: 'À déterminer') . "\n\nMessage:\n{$message}\n";
$html = '<!doctype html><html><head><meta charset="utf-8"></head><body>'
    . '<h1>Nouvelle demande EVSolutions</h1>'
    . '<p><strong>Nom:</strong> ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '</p>'
    . '<p><strong>Email:</strong> ' . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . '</p>'
    . '<p><strong>Téléphone:</strong> ' . htmlspecialchars($phone, ENT_QUOTES, 'UTF-8') . '</p>'
    . '<p><strong>Produit:</strong> ' . htmlspecialchars($product ?: 'À déterminer', ENT_QUOTES, 'UTF-8') . '</p>'
    . '<p><strong>Message:</strong><br>' . nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')) . '</p>'
    . '</body></html>';

$boundary = bin2hex(random_bytes(16));
$headers = [
    'MIME-Version: 1.0',
    'Content-Type: multipart/alternative; boundary="' . $boundary . '"',
    'From: EVSolutions <no-reply@evsolutions.ma>',
    'Reply-To: ' . $name . ' <' . $email . '>',
];
$body = "--{$boundary}\r\nContent-Type: text/plain; charset=UTF-8\r\nContent-Transfer-Encoding: 8bit\r\n\r\n{$plain}\r\n"
    . "--{$boundary}\r\nContent-Type: text/html; charset=UTF-8\r\nContent-Transfer-Encoding: 8bit\r\n\r\n{$html}\r\n"
    . "--{$boundary}--";

if (!mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, implode("\r\n", $headers))) {
    respond(500, false, 'Le serveur mail n’est pas disponible. Merci de nous contacter directement.');
}

respond(200, true, 'Merci, votre demande a bien été envoyée.');
