<?php
/**
 * Admin — Gestione Coefficienti di Abbattimento (stato conservativo)
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

requireAuth();

$messaggio = null;
$tipoMsg   = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();

    if (isset($_POST['elimina']) && $_POST['elimina'] !== '') {
        $id = (int) $_POST['elimina'];
        DB::execute('DELETE FROM omi_abbattimenti WHERE id_coefficiente = ?', [$id]);
        $messaggio = 'Coefficiente eliminato.';
        $tipoMsg   = 'warning';
    } elseif (isset($_POST['salva'])) {
        $desc  = trim($_POST['descrizione'] ?? '');
        $coeff = str_replace(',', '.', trim($_POST['coefficiente'] ?? ''));
        if ($desc === '' || !is_numeric($coeff) || $coeff < 0 || $coeff > 1) {
            $messaggio = 'Inserire una descrizione valida e un coefficiente tra 0 e 1.';
            $tipoMsg   = 'danger';
        } else {
            DB::execute(
                'INSERT INTO omi_abbattimenti (descrizione, valore) VALUES (?, ?)',
                [$desc, (float) $coeff]
            );
            $messaggio = "Coefficiente \"$desc\" aggiunto.";
        }
    }
}

$coefficienti = DB::query('SELECT * FROM omi_abbattimenti ORDER BY valore DESC');

$isAdmin   = true;
$pageTitle = 'Coefficienti di Abbattimento';
include __DIR__ . '/../layout/header.php';
?>

<nav aria-label="breadcrumb" class="mb-3">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= APP_URL ?>/admin/dashboard.php">Dashboard</a></li>
    <li class="breadcrumb-item active">Coefficienti Abbattimento</li>
  </ol>
</nav>

<h2 class="h4 fw-bold mb-4">📉 Gestione Coefficienti di Abbattimento</h2>
<p class="text-muted mb-4">I coefficienti di abbattimento vengono applicati al valore OMI in base allo stato conservativo dell'immobile (valore tra 0 e 1).</p>

<?php if ($messaggio): ?>
  <div class="alert alert-<?= $tipoMsg ?> alert-dismissible fade show" role="alert">
    <?= htmlspecialchars($messaggio) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<div class="row g-4">
  <!-- Tabella coefficienti -->
  <div class="col-lg-7">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>Coefficienti presenti</span>
        <span class="badge bg-primary"><?= count($coefficienti) ?></span>
      </div>
      <?php if (!$coefficienti): ?>
        <div class="card-body text-center text-muted py-5">
          <span style="font-size:3rem">📭</span><br>
          Nessun coefficiente configurato. Aggiungine uno.
        </div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr>
                <th class="ps-3">Descrizione</th>
                <th class="text-center">Coefficiente</th>
                <th class="text-center">Applicazione</th>
                <th style="width:100px"></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($coefficienti as $c): ?>
                <?php
                  $v = (float) $c['valore'];
                  $pct = round($v * 100);
                  if ($pct >= 90)     $colorBadge = 'success';
                  elseif ($pct >= 70) $colorBadge = 'warning';
                  else                $colorBadge = 'danger';
                ?>
              <tr>
                <td class="ps-3 fw-semibold"><?= htmlspecialchars($c['descrizione']) ?></td>
                <td class="text-center">
                  <code><?= number_format($v, 4, ',', '.') ?></code>
                </td>
                <td class="text-center">
                  <span class="badge bg-<?= $colorBadge ?> px-2"><?= $pct ?>%</span>
                </td>
                <td>
                  <form method="post" action="<?= APP_URL ?>/admin/coefficienti_abbattimento.php"
                        onsubmit="return confirm('Eliminare questo coefficiente?')">
                    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                    <button type="submit" name="elimina" value="<?= (int)$c['id_coefficiente'] ?>"
                            class="btn btn-sm btn-outline-danger w-100">🗑 Elimina</button>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Form aggiungi -->
  <div class="col-lg-5">
    <div class="card">
      <div class="card-header">➕ Nuovo Coefficiente</div>
      <div class="card-body p-4">
        <form method="post" action="<?= APP_URL ?>/admin/coefficienti_abbattimento.php" novalidate>
          <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
          <div class="mb-3">
            <label for="descrizione" class="form-label fw-semibold">Descrizione</label>
            <input type="text" class="form-control" id="descrizione" name="descrizione"
                   placeholder="Es. Buono stato conservativo" required>
          </div>
          <div class="mb-4">
            <label for="coefficiente" class="form-label fw-semibold">Coefficiente</label>
            <input type="text" class="form-control" id="coefficiente" name="coefficiente"
                   placeholder="Es. 0.90" required>
            <div class="form-text">Valore tra 0 e 1 (usa il punto «.» come separatore decimale)</div>
          </div>
          <div class="d-grid">
            <button type="submit" name="salva" class="btn btn-primary">Aggiungi Coefficiente</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
