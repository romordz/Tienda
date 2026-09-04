<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$totalMXN = $data['totalMXN'] ?? 0;
$currency = $data['currency'] ?? 'USD';

$apiKey = getenv('EXCHANGE_RATE_API_KEY');

if ($totalMXN <= 0 || empty($currency)) {
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}

$endpoint = "https://v6.exchangerate-api.com/v6/{$apiKey}/pair/MXN/{$currency}";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if (isset($data['conversion_rate'])) {
    $totalConvertido = round($totalMXN * $data['conversion_rate'], 2);
    echo json_encode([
        'success' => true,
        'total' => $totalConvertido,
        'currency' => $currency
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error en la conversión']);
}
?>