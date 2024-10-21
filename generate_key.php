<?php
// Informations de l'application KeyAuth
$app_name = "OnlySkills"; // Nom de l'application
$owner_id = "FJ313jeuBG"; // Identifiant du propriétaire
$secret = "f2f0d446284ca72d208950072e64f01eb5d266288babe18063b1320b9b06c4e9"; // Clé secrète
$url = "https://keyauth.win/api/1.3/"; // URL de l'API

// Vérifie si le nom est fourni et si l'utilisateur vient de Linkvertise
if (isset($_GET['name']) && isset($_GET['from_linkvertise'])) {
    $client_name = htmlspecialchars($_GET['name']); // Nom du client
} else {
    die('Accès non autorisé. Vous devez passer par Linkvertise.');
}

// Données à envoyer pour créer la clé
$data = [
    'type' => 'create',
    'name' => $client_name, // Nom du client pour la clé
    'duration' => 86400, // Durée de validité de la clé en secondes
    'hash' => md5(uniqid()), // Crée un hash unique
];

// Envoi de la requête POST à l'API KeyAuth
$options = [
    'http' => [
        'header' => "Content-Type: application/json\r\n",
        'method' => 'POST',
        'content' => json_encode($data),
    ],
];

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result === FALSE) {
    die('Erreur lors de la création de la clé');
}

// Afficher la réponse
$response = json_decode($result, true);

// Vérifie si la clé a été générée avec succès
if (isset($response['key'])) {
    echo 'Clé générée : <strong>' . $response['key'] . '</strong>';
} else {
    echo 'Erreur : ' . $response['message'];
}
?>
