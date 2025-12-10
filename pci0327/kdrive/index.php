<?php
require_once './includes/db.php';

$db = new DatabaseConnection();
$pdo = $db->pdo;

$stmt = $pdo->query("SELECT * FROM checklist");
$rows = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>보안 점검표</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            padding: 40px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px 16px;
            text-align: left;
        }

        th {
            background-color: #4a6fa5;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead {
                display: none;
            }

            tr {
                margin-bottom: 15px;
                border: 1px solid #ccc;
                padding: 10px;
            }

            td {
                padding-left: 50%;
                position: relative;
                text-align: left;
            }

            td::before {
                position: absolute;
                left: 10px;
                top: 12px;
                white-space: nowrap;
                font-weight: bold;
            }

            td:nth-of-type(1)::before { content: "구분"; }
            td:nth-of-type(2)::before { content: "유형"; }
            td:nth-of-type(3)::before { content: "점검 항목"; }
            td:nth-of-type(4)::before { content: "점검 결과"; }
        }
    </style>
</head>
<body>

<h1>보안 점검표</h1>

<table>
    <thead>
        <tr>
            <th>구분</th>
            <th>유형</th>
            <th>점검 항목</th>
            <th>점검 결과</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['system_area']) ?></td>
            <td><?= htmlspecialchars($row['category']) ?></td>
            <td><?= nl2br(htmlspecialchars($row['item'])) ?></td>
            <td><?= htmlspecialchars($row['result']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
