<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mecanici - Sistem de Management al Flotei</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.4/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/app.css">
</head>

<body class="bg-light">
    <?php include __DIR__ . "/../components/nav.view.php"; ?>

    <!-- Mechanics Section -->
    <section class="py-5">
        <div class="container-lg">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="mb-2 heading-primary">Mecanici din Sistem</h2>
                    <p class="text-muted mb-0">Gestionează mecanicii și mașinile alocate</p>
                </div>
                <a href="/mechanic/create" class="btn btn-gradient-primary"><i class="bi bi-plus-lg"></i> Adaugă Mecanic</a>
            </div>

            <?php if(empty($mechanics)): ?>
                <div class="alert alert-info text-center" role="alert">
                    <i class="bi bi-info-circle"></i> Nu există mecanici în sistem. 
                    <a href="/mechanic/create" class="alert-link">Adaugă unu acum</a>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($mechanics as $mechanic): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card mechanic-card shadow-sm">
                            <div class="mechanic-card-header">
                                <i class="bi bi-tools"></i>
                                <h5><?= htmlspecialchars($mechanic->name) ?></h5>
                            </div>
                            <div class="mechanic-card-body">
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-2"><strong>Mașini alocate:</strong></small>
                                    <?php $cars = $mechanic->cars()->get(); ?>
                                    <?php if(count($cars) > 0): ?>
                                        <ul class="cars-list">
                                            <?php foreach($cars as $car): ?>
                                                <li class="d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <a href="/cars/<?= $car->id ?>" class="card-link">
                                                            <strong><?= htmlspecialchars($car->model) ?></strong>
                                                        </a>
                                                        <?php if($car->owner): ?>
                                                            <br><small class="text-muted">Proprietar: <?= htmlspecialchars($car->owner->name) ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <a href="/cars/<?= $car->id ?>" class="btn btn-sm btn-outline-info" title="Vezi mașina"><i class="bi bi-eye"></i></a>
                                                        <a href="/cars/<?= $car->id ?>/delete" class="btn btn-sm btn-outline-danger" title="Șterge mașina" onclick="return confirm('Șterge această mașină?');"><i class="bi bi-trash"></i></a>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p class="text-muted mb-0"><em>Nicio mașină alocată</em></p>
                                    <?php endif; ?>
                                </div>
                                <div class="action-buttons">
                                    <a href="/mechanic/<?= $mechanic->id ?>" class="btn btn-sm btn-outline-info" title="Detalii"><i class="bi bi-eye"></i></a>
                                    <a href="/mechanic/<?= $mechanic->id ?>/edit" class="btn btn-sm btn-outline-primary" title="Editează"><i class="bi bi-pencil-square"></i></a>
                                    <a href="/mechanic/<?= $mechanic->id ?>/delete" class="btn btn-sm btn-outline-danger" title="Șterge" onclick="return confirm('Șterge mecanicul?');"><i class="bi bi-trash"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <!-- Footer -->
    <?php include __DIR__ . "/../components/footer.view.php"; ?>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>