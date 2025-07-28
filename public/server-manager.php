<?php
    require_once __DIR__ . '/../config/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP Server Manager</title>
    <script>
        function stopServer(pid) {
            fetch('<?=getBaseUrl()?>stop-server-ajax.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'pid=' + pid
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                loadServerList(); // Refresh after stopping
            });
        }

        function loadServerList() {
            fetch('<?=getBaseUrl()?>server-list.php')
            .then(res => res.text())
            .then(html => {
                document.getElementById('serverList').innerHTML = html;
            });
        }

        function filterByPort() {
            const port = document.getElementById('portFilter').value;
            document.querySelectorAll('.serverItem').forEach(item => {
                item.style.display = item.dataset.port.includes(port) ? 'block' : 'none';
            });
        }

        setInterval(loadServerList, 5000); // Auto-refresh every 5 seconds
        window.onload = loadServerList;
    </script>
</head>
<body>
    <h2>Active PHP Servers</h2>
    <input type="text" id="portFilter" placeholder="Filter by Port" onkeyup="filterByPort()" />
    <div id="serverList"></div>
</body>
</html>
