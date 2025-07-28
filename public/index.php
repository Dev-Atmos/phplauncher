<?php
$settings = require __DIR__ . '/../config/settings.php';
require_once __DIR__ . '/../src/Launcher.php';

$roots = $settings['roots'];
$versions = $settings['phpVersions'];
$projects = [];
$url = null;
$selectedRoot = $_POST['root'] ?? null;

if ($selectedRoot && isset($roots[$selectedRoot])) {
    $projects = Launcher::scanProjects($roots[$selectedRoot]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['project'], $_POST['php'])) {
    $project = $_POST['project'];
    $phpExec = $versions[$_POST['php']] ?? null;
    $port = rand(8000, 8999); // Random port for simplicity
    $url = Launcher::launch($roots[$selectedRoot], $project, $phpExec, $port);
    echo "<script>window.open('$url', '_blank');</script>";
    exit;
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
        <div class="card shadow-sm">
            <div class="card-body">

                <form method="POST">
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
</body>

</html>