<?php

$base = rtrim(getenv('API_URL') ?: 'http://127.0.0.1:8000/api/v1', '/');
$post = function (string $path, array $payload) use ($base): array {
    $ch = curl_init($base.$path);
    curl_setopt_array($ch, [CURLOPT_POST => true, CURLOPT_RETURNTRANSFER => true, CURLOPT_HTTPHEADER => ['Content-Type: application/json'], CURLOPT_POSTFIELDS => json_encode($payload)]);
    $body = curl_exec($ch); $status = curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
    return [$status, json_decode($body ?: '{}', true) ?: []];
};

for ($i = 1; $i <= 10; $i++) {
    $post('/members', ['full_name' => "Socio Demo {$i}", 'document_number' => 'DEMO'.str_pad((string) $i, 6, '0', STR_PAD_LEFT)]);
}

$categories = ['11111111-1111-4111-8111-111111111111', '22222222-2222-4222-8222-222222222222', '33333333-3333-4333-8333-333333333333', '44444444-4444-4444-8444-444444444444', '55555555-5555-4555-8555-555555555555'];
for ($i = 1; $i <= 40; $i++) {
    $post('/books', ['title' => "Libro Demo {$i}", 'author' => 'Autor Demo', 'isbn' => str_pad((string) (9780000000000 + $i), 13, '0', STR_PAD_LEFT), 'category_id' => $categories[$i % count($categories)], 'total_copies' => 3]);
}
echo "Datos de demostración enviados a {$base}\n";
