<?php

$filePath = 'tarefas.json';

function getTasks($filePath) {
    if (!file_exists($filePath)) {
        file_put_contents($filePath, json_encode([]));
    }
    $tasksJson = file_get_contents($filePath);
    return json_decode($tasksJson, true);
}

function addTask($filePath, $titulo) {
    $tasks = getTasks($filePath);
    $tasks[] = ['titulo' => $titulo];
    file_put_contents($filePath, json_encode($tasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    echo json_encode(getTasks($filePath), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} elseif ($method === 'POST') {
    $inputData = json_decode(file_get_contents('php://input'), true);

    if (isset($inputData['titulo']) && !empty(trim($inputData['titulo']))) {
        addTask($filePath, $inputData['titulo']);
        echo json_encode(['message' => 'Tarefa adicionada com sucesso!']);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'O campo "titulo" é obrigatório!']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
}
?>