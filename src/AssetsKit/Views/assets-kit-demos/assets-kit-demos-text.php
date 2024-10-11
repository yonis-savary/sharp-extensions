<a class="svg-link" overlay="textOverlay">Learn Text Elements <?= svg("arrow-right-short") ?></a>

<section class="overlay" id="textOverlay">
    <section class="flex-column content">

        <section class="bg-DodgerBlue fg-White">
            <b>Text</b>
        </section>
        <section class="flex-column">
            <h1>Title H1</h1>
            <h2>Title H2</h2>
            <h3>Title H3</h3>
            <h4>Title H4</h4>
            <h5>Title H5</h5>
            <p>Paragraph</p>
            <span class="f1">Small F1</span>
            <span class="f2">Small F2</span>
            <span class="f3">Small F3</span>
            <span class="f4">Small F4</span>
            <span class="f5">Small F5</span>
        </section>
        <section class="flex-column">
            <ul>
                <li>Element A</li>
                <li>Element B</li>
                <li>Element C</li>
            </ul>
            <ol>
                <li>Element A</li>
                <li>Element B</li>
                <li>Element C</li>
            </ol>

            <span class="underline">underline text !</span>
            <span class="line-through">line-through text !</span>
        </section>
        <section>
            <b>Some details</b>
        </section>
        <section class="flex-column gap-0 scrollable vh-50">
            <?php foreach (ASSETS_KIT_COLORS as $color) {
                wrapSample("
                <details class='$color'>
                    <summary>I'm a $color details</summary>
                    <p> And so it goes ! </p>
                </details>
                ");
            }?>
        </section>
        <section>
            <b>Some Infos</b>
        </section>
        <section class="flex-column gap-0 scrollable max-vh-50">
            <?php foreach (ASSETS_KIT_COLORS as $color) {
                wrapSample("
                <section class='info $color'>
                    <b>$color infos</b>
                    <p>I'm an extra, and so it goes !</p>
                </section>
                ");
            }?>
        </section>
        <section class="info-blue">
            <b>Info blue in card !</b>
        </section>
        <section>
            <b>Some description list</b>
        </section>
        <section class="flex-column">
            <dl>
                <dt>Some title</dt>
                <dd>Some content</dd>
                <dt>Some title</dt>
                <dd><input class="subtle" placeholder="Hover me !" type="text"></dd>
            </dl>
        </section>
        <section class="flex-row">
            <section class="notification red">
                Your notifications
                <div class="number">12</div>
            </section>
            <section class="notification orange">
                Your notifications
                <div class="number">12</div>
            </section>
            <section class="notification green">
                Your notifications
                <div class="number">12</div>
            </section>
        </section>
        <section>
            <b>Some Chips</b>
        </section>
        <section class="flex-column gap-0 scrollable vh-50">
            <?php foreach (ASSETS_KIT_COLORS as $color) {
                wrapSample("
                <section class='flex-row align-center flex-wrap'>
                    <button class='chip $color'> ". svg("check") . "</button>
                    <button class='tag clickable $color'> $color</button>
                    <button class='badge clickable $color'> $color</button>
                </section>
                ");
            }?>
        </section>
    </section>
</section>
