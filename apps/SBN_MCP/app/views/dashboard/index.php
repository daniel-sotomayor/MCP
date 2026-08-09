<?php
/**
 * Vista: Dashboard principal
 * Métricas del inventario, bienes recientes, movimientos y distribución.
 *
 * @var array  $metrics           Métricas generales del inventario.
 * @var array  $recentBienes      Últimos 8 bienes registrados.
 * @var array  $recentMovimientos Últimos 6 movimientos.
 * @var array  $bienesPorEstado   Distribución por estado.
 * @var array  $bienesPorEdificio Distribución por edificio.
 * @var string $base_url          URL base de la aplicación.
 */

require APP_PATH . '/views/partials/sidebar.php';

$totalBienes = max((int)$metrics['total'], 1);
?>

<main class="main-content" id="mainContent">

    <!-- ── Header ──────────────────────────────────────────────── -->
    <div class="page-header">
        <div>
            <h1>Dashboard</h1>
            <p class="dash-subtitle">Resumen general del inventario de bienes nacionales</p>
        </div>
        <div class="page-actions">
            <span class="dash-datetime">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
                <?= date('d/m/Y H:i') ?>
                &nbsp;·&nbsp;
                <?= htmlspecialchars($_SESSION['nombre'] ?? '') ?>
            </span>
        </div>
    </div>

    <!-- ── Métricas principales ───────────────────────────────── -->
    <div class="dash-section">
        <div class="metrics-grid">

            <div class="metric-card metric-card--total">
                <div class="metric-icon blue" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 7h-9"/><path d="M14 17H5"/>
                        <circle cx="17" cy="17" r="3"/><circle cx="7" cy="7" r="3"/>
                    </svg>
                </div>
                <div class="metric-info">
                    <span class="metric-value counter" data-target="<?= $metrics['total'] ?>">0</span>
                    <span class="metric-label">Total Bienes</span>
                </div>
                <div class="metric-trend metric-trend--neutral">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2v20M2 12h20"/></svg>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-icon green" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
                <div class="metric-info">
                    <span class="metric-value"><?= number_format($metrics['operativos']) ?></span>
                    <span class="metric-label">Operativos</span>
                </div>
                <div class="metric-badge metric-badge--green">
                    <?= $totalBienes > 0 ? round($metrics['operativos'] / $totalBienes * 100) : 0 ?>%
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-icon yellow" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                </div>
                <div class="metric-info">
                    <span class="metric-value"><?= number_format($metrics['inoperativos']) ?></span>
                    <span class="metric-label">Inoperativos</span>
                </div>
                <div class="metric-badge metric-badge--yellow">
                    <?= $totalBienes > 0 ? round($metrics['inoperativos'] / $totalBienes * 100) : 0 ?>%
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-icon teal" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                </div>
                <div class="metric-info">
                    <span class="metric-value"><?= number_format($metrics['resguardo']) ?></span>
                    <span class="metric-label">En Resguardo</span>
                </div>
                <div class="metric-badge metric-badge--teal">
                    <?= $totalBienes > 0 ? round($metrics['resguardo'] / $totalBienes * 100) : 0 ?>%
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-icon red" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                </div>
                <div class="metric-info">
                    <span class="metric-value"><?= number_format($metrics['chatarra']) ?></span>
                    <span class="metric-label">Chatarra</span>
                </div>
                <div class="metric-badge metric-badge--red">
                    <?= $totalBienes > 0 ? round($metrics['chatarra'] / $totalBienes * 100) : 0 ?>%
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-icon gray" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6l-1 14H6L5 6"/>
                        <path d="M10 11v6"/><path d="M14 11v6"/>
                    </svg>
                </div>
                <div class="metric-info">
                    <span class="metric-value"><?= number_format($metrics['desincorp']) ?></span>
                    <span class="metric-label">Desincorporados</span>
                </div>
                <div class="metric-badge metric-badge--gray">
                    <?= $totalBienes > 0 ? round($metrics['desincorp'] / $totalBienes * 100) : 0 ?>%
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-icon purple" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="1" x2="12" y2="23"/>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </div>
                <div class="metric-info">
                    <span class="metric-value"><?= number_format($metrics['valor'], 0, ',', '.') ?></span>
                    <span class="metric-label">Valor Total (Bs.)</span>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-icon orange" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="5 9 2 12 5 15"/><polyline points="19 9 22 12 19 15"/>
                        <line x1="2" y1="12" x2="22" y2="12"/>
                    </svg>
                </div>
                <div class="metric-info">
                    <span class="metric-value"><?= number_format($metrics['movMes']) ?></span>
                    <span class="metric-label">Movimientos (mes)</span>
                </div>
            </div>

        </div>
    </div>

    <!-- ── Gráficos de distribución ───────────────────────────── -->
    <div class="dash-section">
        <div class="dash-charts">

            <!-- Distribución por Estado -->
            <div class="card dash-card">
                <div class="card-header">
                    <h3>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="card-header-icon"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                        Distribución por Estado
                    </h3>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height:280px;">
                        <canvas id="chartEstadosBar"></canvas>
                    </div>
                </div>
            </div>

            <!-- Bienes por Edificio -->
            <div class="card dash-card">
                <div class="card-header">
                    <h3>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="card-header-icon"><path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/><path d="M9 9h.01"/><path d="M9 13h.01"/><path d="M9 17h.01"/></svg>
                        Bienes por Edificio
                    </h3>
                </div>
                <div class="card-body">
                    <?php if (empty($bienesPorEdificio)): ?>
                        <div class="empty-state">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-muted"><path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/></svg>
                            <p>Sin datos disponibles</p>
                        </div>
                    <?php else: ?>
                        <div class="chart-container" style="height:280px;">
                            <canvas id="chartEdificioPie"></canvas>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <!-- ── Análisis por Área – Toma de Decisiones ────────────── -->
    <div class="dash-section">
        <div class="section-header">
            <h2 class="section-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                Análisis por Área
            </h2>
            <span class="section-subtitle">Indicadores para la toma de decisiones</span>
        </div>

        <div class="dash-analytics">

            <!-- Salud de Áreas – Resumen ejecutivo -->
            <div class="card dash-card">
                <div class="card-header">
                    <h3>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="card-header-icon"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                        Salud por Área
                    </h3>
                    <span class="card-badge"><?= count($areaResumen) ?> áreas</span>
                </div>
                <div class="card-body">
                    <?php if (empty($areaResumen)): ?>
                        <div class="empty-state"><p>Sin datos de áreas disponibles</p></div>
                    <?php else: ?>
                        <div class="area-health-list">
                            <?php foreach (array_slice($areaResumen, 0, 8) as $ar): ?>
                            <div class="area-health-row">
                                <div class="area-health-info">
                                    <span class="area-health-name"><?= htmlspecialchars($ar['nombre_area']) ?></span>
                                    <span class="area-health-edificio"><?= htmlspecialchars($ar['edificio']) ?></span>
                                </div>
                                <div class="area-health-bar-wrap">
                                    <div class="area-health-bar">
                                        <div class="area-health-fill" style="width:<?= $ar['pct_operativos'] ?>%;background:<?= $ar['salud_color'] ?>"></div>
                                    </div>
                                    <span class="area-health-pct"><?= $ar['pct_operativos'] ?>%</span>
                                </div>
                                <span class="area-health-badge" style="background:<?= $ar['salud_bg'] ?>;color:<?= $ar['salud_color'] ?>">
                                    <?= $ar['salud'] ?>
                                </span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Gráfico de barras apiladas por área -->
            <div class="card dash-card">
                <div class="card-header">
                    <h3>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="card-header-icon"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                        Composición por Área
                    </h3>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height:320px;">
                        <canvas id="chartAreaStacked"></canvas>
                    </div>
                </div>
            </div>

            <!-- Áreas que requieren atención -->
            <div class="card dash-card dash-card--alert">
                <div class="card-header card-header--alert">
                    <h3>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="card-header-icon"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        Requieren Atención
                    </h3>
                    <span class="card-badge card-badge--red"><?= count($topAreasInoperativos) ?> áreas</span>
                </div>
                <div class="card-body">
                    <?php if (empty($topAreasInoperativos)): ?>
                        <div class="empty-state">
                            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            <p>Todas las áreas están operativas</p>
                        </div>
                    <?php else: ?>
                        <div class="alert-table">
                            <div class="alert-table-head">
                                <span>Área</span>
                                <span>Total</span>
                                <span>Problema</span>
                                <span>%</span>
                            </div>
                            <?php foreach ($topAreasInoperativos as $area): ?>
                            <?php
                                $totalA = (int)$area['total'];
                                $problema = (int)$area['inoperativos'] + (int)$area['chatarra'];
                                $pctProb = (float)$area['pct_problema'];
                                $valorRiesgo = (float)$area['valor_riesgo'];
                            ?>
                            <div class="alert-table-row">
                                <div class="alert-table-area">
                                    <span class="alert-table-name"><?= htmlspecialchars($area['nombre_area']) ?></span>
                                    <span class="alert-table-edificio"><?= htmlspecialchars($area['edificio']) ?></span>
                                </div>
                                <span class="alert-table-total"><?= $totalA ?></span>
                                <div class="alert-table-detail">
                                    <?php if ((int)$area['inoperativos'] > 0): ?>
                                        <span class="alert-tag alert-tag--yellow"><?= $area['inoperativos'] ?> inop.</span>
                                    <?php endif; ?>
                                    <?php if ((int)$area['chatarra'] > 0): ?>
                                        <span class="alert-tag alert-tag--red"><?= $area['chatarra'] ?> chat.</span>
                                    <?php endif; ?>
                                </div>
                                <span class="alert-table-pct <?php echo $pctProb >= 50 ? 'alert-table-pct--danger' : ($pctProb >= 25 ? 'alert-table-pct--warn' : ''); ?>">
                                    <?= $pctProb ?>%
                                </span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <!-- ── Tablas de datos ────────────────────────────────────── -->
    <div class="dash-section">
        <div class="dash-tables">

            <!-- Últimos bienes registrados -->
            <div class="card dash-card">
                <div class="card-header">
                    <h3>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="card-header-icon"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a4 4 0 0 0-8 0v2"/></svg>
                        Últimos Bienes Registrados
                    </h3>
                    <a href="<?= $base_url ?>/bienes" class="btn btn-sm btn-ghost">Ver todos →</a>
                </div>
                <div class="table-wrapper">
                    <table class="data-table" aria-label="Últimos bienes registrados">
                        <thead>
                            <tr>
                                <th scope="col">Cód. Interno</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Área</th>
                                <th scope="col">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($recentBienes)): ?>
                            <tr class="empty-row">
                                <td colspan="4">
                                    <div class="empty-state empty-state--inline">
                                        <p>No hay bienes registrados aún</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentBienes as $b): ?>
                            <tr>
                                <td>
                                    <span class="codigo-interno">
                                        <?= htmlspecialchars($b['codigo_interno'] ?? '') ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= $base_url ?>/bienes/<?= (int)$b['id_bien'] ?>" class="table-link">
                                        <?= htmlspecialchars($b['nombre']) ?>
                                    </a>
                                </td>
                                <td class="text-muted-sm"><?= htmlspecialchars($b['nombre_area'] ?? '—') ?></td>
                                <td>
                                    <span class="badge" style="background:<?= htmlspecialchars($b['estado_color']) ?>">
                                        <?= htmlspecialchars($b['estado']) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Movimientos recientes -->
            <div class="card dash-card">
                <div class="card-header">
                    <h3>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="card-header-icon"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        Movimientos Recientes
                    </h3>
                    <a href="<?= $base_url ?>/movimientos" class="btn btn-sm btn-ghost">Ver todos →</a>
                </div>
                <div class="table-wrapper">
                    <table class="data-table" aria-label="Movimientos recientes">
                        <thead>
                            <tr>
                                <th scope="col">Tipo</th>
                                <th scope="col">Bien</th>
                                <th scope="col">Ruta</th>
                                <th scope="col">Usuario</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($recentMovimientos)): ?>
                            <tr class="empty-row">
                                <td colspan="4">
                                    <div class="empty-state empty-state--inline">
                                        <p>No hay movimientos registrados</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentMovimientos as $m):
                                $colorTipo = match($m['tipo_movimiento']) {
                                    'incorporacion'    => 'var(--success)',
                                    'traslado'         => 'var(--accent)',
                                    'desincorporacion' => 'var(--danger)',
                                    default            => 'var(--gray-400)',
                                };
                            ?>
                            <tr>
                                <td>
                                    <span class="badge badge-sm" style="background:<?= $colorTipo ?>">
                                        <?= ucfirst($m['tipo_movimiento']) ?>
                                    </span>
                                </td>
                                <td class="text-sm"><?= htmlspecialchars($m['bien_nombre']) ?></td>
                                <td class="text-xs">
                                    <span class="route-origin"><?= htmlspecialchars($m['area_origen'] ?? '—') ?></span>
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="route-arrow"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                                    <span class="route-dest"><?= htmlspecialchars($m['area_destino'] ?? '—') ?></span>
                                </td>
                                <td class="text-xs text-muted-sm"><?= htmlspecialchars($m['usuario'] ?? '') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</main>

<!-- ── Scripts ──────────────────────────────────────────────── -->
<script src="<?= $base_url ?>/js/chart.umd.min.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Esperar a que Chart.js esté disponible (cargado con defer)
    function initCharts() {
        if (typeof Chart === 'undefined') {
            setTimeout(initCharts, 100);
            return;
        }

    /* ── Colores de estados (mismos que los badges) ── */
    const estadoLabels = <?= json_encode(array_column($bienesPorEstado, 'nombre')) ?>;
    const estadoData   = <?= json_encode(array_column($bienesPorEstado, 'cantidad')) ?>;
    const estadoColors = <?= json_encode(array_column($bienesPorEstado, 'color')) ?>;
    const totalBienes  = <?= (int)$metrics['total'] ?>;

    /* ── Gráfico de barras: Distribución por Estado ── */
    const ctxBar = document.getElementById('chartEstadosBar');
    if (ctxBar) {
        new Chart(ctxBar.getContext('2d'), {
            type: 'bar',
            data: {
                labels: estadoLabels,
                datasets: [{
                    label: 'Cantidad',
                    data: estadoData,
                    backgroundColor: estadoColors.map(c => c + 'cc'),
                    hoverBackgroundColor: estadoColors,
                    borderRadius: 8,
                    borderSkipped: false,
                    barPercentage: 0.6,
                    categoryPercentage: 0.8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 800,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15,23,42,0.92)',
                        titleFont: { size: 13, weight: '600' },
                        bodyFont: { size: 12 },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: true,
                        boxPadding: 4,
                        callbacks: {
                            label: function(ctx) {
                                const val = ctx.parsed.y;
                                const pct = totalBienes > 0 ? ((val / totalBienes) * 100).toFixed(1) : 0;
                                return ' ' + val + ' bienes  ·  ' + pct + '%';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        border: { display: false },
                        ticks: {
                            font: { size: 11, family: 'inherit' },
                            color: '#94a3b8',
                            stepSize: 1,
                            padding: 8
                        },
                        grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false }
                    },
                    x: {
                        border: { display: false },
                        ticks: {
                            font: { size: 11, family: 'inherit' },
                            color: '#64748b',
                            padding: 8
                        },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    /* ── Gráfico de barras apiladas: Composición por Área ── */
    const ctxAreaStacked = document.getElementById('chartAreaStacked');
    if (ctxAreaStacked) {
        const areaData = <?= json_encode($bienesPorArea) ?>;
        const areaLabels = areaData.map(a => a.nombre_area.length > 18 ? a.nombre_area.substring(0, 16) + '…' : a.nombre_area);
        new Chart(ctxAreaStacked.getContext('2d'), {
            type: 'bar',
            data: {
                labels: areaLabels,
                datasets: [
                    { label: 'Operativos',    data: areaData.map(a => +a.operativos),        backgroundColor: '#10b981', borderRadius: {topLeft: 4, topRight: 4}, borderSkipped: false },
                    { label: 'Inoperativos',  data: areaData.map(a => +a.inoperativos),      backgroundColor: '#f59e0b', borderSkipped: false },
                    { label: 'Resguardo',     data: areaData.map(a => +a.resguardo),         backgroundColor: '#06b6d4', borderSkipped: false },
                    { label: 'Chatarra',      data: areaData.map(a => +a.chatarra),          backgroundColor: '#ef4444', borderSkipped: false },
                    { label: 'Desincorp.',    data: areaData.map(a => +a.desincorporados),   backgroundColor: '#94a3b8', borderRadius: {bottomLeft: 4, bottomRight: 4}, borderSkipped: false }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 900, easing: 'easeOutQuart' },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 14, font: { size: 11, family: 'inherit' }, usePointStyle: true, pointStyleWidth: 10 }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15,23,42,0.92)',
                        titleFont: { size: 13, weight: '600' },
                        bodyFont: { size: 12 },
                        padding: 12, cornerRadius: 8,
                        callbacks: {
                            label: function(ctx) {
                                const val = ctx.parsed.y;
                                const idx = ctx.dataIndex;
                                const total = areaData[idx].total;
                                const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                return ' ' + ctx.dataset.label + ': ' + val + ' (' + pct + '%)';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        stacked: true, border: { display: false },
                        ticks: { font: { size: 10, family: 'inherit' }, color: '#64748b', maxRotation: 45, minRotation: 0 }
                    },
                    y: {
                        stacked: true, beginAtZero: true, border: { display: false },
                        ticks: { font: { size: 11, family: 'inherit' }, color: '#94a3b8', stepSize: 1, padding: 8 },
                        grid: { color: 'rgba(0,0,0,0.04)' }
                    }
                }
            }
        });
    }

    /* ── Gráfico de doughnut: Bienes por Edificio ── */
    const edificioLabels = <?= json_encode(array_column($bienesPorEdificio, 'edificio')) ?>;
    const edificioData   = <?= json_encode(array_column($bienesPorEdificio, 'cantidad')) ?>;
    const edificioColors = ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#06b6d4','#84cc16'];
    const ctxEdificio = document.getElementById('chartEdificioPie');
    if (ctxEdificio && edificioLabels.length) {
        new Chart(ctxEdificio.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: edificioLabels,
                datasets: [{
                    data: edificioData,
                    backgroundColor: edificioColors.slice(0, edificioLabels.length),
                    hoverBackgroundColor: edificioColors.slice(0, edificioLabels.length).map(c => c + 'dd'),
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '55%',
                animation: { duration: 900, easing: 'easeOutQuart' },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 14, font: { size: 11, family: 'inherit' }, usePointStyle: true, pointStyleWidth: 10 }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15,23,42,0.92)',
                        titleFont: { size: 13, weight: '600' },
                        bodyFont: { size: 12 },
                        padding: 12, cornerRadius: 8,
                        callbacks: {
                            label: function(ctx) {
                                const val = ctx.parsed;
                                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                return ' ' + ctx.label + ': ' + val + ' (' + pct + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    initCharts();
});
</script>

<style>
/* ═══════════════════════════════════════════════════════════
   DASHBOARD – Estilos específicos
   ═══════════════════════════════════════════════════════════ */

/* ── Header ── */
.dash-subtitle {
    font-size: 0.85rem;
    color: var(--text-muted);
    margin-top: 0.2rem;
}
.dash-datetime {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.8rem;
    color: var(--text-muted);
    background: var(--gray-50);
    padding: 0.4rem 0.75rem;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border);
}

/* ── Secciones ── */
.dash-section {
    margin-bottom: 1.5rem;
}
.dash-section:last-child {
    margin-bottom: 0;
}

/* ── Grid de charts (2 columnas) ── */
.dash-charts {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}
@media (max-width: 1100px) {
    .dash-charts { grid-template-columns: 1fr; }
}

/* ── Grid de tablas (2 columnas) ── */
.dash-tables {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}
@media (max-width: 1200px) {
    .dash-tables { grid-template-columns: 1fr; }
}

/* ── Cards ── */
.dash-card {
    display: flex;
    flex-direction: column;
}
.dash-card .card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
}
.dash-card .table-wrapper {
    flex: 1;
}

/* ── Card header icon ── */
.card-header-icon {
    opacity: 0.5;
    margin-right: 0.4rem;
    vertical-align: -2px;
}

/* ── Botón ghost (Ver todos) ── */
.btn-ghost {
    background: transparent;
    border: 1px solid var(--border);
    color: var(--text-muted);
    font-size: 0.78rem;
    padding: 0.3rem 0.7rem;
    border-radius: var(--radius-sm);
    transition: all var(--transition);
}
.btn-ghost:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
    color: var(--accent);
}

/* ═══════════════════════════════════════════════════════════
   MÉTRICAS – Mejoras visuales
   ═══════════════════════════════════════════════════════════ */

.metrics-grid {
    grid-template-columns: repeat(4, 1fr);
}
@media (max-width: 1100px) {
    .metrics-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 600px) {
    .metrics-grid { grid-template-columns: 1fr; }
}

.metric-card {
    position: relative;
    padding: 1.1rem 1.2rem !important;
    overflow: hidden;
    transition: all var(--transition);
}
.metric-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}
.metric-card--total {
    border-left: 3px solid var(--accent);
}

.metric-info {
    display: flex;
    flex-direction: column;
    flex: 1;
    min-width: 0;
}
.metric-value {
    font-size: 1.7rem !important;
    font-weight: 700;
    line-height: 1.2;
    color: var(--text-main);
    margin-bottom: 0.15rem !important;
}
.metric-label {
    font-size: 0.78rem !important;
    color: var(--text-muted);
    font-weight: 500;
}

/* Badge de porcentaje en métricas */
.metric-badge {
    position: absolute;
    top: 0.7rem;
    right: 0.8rem;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.15rem 0.5rem;
    border-radius: 999px;
    letter-spacing: 0.02em;
}
.metric-badge--green  { background: rgba(16,185,129,0.1); color: #059669; }
.metric-badge--yellow { background: rgba(245,158,11,0.1); color: #d97706; }
.metric-badge--teal   { background: rgba(6,182,212,0.1);  color: #0891b2; }
.metric-badge--red    { background: rgba(239,68,68,0.1);  color: #dc2626; }
.metric-badge--gray   { background: rgba(100,116,139,0.1); color: #475569; }

/* Trend indicator */
.metric-trend {
    position: absolute;
    top: 0.7rem;
    right: 0.8rem;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.metric-trend--neutral {
    background: rgba(59,130,246,0.1);
    color: var(--accent);
}

/* ═══════════════════════════════════════════════════════════
   DISTRIBUCIÓN POR EDIFICIO – Barras horizontales
   ═══════════════════════════════════════════════════════════ */

.dist-bars {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    flex: 1;
}
.dist-row {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}
.dist-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.dist-name {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--gray-700);
}
.dist-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
    box-shadow: 0 0 0 3px rgba(0,0,0,0.05);
}
.dist-stats {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: var(--gray-600);
}
.dist-pct {
    font-size: 0.75rem;
    color: var(--gray-400);
    min-width: 3rem;
    text-align: right;
}
.dist-track {
    height: 8px;
    background: var(--gray-100);
    border-radius: 999px;
    overflow: hidden;
}
.dist-fill {
    height: 100%;
    border-radius: 999px;
    transition: width 0.8s cubic-bezier(0.33, 1, 0.68, 1);
    min-width: 2px;
}

/* ═══════════════════════════════════════════════════════════
   TABLAS – Mejoras
   ═══════════════════════════════════════════════════════════ */

.table-link {
    color: var(--accent);
    text-decoration: none;
    font-weight: 500;
    transition: color var(--transition);
}
.table-link:hover {
    color: var(--accent-hover);
    text-decoration: underline;
}

.route-origin {
    color: var(--text-muted);
}
.route-dest {
    color: var(--gray-700);
    font-weight: 500;
}
.route-arrow {
    vertical-align: -2px;
    color: var(--gray-300);
    margin: 0 0.2rem;
}

.text-muted-sm {
    font-size: 0.8rem;
    color: var(--gray-400);
}

/* Empty states */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2.5rem 1rem;
    color: var(--gray-400);
    gap: 0.5rem;
}
.empty-state p {
    font-size: 0.85rem;
}
.empty-state--inline {
    padding: 1.5rem 1rem;
}
.empty-state--inline p {
    font-size: 0.8rem;
}

/* ═══════════════════════════════════════════════════════════
   ANIMACIONES
   ═══════════════════════════════════════════════════════════ */

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}
.dash-section {
    animation: fadeInUp 0.4s ease-out both;
}
.dash-section:nth-child(2) { animation-delay: 0.05s; }
.dash-section:nth-child(3) { animation-delay: 0.10s; }
.dash-section:nth-child(4) { animation-delay: 0.15s; }

/* ═══════════════════════════════════════════════════════════
   SECCIÓN DE ANÁLISIS POR ÁREA
   ═══════════════════════════════════════════════════════════ */

.section-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
}
.section-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-main);
    margin: 0;
}
.section-title svg {
    color: var(--accent);
}
.section-subtitle {
    font-size: 0.82rem;
    color: var(--text-muted);
    padding-left: 0.75rem;
    border-left: 2px solid var(--border);
}

/* Grid 3 columnas para analytics */
.dash-analytics {
    display: grid;
    grid-template-columns: 1fr 1.4fr 1fr;
    gap: 1.5rem;
}
@media (max-width: 1200px) {
    .dash-analytics { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 900px) {
    .dash-analytics { grid-template-columns: 1fr; }
}

/* Card badges */
.card-badge {
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.2rem 0.55rem;
    border-radius: 999px;
    background: var(--gray-100);
    color: var(--gray-500);
}
.card-badge--red {
    background: rgba(239,68,68,0.1);
    color: #dc2626;
}

/* ── Area Health List ── */
.area-health-list {
    display: flex;
    flex-direction: column;
    gap: 0.7rem;
}
.area-health-row {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.6rem 0.75rem;
    border-radius: var(--radius-sm);
    background: var(--gray-50);
    transition: background var(--transition);
}
.area-health-row:hover {
    background: var(--gray-100);
}
.area-health-info {
    display: flex;
    flex-direction: column;
    min-width: 120px;
    flex-shrink: 0;
}
.area-health-name {
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--gray-700);
    line-height: 1.2;
}
.area-health-edificio {
    font-size: 0.72rem;
    color: var(--gray-400);
}
.area-health-bar-wrap {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex: 1;
    min-width: 0;
}
.area-health-bar {
    flex: 1;
    height: 7px;
    background: var(--gray-200);
    border-radius: 999px;
    overflow: hidden;
}
.area-health-fill {
    height: 100%;
    border-radius: 999px;
    transition: width 0.8s cubic-bezier(0.33,1,0.68,1);
}
.area-health-pct {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--gray-600);
    min-width: 2.8rem;
    text-align: right;
}
.area-health-badge {
    font-size: 0.68rem;
    font-weight: 700;
    padding: 0.2rem 0.5rem;
    border-radius: 999px;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    flex-shrink: 0;
    min-width: 52px;
    text-align: center;
}

/* ── Alert Table ── */
.card-header--alert {
    border-bottom-color: rgba(239,68,68,0.15) !important;
}
.alert-table {
    display: flex;
    flex-direction: column;
}
.alert-table-head {
    display: grid;
    grid-template-columns: 1fr 50px 100px 50px;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    font-size: 0.7rem;
    font-weight: 600;
    color: var(--gray-400);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid var(--border);
}
.alert-table-row {
    display: grid;
    grid-template-columns: 1fr 50px 100px 50px;
    gap: 0.5rem;
    padding: 0.6rem 0.75rem;
    align-items: center;
    border-bottom: 1px solid var(--gray-100);
    transition: background var(--transition);
}
.alert-table-row:last-child { border-bottom: none; }
.alert-table-row:hover { background: var(--gray-50); }
.alert-table-area {
    display: flex;
    flex-direction: column;
    min-width: 0;
}
.alert-table-name {
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--gray-700);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.alert-table-edificio {
    font-size: 0.7rem;
    color: var(--gray-400);
}
.alert-table-total {
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--gray-600);
    text-align: center;
}
.alert-table-detail {
    display: flex;
    gap: 0.3rem;
    flex-wrap: wrap;
}
.alert-tag {
    font-size: 0.68rem;
    font-weight: 600;
    padding: 0.15rem 0.4rem;
    border-radius: 4px;
}
.alert-tag--yellow { background: rgba(245,158,11,0.12); color: #d97706; }
.alert-tag--red    { background: rgba(239,68,68,0.12);  color: #dc2626; }
.alert-table-pct {
    font-size: 0.8rem;
    font-weight: 700;
    text-align: right;
}
.alert-table-pct--warn  { color: #d97706; }
.alert-table-pct--danger { color: #dc2626; }
</style>
