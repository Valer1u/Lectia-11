<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $owner ? 'Editează Proprietar' : 'Adaugă Proprietar' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <?php include __DIR__ . "/../components/nav.view.php"; ?>
    <div class="container my-5">
        <div class="card mx-auto card-max-720">
            <div class="card-header">
                <h5 class="mb-0"><?= $owner ? 'Editează Proprietar' : 'Adaugă Proprietar' ?></h5>
            </div>
            <div class="card-body">
                        <?php if(!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach($errors as $e): ?>
                                        <li><?= htmlspecialchars($e) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="<?= $action ?>">
                    <div class="mb-3">
                        <label class="form-label">Nume</label>
                                <input type="text" name="name" class="form-control" value="<?= isset($old['name']) ? htmlspecialchars($old['name']) : ($owner ? htmlspecialchars($owner->name) : '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mașină (opțional) — introdu modelul</label>
                                <input type="text" name="car_model" class="form-control" placeholder="Ex: Dacia Logan" value="<?= isset($old['car_model']) ? htmlspecialchars($old['car_model']) : ($owner && $owner->car ? htmlspecialchars($owner->car->model) : '') ?>">
                        <div class="form-text">Dacă completați, se va crea/legă o mașină cu acest model pentru proprietar.</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="/owners" class="btn btn-outline-secondary">Înapoi</a>
                        <button class="btn btn-primary"><?= $owner ? 'Salvează' : 'Adaugă' ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include __DIR__ . "/../components/footer.view.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>