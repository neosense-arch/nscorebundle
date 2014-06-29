<?php

set_time_limit(0);

// retrieving file list
$files = array();
foreach (glob(__DIR__ . '/*.tar.gz') as $fileName) {
    $files[] = array(
        'brief' => basename($fileName),
        'full'  => $fileName,
    );
}

// restoring
if (isset($_GET['fileName'])) {
    $idx = (int)$_GET['fileName'];
    $fileName = $files[$idx]['full'];
    $cwd = __DIR__;
    $cmd = "cd $cwd && tar -xzvf $fileName";
    exec($cmd, $output ,$return);
    @unlink(__FILE__);
    header("Location: /");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Восстановление</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://yandex.st/bootstrap/3.1.1/css/bootstrap.min.css">
    <style type="text/css">
        html, body {
        }
        .main {
            border: solid 10px #f5f5f5;
            border-radius: 30px;
            margin: 70px auto;
            padding: 30px;
            width: 700px;
        }
        .main h1 {
            margin-top: 0;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="main">
        <h1>Восстановление системы</h1>

        <?php if (isset($_GET['fileName'])): ?>
            <pre><?php
                    $idx = (int)$_GET['fileName'];
                    $fileName = $files[$idx]['full'];
                    $cwd = __DIR__;
                    $cmd = "cd $cwd && tar -xzvf $fileName";
                    echo "$ {$cmd}\n\n";
                    exec($cmd, $output ,$return);

                    unlink(__FILE__);

                ?>
            </pre>
        <? else: ?>
            <p>Выберите файл для восставления:</p>
            <form method="get">
                <select name="fileName">
                    <?php foreach ($files as $i => $file): ?>
                        <option value="<?php echo $i ?>"><?php echo htmlspecialchars($file['brief'])?></option>
                    <?php endforeach ?>
                </select>
                <div class="row" style="margin-top: 20px;">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Восстановить</button>
                </div>
            </form>
        <?php endif ?>
    </div>
</div>
</body>

