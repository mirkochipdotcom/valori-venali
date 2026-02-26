<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

requireAuth();

$n_periodi     = DB::queryOne('SELECT COUNT(DISTINCT Periodo) as n FROM valori_omi')['n'] ?? 0;
$n_zone        = DB::queryOne('SELECT COUNT(DISTINCT Zona) as n FROM valori_omi')['n'] ?? 0;
$n_valori      = DB::queryOne('SELECT COUNT(*) as n FROM valori_omi')['n'] ?? 0;
$n_destinazioni = DB::queryOne('SELECT COUNT(*) as n FROM omi_destinazione_urbanistica')['n'] ?? 0;
$n_coefficienti = DB::queryOne('SELECT COUNT(*) as n FROM omi_abbattimenti')['n'] ?? 0;
$n_fogli       = DB::queryOne('SELECT COUNT(*) as n FROM fogli_zone_omi')['n'] ?? 0;

$ultimo_periodo = DB::queryOne('SELECT Periodo FROM valori_omi ORDER BY Periodo DESC LIMIT 1');

$isAdmin   = true;
$pageTitle = 'Dashboard Admin';
include __DIR__ . '/../layout/header.php';
?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <h2 class="h4 fw-bold mb-0">🏠 Dashboard Amministrazione</h2>
  <span class="badge bg-success px-3 py-2">
    Bentornato, <?= htmlspecialchars($_SESSION['username']) ?>
  </span>
</div>

<!-- KPI Cards -->
<div class="row g-3 mb-4">
  <div class="col-sm-6 col-lg-4">
    <div class="card h-100" style="border-top: 4px solid #0066cc;">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <div class="text-muted small">Record OMI totali</div>
            <div class="display-6 fw-bold text-primary"><?= number_format($n_valori) ?></div>
          </div>
          <span style="font-size:2rem">📊</span>
        </div>
        <div class="small text-muted mt-2">
          <?= $n_periodi ?> periodi · <?= $n_zone ?> zone OMI
          <?php if ($ultimo_periodo): ?>
            <br>Ultimo: <strong><?= htmlspecialchars($ultimo_periodo['Periodo']) ?></strong>
          <?php endif; ?>
        </div>
      </div>
      <div class="card-footer bg-transparent border-0 pt-0">
        <a href="<?= APP_URL ?>/admin/importa_omi.php" class="btn btn-sm btn-outline-primary">Gestisci →</a>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-4">
    <div class="card h-100" style="border-top: 4px solid #198754;">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <div class="text-muted small">Destinazioni Urbanistiche</div>
            <div class="display-6 fw-bold text-success"><?= $n_destinazioni ?></div>
          </div>
          <span style="font-size:2rem">🏗️</span>
        </div>
        <div class="small text-muted mt-2">Coefficienti PRG configurati</div>
      </div>
      <div class="card-footer bg-transparent border-0 pt-0">
        <a href="<?= APP_URL ?>/admin/parametri_omi.php" class="btn btn-sm btn-outline-success">Gestisci →</a>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-4">
    <div class="card h-100" style="border-top: 4px solid #fd7e14;">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <div class="text-muted small">Coefficienti Abbattimento</div>
            <div class="display-6 fw-bold" style="color:#fd7e14;"><?= $n_coefficienti ?></div>
          </div>
          <span style="font-size:2rem">📉</span>
        </div>
        <div class="small text-muted mt-2">Stati conservativi configurati</div>
      </div>
      <div class="card-footer bg-transparent border-0 pt-0">
        <a href="<?= APP_URL ?>/admin/coefficienti_abbattimento.php" class="btn btn-sm btn-outline-warning">Gestisci →</a>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-4">
    <div class="card h-100" style="border-top: 4px solid #6f42c1;">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <div class="text-muted small">Fogli Catastali Abbinati</div>
            <div class="display-6 fw-bold text-purple" style="color:#6f42c1;"><?= $n_fogli ?></div>
          </div>
          <span style="font-size:2rem">🗺️</span>
        </div>
        <div class="small text-muted mt-2">Abbinamenti foglio → zona OMI</div>
      </div>
      <div class="card-footer bg-transparent border-0 pt-0">
        <a href="<?= APP_URL ?>/admin/fogli_omi.php" class="btn btn-sm btn-outline-secondary">Gestisci →</a>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card h-100">
      <div class="card-header">🚀 Accesso rapido</div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-6">
            <a href="<?= APP_URL ?>/admin/importa_omi.php" class="btn btn-outline-primary w-100 py-3">
              📂 <strong>Importa CSV OMI</strong><br>
              <small>Carica nuovi valori dal portale Sister</small>
            </a>
          </div>
          <div class="col-md-6">
            <a href="<?= APP_URL ?>/admin/parametri_omi.php" class="btn btn-outline-success w-100 py-3">
              ⚙️ <strong>Parametri Destinazione</strong><br>
              <small>Gestione zone PRG e coefficienti</small>
            </a>
          </div>
          <div class="col-md-6">
            <a href="<?= APP_URL ?>/admin/coefficienti_abbattimento.php" class="btn btn-outline-warning w-100 py-3">
              📉 <strong>Coefficienti Abbattimento</strong><br>
              <small>Stati conservativi e percentuali</small>
            </a>
          </div>
          <div class="col-md-6">
            <a href="<?= APP_URL ?>/admin/fogli_omi.php" class="btn btn-outline-secondary w-100 py-3">
              🗺️ <strong>Fogli Catastali</strong><br>
              <small>Abbinamento foglio → zona OMI</small>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="alert alert-light border">
  <strong>💡 Configurazione iniziale consigliata:</strong>
  <ol class="mb-0 mt-1">
    <li>Importa i valori OMI dal portale Sister (CSV)</li>
    <li>Abbina i fogli catastali alle zone OMI</li>
    <li>Configura le destinazioni urbanistiche con relativi coefficienti PRG</li>
    <li>Verifica i coefficienti di abbattimento per stato conservativo</li>
  </ol>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
