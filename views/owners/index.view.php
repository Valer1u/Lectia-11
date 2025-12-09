<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Proprietari - Sistem de Management al Flotei</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.4/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="bg-light">
    <?php include __DIR__ . "/../components/nav.view.php"; ?>
    <section class="py-5">
        <div class="container-lg">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="mb-2 heading-danger">Proprietari</h2>
                    <p class="text-muted mb-0">Gestionează proprietarii vehiculelor</p>
                </div>
                    <a href="/owners/create" class="btn btn-gradient-danger"><i class="bi bi-plus-lg"></i> Adaugă Proprietar</a>
            </div>

            <?php if(empty($owners)): ?>
                <div class="alert alert-info text-center" role="alert">
                    <i class="bi bi-info-circle"></i> Nu există proprietari în sistem. 
                    <a href="/owners/create" class="alert-link">Adaugă unu acum</a>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach($owners as $o): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card owner-card shadow-sm">
                            <div class="owner-card-header">
                                <i class="bi bi-person-check"></i>
                                <h5><?= htmlspecialchars($o->name) ?></h5>
                            </div>
                            <div class="owner-card-body">
                                <div class="owner-info">
                                    <strong>Mașină:</strong>
                                    <p class="mb-0">
                                        <?php if($o->car): ?>
                                            <?= htmlspecialchars($o->car->model) ?>
                                            <?php if($o->car->mechanic): ?>
                                                <br><small class="text-muted">Mecanic: <?= htmlspecialchars($o->car->mechanic->name) ?></small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <em class="text-muted">Nicio mașină asignată</em>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <div class="action-buttons">
                                    <a href="/owners/<?= $o->id ?>" class="btn btn-sm btn-outline-info" title="Detalii"><i class="bi bi-eye"></i></a>
                                    <a href="/owners/<?= $o->id ?>/edit" class="btn btn-sm btn-outline-primary" title="Editează"><i class="bi bi-pencil"></i></a>
                                    <a href="/owners/<?= $o->id ?>/delete" class="btn btn-sm btn-outline-danger" title="Șterge" onclick="return confirm('Șterge proprietarul?');"><i class="bi bi-trash"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php include __DIR__ . "/../components/footer.view.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>