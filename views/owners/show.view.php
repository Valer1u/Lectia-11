<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalii Proprietar</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <?php include __DIR__ . "/../components/nav.view.php"; ?>
    <div class="container my-5">
        <div class="card mx-auto card-max-720">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detalii Proprietar</h5>
                <a href="/owners/<?= $owner->id ?>/edit" class="btn btn-sm btn-outline-primary">Editează</a>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Nume</dt>
                    <dd class="col-sm-9"><?= htmlspecialchars($owner->name) ?></dd>

                    <dt class="col-sm-3">Mașină</dt>
                    <dd class="col-sm-9"><?= $car ? htmlspecialchars($car->model) : '—' ?></dd>

                    <dt class="col-sm-3">ID</dt>
                    <dd class="col-sm-9"><?= $owner->id ?></dd>
                </dl>
                <a href="/owners" class="btn btn-outline-secondary">Înapoi</a>
            </div>
        </div>
    </div>
    <?php include __DIR__ . "/../components/footer.view.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>