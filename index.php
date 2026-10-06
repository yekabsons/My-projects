<?php
$folders = array_filter(glob('*'), function($item) {
    return is_dir($item) && $item !== "assets";
});
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>LBTU WWW Darbi | Ervīns Jēkabsons</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
</head>
<body>

<section class="hero">
    <div class="hero-content">
        <h1>LBTU WWW Darbi</h1>
        <p>Ervīns Jēkabsons | Web Developer</p>
    </div>
</section>

<section class="projects">
    <h2>Mani Projekti</h2>
    <div class="container">
        <?php foreach($folders as $folder): ?>
            <a href="<?php echo $folder; ?>" class="card">
                <h3><?php echo ucfirst(str_replace("_"," ",$folder)); ?></h3>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<footer>
    © <?php echo date("Y"); ?> Ervīns Jēkabsons
</footer>
</body>
</html>
