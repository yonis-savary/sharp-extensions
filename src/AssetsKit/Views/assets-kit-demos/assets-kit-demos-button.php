<a class="svg-link" overlay="buttonOverlay">Learn buttons <?= svg("arrow-right-short") ?></a>

<section class="overlay" id="buttonOverlay">
    <section class="content flex-column">
        <section class="flex-column">
            <textarea disabled><button class="button COLOR">some test</button></textarea>
            <section class="flex-column scrollable gap-0 vh-30">
                <?php foreach (ASSETS_KIT_COLORS as $color) { buttonSection($color); }?>
            </section>
            <textarea disabled><button class="button secondary COLOR">some test</button></textarea>
            <section class="flex-column scrollable gap-0 vh-30">
                <?php foreach (ASSETS_KIT_COLORS as $color) { buttonSection($color, "secondary"); }?>
            </section>
            <textarea disabled><button class="button tertiary COLOR">some test</button></textarea>
            <section class="flex-column scrollable gap-0 vh-30">
                <?php foreach (ASSETS_KIT_COLORS as $color) { buttonSection($color, "tertiary"); }?>
            </section>
            <textarea disabled><button class="button disabled COLOR">some test</button></textarea>
            <section class="flex-column scrollable gap-0 vh-30">
                <?php foreach (ASSETS_KIT_COLORS as $color) { buttonSection($color, "", "disabled"); }?>
            </section>
        </section>
        <section class="flex-column gap-0">
            <textarea disabled><button class="button icon COLOR">some test</button></textarea>
            <textarea disabled><button class="button icon secondary COLOR">some test</button></textarea>
            <textarea disabled><button class="button icon tertiary COLOR">some test</button></textarea>
        </section>
        <section class="flex-row flex-wrap">
            <?php foreach (ASSETS_KIT_COLORS as $color) { ?>
                <button title="<?= $color ?>" class="button icon <?= $color ?>"><?= svg("check") ?></button>
                <button title="<?= $color ?>" class="button icon secondary <?= $color ?>"><?= svg("check") ?></button>
                <button title="<?= $color ?>" class="button icon tertiary <?= $color ?>"><?= svg("check") ?></button>
            <?php }?>
        </section>
        <section class="flex-column">
            <button class="button green"><?= svg("person") ?> Button with SVG!</button>
        </section>
    </section>
</section>