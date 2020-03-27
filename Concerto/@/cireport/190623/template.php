<?php
header("Content-type:text/html; charset=UTF-8");

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?= $title; ?></title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css">
    <style>
        .my_chart {
            
        }
        
        .my_chart canvas {
            width:600px;
            height:400px;
            
            
        }
        
        
        
    </style>
</head>
<body>
    <div id="container">
        <?php foreach ($aaa as $bb) : ?>
        <div class="my_chart">
            <h4><?= $aaa; ?></h4>
            <canvas id="myChart_<?= $bbb; ?>"></canvas>
        </div>
        
        <?php endforeach; ?>
        
    </div>
    
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js">
    
    <script>
        
        
        
        
        
        
    </script>
</body>
</html>
