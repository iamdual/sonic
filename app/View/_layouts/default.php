<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title><?= $title ?? ""; ?></title>
    <style>
        html, body {
            margin: 10px;
            padding: 0;
            background: #f2f7fd;
            color: #000;
            font-family: sans-serif;
        }
    </style>
</head>
<body>
<?php require $view; ?>
</body>
</html>