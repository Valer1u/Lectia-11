<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formular Mașină</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.4/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/app.css">
</head>

<body class="bg-light">
    <?php include __DIR__ . "/../components/nav.view.php"; ?>

    <div class="container form-card">
        <div class="card shadow-sm">
            <div class="card-header form-header py-3">
                <h4 class="mb-0"><?= $car ? 'Editează Mașina' : 'Adaugă Mașină Nouă' ?></h4>
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

                <form method="post" action="<?= $action; ?>">
                    <div class="mb-3">
                        <label class="form-label required">Model</label>
                        <input type="text" name="model" class="form-control" value="<?= isset($old['model']) ? htmlspecialchars($old['model']) : ($car ? htmlspecialchars($car->model) : '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Mecanic</label>
                        <?php $mechanicsList = isset($mechanics) ? $mechanics : []; ?>
                        <select name="mechanic_id" id="mechanic-select" class="form-select" required>
                            <option value="">-- Selectează mecanic --</option>
                            <?php foreach($mechanicsList as $m): ?>
                                <?php
                                    $selected = '';
                                    if(isset($old['mechanic_id']) && $old['mechanic_id'] == $m->id) {
                                        $selected = 'selected';
                                    } elseif(empty($old) && isset($car) && $car && $car->mechanic && $car->mechanic->id == $m->id) {
                                        $selected = 'selected';
                                    }
                                ?>
                                <option value="<?= $m->id ?>" <?= $selected ?>><?= htmlspecialchars($m->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">Selectați mecanicul responsabil pentru această mașină.</div>
                    </div>
                    <!-- Proprietar field removed as requested -->
                    <div class="mb-3">
                        <label class="form-label">Poză Mașină (URL)</label>
                        <?php if($car && !empty($car->image)): ?>
                            <div class="mb-2">
                                <img src="<?= $car->image ?>" alt="Poză mașină" class="img-fluid car-image">
                            </div>
                        <?php endif; ?>
                        <input type="url" name="image_url" class="form-control" placeholder="https://example.com/image.jpg" value="<?= isset($old['image_url']) ? htmlspecialchars($old['image_url']) : ($car ? htmlspecialchars($car->image) : '') ?>">
                        <div class="form-text">Introdu adresa URL a imaginii (ex: un link extern) sau lasă necompletat.</div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="/cars" class="btn btn-outline-secondary">Înapoi</a>
                        <button class="btn btn-primary">
                            <i class="bi bi-save"></i>
                            <?= $car ? 'Salvează modificările' : 'Adaugă Mașina' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include __DIR__ . "/../components/footer.view.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>