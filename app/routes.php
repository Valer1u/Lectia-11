<?php

use App\Controllers\HomeController;
use App\Controllers\CarController;
use App\Controllers\MechanicController;
use App\Controllers\OwnerController;

$app->get("/", [HomeController::class, 'index']);

$app->get("/cars", [CarController::class, 'index']);
$app->get("/cars/create", [CarController::class, 'create']);
$app->post("/cars", [CarController::class, 'store']);
$app->get("/cars/{id}", [CarController::class, 'show']);
$app->get("/cars/{id}/edit", [CarController::class, 'edit']);
$app->post("/cars/{id}/update", [CarController::class, 'update']);
$app->get("/cars/{id}/delete", [CarController::class, 'delete']);
$app->post("/cars/{id}/delete", [CarController::class, 'delete']);
$app->get("/mechanic", [MechanicController::class, 'index']);
$app->get("/mechanic/create", [MechanicController::class, 'create']);
$app->post("/mechanic", [MechanicController::class, 'store']);
$app->get("/mechanic/{id}", [MechanicController::class, 'show']);
$app->get("/mechanic/{id}/edit", [MechanicController::class, 'edit']);
$app->post("/mechanic/{id}/update", [MechanicController::class, 'update']);
$app->get("/mechanic/{id}/delete", [MechanicController::class, 'delete']);
// Owners routes
$app->get("/owners", [OwnerController::class, 'index']);
$app->get("/owners/create", [OwnerController::class, 'create']);
$app->post("/owners", [OwnerController::class, 'store']);
$app->get("/owners/{id}", [OwnerController::class, 'show']);
$app->get("/owners/{id}/edit", [OwnerController::class, 'edit']);
$app->post("/owners/{id}/update", [OwnerController::class, 'update']);
$app->get("/owners/{id}/delete", [OwnerController::class, 'delete']);


