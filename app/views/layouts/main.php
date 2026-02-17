<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'BNGRC - Gestion des Dons') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= BASE_URL ?>/dashboard">
                <i class="bi bi-heart-pulse-fill me-2"></i>BNGRC
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link">
                            <i class="bi bi-calendar3 me-1"></i>
                            <?= date('d/m/Y') ?>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar collapse d-md-block">
            <div class="position-sticky pt-3">
                    <div class="sidebar-heading">Navigation</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>/dashboard">
                                <i class="bi bi-speedometer2"></i>Tableau de bord
                            </a>
                        </li>
                    </ul>
                    
                    <div class="sidebar-heading">Gestion</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/besoins') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>/besoins">
                                <i class="bi bi-clipboard-check"></i>Besoins
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/dons') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>/dons">
                                <i class="bi bi-gift"></i>Dons
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/achats') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>/achats">
                                <i class="bi bi-cart-check"></i>Achats
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/dispatch') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>/dispatch">
                                <i class="bi bi-arrow-left-right"></i>Dispatch
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/recap') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>/recap">
                                <i class="bi bi-bar-chart"></i>Récapitulation
                            </a>
                        </li>
                    </ul>
                    
                    <div class="sidebar-heading">Configuration</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/regions') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>/regions">
                                <i class="bi bi-map"></i>Régions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/villes') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>/villes">
                                <i class="bi bi-building"></i>Villes
                            </a>
                        </li>
                    </ul>
            </div>
        </nav>

        <!-- Main content -->
        <main class="flex-grow-1 main-content">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        <i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($_SESSION['success']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['info'])): ?>
                    <div class="alert alert-info alert-dismissible fade show mt-3" role="alert">
                        <i class="bi bi-info-circle me-2"></i><?= htmlspecialchars($_SESSION['info']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['info']); ?>
                <?php endif; ?>

                <?= $content ?>

                <!-- Footer -->
                <footer class="site-footer mt-5">
                    <div class="footer-top">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="footer-brand">
                                    <i class="bi bi-heart-pulse-fill"></i>
                                    <span>BNGRC</span>
                                </div>
                                <p class="footer-desc">
                                    Bureau National de Gestion des Risques et des Catastrophes — 
                                    Système de gestion et de distribution des dons pour les populations sinistrées de Madagascar.
                                </p>
                            </div>
                            <div class="col-md-4">
                                <h6 class="footer-heading">Liens rapides</h6>
                                <ul class="footer-links">
                                    <li><a href="<?= BASE_URL ?>/dashboard"><i class="bi bi-speedometer2"></i>Tableau de bord</a></li>
                                    <li><a href="<?= BASE_URL ?>/besoins"><i class="bi bi-clipboard-check"></i>Besoins</a></li>
                                    <li><a href="<?= BASE_URL ?>/dons"><i class="bi bi-gift"></i>Dons</a></li>
                                    <li><a href="<?= BASE_URL ?>/dispatch"><i class="bi bi-arrow-left-right"></i>Dispatch</a></li>
                                    <li><a href="<?= BASE_URL ?>/recap"><i class="bi bi-bar-chart"></i>Récapitulation</a></li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h6 class="footer-heading">Contact & Équipe</h6>
                                <ul class="footer-contact">
                                    <li><i class="bi bi-geo-alt-fill"></i>Antananarivo, Madagascar</li>
                                    <li><i class="bi bi-envelope-fill"></i>contact@bngrc.mg</li>
                                    <li><i class="bi bi-telephone-fill"></i>+261 20 22 211 02</li>
                                </ul>
                                <div class="footer-team">
                                    <span class="footer-team-label">Développé par :</span>
                                    <div class="footer-badges">
                                        <span class="footer-badge">ETU004248</span>
                                        <span class="footer-badge">ETU004310</span>
                                        <span class="footer-badge">ETU004312</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="footer-bottom">
                        <span>&copy; <?= date('Y') ?> BNGRC — Tous droits réservés</span>
                        <span class="footer-separator">|</span>
                        <span>Projet Final S3 — Université de Madagascar</span>
                    </div>
                </footer>
            </main>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Format des nombres
        function formatNumber(num) {
            return new Intl.NumberFormat('fr-FR').format(num);
        }
    </script>
</body>
</html>
