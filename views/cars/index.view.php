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

<body class="bg-light">
    <?php include __DIR__ . "/../components/nav.view.php"; ?>

    <!-- Cars Section -->
    <section class="py-5">
        <div class="container-lg">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="mb-0 fw-bold">Lista de Vehicule înregistrate</h2>
                    <p class="text-muted small mt-1">Gestionează toate mașinile din flota ta</p>
                </div>
                <a href="/cars/create" class="btn btn-gradient-primary btn-lg">
                    <i class="bi bi-plus-lg"></i> Adaugă Mașină
                </a>
            </div>
            <div class="row g-4">
                <?php foreach ($cars as $car): ?>
                    <div class="col-sm-6 col-md-4">
                        <div class="card h-100 shadow-sm car-card">
                            <?php if (!empty($car->image)): ?>
                                <img src="<?= $car->image ?>" class="card-img-top img-cover-small"
                                    alt="<?= htmlspecialchars($car->model) ?>">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/600x360?text=No+Image"
                                    class="card-img-top img-cover-small" alt="fără poză">
                            <?php endif; ?>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title mb-1 fw-bold accent-blue"><?= htmlspecialchars($car->model) ?></h5>
                                <p class="card-text mb-2"><strong>Mecanic:</strong> <span
                                        class="text-muted"><?= $car->mechanic ? htmlspecialchars($car->mechanic->name) : '—' ?></span>
                                </p>
                                <p class="card-text mb-3"><strong>Proprietar:</strong> <span
                                        class="text-muted"><?= $car->owner ? htmlspecialchars($car->owner->name) : '—' ?></span>
                                </p>
                                <div class="mt-auto d-flex gap-2">
                                    <a href="/cars/<?= $car->id; ?>" class="btn btn-sm btn-outline-info" title="Detalii"><i
                                            class="bi bi-eye"></i></a>
                                    <a href="/cars/<?= $car->id; ?>/edit" class="btn btn-sm btn-outline-primary"
                                        title="Editează"><i class="bi bi-pencil-square"></i></a>
                                    <form action="/cars/<?= $car->id; ?>/delete" method="post" style="display:inline"
                                          onsubmit="return confirm('Sigur doriți să ștergeți această intrare?');">
                                        <button type="submit" class="btn btn-sm btn-outline-danger ms-auto" title="Șterge">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <!-- Footer -->
    <?php include __DIR__ . "/../components/footer.view.php"; ?>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>