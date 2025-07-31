<?php

$url = 'http://localhost:8000/api/v1/basket/confirm';
$data = [
    'items' => [
        ['product_id' => 1, 'quantity' => 2],
        ['product_id' => 3, 'quantity' => 1]
    ],
    'total_price' => 120,
    'discount_code' => 'SUMMER25',
    'name' => 'Ali Veli',
    'address' => 'Ankara, Turkey'
];

$jsonData = json_encode($data);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($jsonData)
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: " . $httpCode . "\n";
echo "Response: " . $response . "\n";
?> 