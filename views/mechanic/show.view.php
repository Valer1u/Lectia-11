<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalii Mecanic</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.4/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="bg-light">
    <?php include __DIR__ . "/../components/nav.view.php"; ?>
    <div class="container my-5">
        <div class="card mx-auto card-max-900">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detalii Mecanic</h5>
                <a href="/mechanic/<?= $mechanic->id ?>/edit" class="btn btn-sm btn-outline-primary">Editează</a>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Nume</dt>
                    <dd class="col-sm-9"><?= htmlspecialchars($mechanic->name) ?></dd>

                    <dt class="col-sm-3">Mașini atribuite</dt>
                    <dd class="col-sm-9">
                        <?php if(count($cars) === 0): ?>
                            —
                        <?php else: ?>
                            <ul>
                                <?php foreach($cars as $c): ?>
                                    <li class="mb-2 d-flex justify-content-between align-items-center">
                                        <div>
                                            <?= htmlspecialchars($c->model) ?>
                                            (<a href="/cars/<?= $c->id ?>">detalii</a>)
                                        </div>
                                        <div>
                                            <a href="/cars/<?= $c->id ?>/delete" class="btn btn-sm btn-outline-danger" onclick="return confirm('Șterge această mașină?');">Șterge</a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </dd>
                </dl>
                <a href="/mechanic" class="btn btn-outline-secondary">Înapoi</a>
            </div>
        </div>
    </div>
    <?php include __DIR__ . "/../components/footer.view.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>