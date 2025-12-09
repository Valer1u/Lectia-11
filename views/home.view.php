<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem de Management al Flotei - Acasă</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.4/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <?php include __DIR__ . "/components/nav.view.php"; ?>

    <!-- Hero Section -->
    <section class="hero">
        <h1 class="display-3 fw-bold mb-4">Sistem de Management al Flotei</h1>
        <p class="lead mb-0">Gestionați vehicule, mecanici și proprietari cu ușurință</p>
    </section>

   <!-- Quick Navigation Section -->
<section class="py-5 nav-section">
    <div class="container-lg">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3 nav-title">Accesează rapid secțiunile principale</h2>
            <p class="text-muted nav-subtitle">Gestionați flota și echipa de mecanici în câteva clicuri</p>
        </div>
        <div class="row g-4">
            <!-- Cars Card -->
            <div class="col-lg-4 col-md-6">
                <a href="/cars" class="card-link">
                    <div class="card shadow-sm nav-card">
                        <div class="card-body text-center">
                            <div class="nav-card-icon accent-blue">
                                <i class="bi bi-car-front"></i>
                            </div>
                            <h5 class="card-title fw-bold accent-blue">Mașini</h5>
                            <p class="card-text text-muted small">
                                Gestionează vehiculele din flota ta
                            </p>
                            <span class="btn btn-sm btn-gradient-primary">Deschide</span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Mechanics Card -->
            <div class="col-lg-4 col-md-6">
                <a href="/mechanic" class="card-link">
                    <div class="card shadow-sm nav-card">
                        <div class="card-body text-center">
                            <div class="nav-card-icon accent-orange">
                                <i class="bi bi-tools"></i>
                            </div>
                            <h5 class="card-title fw-bold accent-orange">Mecanici</h5>
                            <p class="card-text text-muted small">
                                Administrează mecanicii și atribuțiile lor
                            </p>
                            <span class="btn btn-sm btn-gradient-warning">Deschide</span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Owners Card -->
            <div class="col-lg-4 col-md-6">
                <a href="/owners" class="card-link">
                    <div class="card shadow-sm nav-card">
                        <div class="card-body text-center">
                            <div class="nav-card-icon accent-red">
                                <i class="bi bi-person-check"></i>
                            </div>
                            <h5 class="card-title fw-bold accent-red">Proprietari</h5>
                            <p class="card-text text-muted small">
                                Gestionează proprietarii vehiculelor
                            </p>
                            <span class="btn btn-sm btn-gradient-danger">Deschide</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>
