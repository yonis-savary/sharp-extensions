<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= style("assets-kit/style.css") ?>
    <title><?= $title ?? "Document" ?></title>
</head>
<body>
    <?= function_exists("assetsKitJSBundle") ? assetsKitJSBundle(): "" ?>
    <?= render("navbar") ?>
    <?= section("body") ?>
</body>
<?= section("style") ?>
<?= section("scripts") ?>
</html>