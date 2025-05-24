<?php

// Ladet daten der düfte in der Modalbox 'Bearbeiten'

session_start();
require_once "db.php";

$id = $_GET["id"] ?? 0;
if (!$id) exit;

$stmt = $pdo->prepare("SELECT * FROM scents WHERE id = ?");
$stmt->execute([$id]);
$scent = $stmt->fetch(PDO::FETCH_ASSOC);

function getNotes($pdo, $id, $type) {
    $stmt = $pdo->prepare("SELECT note_name FROM scent_notes WHERE scent_id = ? AND type = ?");
    $stmt->execute([$id, $type]);
    return implode(',', array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'note_name'));
}

function getAttrs($pdo, $id, $cat) {
    $stmt = $pdo->prepare("SELECT label, value FROM scent_attributes WHERE scent_id = ? AND category = ?");
    $stmt->execute([$id, $cat]);
    return implode(",", array_map(fn($r) => "{$r['label']}:{$r['value']}", $stmt->fetchAll(PDO::FETCH_ASSOC)));
}

// use_pyramid ermitteln
$usePyramid = (int)($scent['use_pyramid'] ?? 1);

if ($usePyramid === 1) {
    // Duftpyramide
    $notesTop   = getNotes($pdo, $id, 'Kopf');
    $notesHeart = getNotes($pdo, $id, 'Herz');
    $notesBase  = getNotes($pdo, $id, 'Basis');
    // Allgemeine Noten entfallen
    $notesAll   = '';
} else {
    // use_pyramid=0 => Allgemeine Noten
    $notesTop   = '';
    $notesHeart = '';
    $notesBase  = '';
    $notesAll   = getNotes($pdo, $id, 'All');
}

echo json_encode([
    'id'          => $scent['id'],
    'code'        => $scent['code'],
    'inspired_by' => $scent['inspired_by'],
    'gender'      => $scent['gender'],
    'description' => $scent['description'],
    'use_pyramid' => $usePyramid,

    // Entweder gefüllt oder leer
    'notes_top'   => $notesTop,
    'notes_heart' => $notesHeart,
    'notes_base'  => $notesBase,
    'notes_all'   => $notesAll,

    'dufttyp'     => getAttrs($pdo, $id, 'Dufttyp'),
    'stil'        => getAttrs($pdo, $id, 'Stil'),
    'season'      => getAttrs($pdo, $id, 'Jahreszeit'),
    'occasion'    => getAttrs($pdo, $id, 'Anlass')
]);
