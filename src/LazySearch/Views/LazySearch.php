<?php

    use YonisSavary\Sharp\Classes\Extras\AssetServer;
    use YonisSavary\Sharp\Classes\Web\Renderer;
    use YonisSavary\Sharp\Extensions\LazySearch\Classes\LazySearch;

    $asset = fn(string $target) => AssetServer::getInstance()->getURL($target);

    foreach (($views ?? []) as $v)
    {
        if (Renderer::getInstance()->templateExists($v))
            echo (new Renderer())->render($v);
    }

    foreach (($scripts ?? []) as $s)
        echo "<script src='".$asset($s)."'></script>"

?>

<section
    class="lazySearch"
    <?= (isset($id) && $id) ? "id='$id'" : "" ?>
    url="<?= $url ?? '' ?>"
    <?= $attr ?? $attributes ?? '' ?>
></section>

<?php
    if (!defined("LAZYSEARCH_IMPORTED_ASSETS")) {
        define("LAZYSEARCH_IMPORTED_ASSETS", 1);
?>
    <script>
        const LAZYSEARCH_CONFIGURATION = <?= json_encode(LazySearch::getInstance()->getConfiguration()) ?>;
        const LAZYSEARCH_SETTINGS = <?= json_encode($backendOptions ?? null) ?>;
    </script>

    <!-- LazySearch Core - only has to be imported once -->
    <link rel="stylesheet" href="<?= $asset("/css/LazySearch.css") ?>">
    <script src="<?= $asset("/js/LazySearch.js") ?>"></script>
<?php } ?>