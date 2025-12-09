<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalii Mașină</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.4/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/app.css">
</head>

<body class="bg-light">
    <?php include __DIR__ . "/../components/nav.view.php"; ?>

    <div class="container my-5">
        <div class="card shadow-sm card-max-900 mx-auto">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold accent-blue">Detalii Mașină</h4>
                <div>
                    <a href="/cars/<?= $car->id; ?>/edit" class="btn btn-sm btn-outline-primary me-2"><i class="bi bi-pencil"></i> Editează</a>
                    <a href="/cars" class="btn btn-sm btn-outline-secondary">Înapoi</a>
                </div>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3 fw-bold">Model</dt>
                    <dd class="col-sm-9"><?= htmlspecialchars($car->model) ?></dd>

                    <dt class="col-sm-3 fw-bold">Poză</dt>
                    <dd class="col-sm-9">
                        <?php if(!empty($car->image)): ?>
                            <img src="<?= $car->image ?>" alt="poză" class="img-fluid img-cover-large">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/800x400?text=No+Image" alt="fără poză" class="img-fluid">
                        <?php endif; ?>
                    </dd>

                    <dt class="col-sm-3 fw-bold">Mecanic</dt>
                    <dd class="col-sm-9"><?= $car->mechanic ? htmlspecialchars($car->mechanic->name) : '—' ?></dd>

                    <dt class="col-sm-3 fw-bold">Proprietar</dt>
                    <dd class="col-sm-9"><?= $owner ? htmlspecialchars($owner->name) : '—' ?></dd>

                    <dt class="col-sm-3 fw-bold">ID</dt>
                    <dd class="col-sm-9"><?= $car->id ?></dd>
                </dl>
            </div>
        </div>
    </div>

    <?php include __DIR__ . "/../components/footer.view.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>