<?php

// var_dump($_SERVER);

$settings = require __DIR__ . '/../config/settings.php';
require_once __DIR__ . '/../src/Launcher.php';
require_once __DIR__ . '/../config/config.php';


$roots = $settings['roots'];
$versions = $settings['phpVersions'];
$projects = [];
$url = null;
$selectedRoot = $_POST['root'] ?? null;

if ($selectedRoot && isset($roots[$selectedRoot])) {
    $projects = Launcher::scanProjects($roots[$selectedRoot]);
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Project Toolkit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <h3 class="text-center mb-4">🧰 PHP Project Toolkit</h3>
        <nav>
            <ul class="nav justify-content-center mb-4">
                <li class="nav-item">
                    <a class="nav-link" href="<?= getBaseUrl() ?>index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" target="_blank" href="<?= getBaseUrl() ?>server-manager.php">Server Manager</a>
                </li>
            </ul>
        </nav>
        <div class="card shadow-sm">
            <div class="card-body">

                <form id="launchForm" method="POST">
                    <!-- Root Folder Selector -->
                    <div class="mb-3">
                        <label class="form-label">📂 Root Directory:</label>
                        <select class="form-select" name="root" onchange="this.form.submit()" required>
                            <option value="">Select Root</option>
                            <?php foreach ($roots as $key => $path): ?>
                                <option value="<?= $key ?>" <?= $selectedRoot === $key ? 'selected' : '' ?>>
                                    <?= $key ?> (<?= $path ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Project Folder Selector -->
                    <?php if ($projects): ?>
                        <div class="mb-3">
                            <label class="form-label">📁 Project Folder:</label>
                            <select class="form-select" name="project" required>
                                <option value="">Select Project</option>
                                <?php foreach ($projects as $folder): ?>
                                    <option value="<?= $folder ?>"><?= $folder ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- PHP Version Selector -->
                        <div class="mb-3">
                            <label class="form-label">⚙️ PHP Version:</label>
                            <select class="form-select" name="php" required>
                                <option value="">Select PHP Version</option>
                                <?php foreach ($versions as $label => $path): ?>
                                    <option value="<?= $label ?>"><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">🚀 Launch Project</button>
                    <?php endif; ?>
                </form>
                <div id="launchMessage">
                    <p>Project Details</p>
                    <ul>
                        <li><strong>Root:</strong> <span id="rootDetail"></span></li>
                        <li><strong>Project:</strong> <span id="projectDetail"></span></li>
                        <li><strong>PHP Version:</strong> <span id="phpDetail"></span></li>
                        <li><strong>Launch URL:</strong> <span id="launchUrlDetail"></span></li>
                    </ul>
                </div>

                <?php if ($url): ?>
                    <div class="alert alert-success mt-4">
                        Project launched! <a href="<?= $url ?>" target="_blank"><?= $url ?></a>
                    </div>
                    <script>
                        window.open('<?= $url ?>', '_blank');
                    </script>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('launchForm').addEventListener('submit', function(e) {
            e.preventDefault();

            fetch('<?= getBaseUrl() ?>launch.php', {
                    method: 'POST',
                    body: new FormData(this)
                })
                .then(res => res.json())
                .then(data => {
                    console.log('Got:', data.url);
                    document.getElementById('rootDetail').innerHTML = data.root;
                    document.getElementById('projectDetail').innerHTML = data.project;
                    document.getElementById('phpDetail').innerHTML = data.php;
                    if (data.url) {
                        document.getElementById('launchUrlDetail').innerHTML = `<div class="alert alert-success mt-4">
                            Project launched! <a href="${data.url}" target="_blank">${data.url}</a>
                        </div>`;
                    } else {
                        document.getElementById('launchUrlDetail').innerHTML = `<div class="alert alert-danger mt-4">
                            Launch failed: ${data.error || 'Unknown error'}
                        </div>`;
                    }
                    // You can still open or display it
                });

        });
    </script>
</body>

</html>