
<?php

$colors= [

    "primary",
    "white",
    "lightgray",
    "gray",
    "darkgray",
    "black",
    "red",
    "orange",
    "yellow",
    "green",
    "turquoise",
    "blue",
    "indigo",
    "violet",

    "Ivory",
    "Beige",
    "AntiqueWhite",
    "LemonChiffon",
    "LightGoldenRodYellow",
    "LightYellow",
    "Linen",
    "PaleGoldenRod",
    "Bisque",
    "BlanchedAlmond",
    "BurlyWood",
    "Khaki",
    "DarkKhaki",
    "Gold",
    "GoldenRod",
    "Yellow",
    "White",
    "Snow",
    "Azure",
    "WhiteSmoke",
    "Gainsboro",
    "LightGray",
    "LightGrey",
    "Silver",
    "DarkGray",
    "DarkGrey",
    "Gray",
    "Grey",
    "DimGray",
    "DimGrey",
    "Black",
    "HoneyDew",
    "FloralWhite",
    "MintCream",
    "YellowGreen",
    "LightGreen",
    "PaleGreen",
    "GreenYellow",
    "Chartreuse",
    "LawnGreen",
    "Lime",
    "LimeGreen",
    "MediumSpringGreen",
    "SpringGreen",
    "MediumAquaMarine",
    "MediumSeaGreen",
    "OliveDrab",
    "Olive",
    "DarkOliveGreen",
    "DarkSeaGreen",
    "Green",
    "DarkGreen",
    "DarkSlateGray",
    "DarkSlateGrey",
    "ForestGreen",
    "SeaGreen",
    "SeaShell",
    "LavenderBlush",
    "Cornsilk",
    "MistyRose",
    "Moccasin",
    "NavajoWhite",
    "OldLace",
    "PapayaWhip",
    "PeachPuff",
    "Wheat",
    "Coral",
    "DarkGoldenRod",
    "Chocolate",
    "DarkOrange",
    "Orange",
    "SandyBrown",
    "Peru",
    "DarkSalmon",
    "LightSalmon",
    "LightCoral",
    "IndianRed",
    "OrangeRed",
    "PaleVioletRed",
    "Tomato",
    "Crimson",
    "FireBrick",
    "Brown",
    "DarkRed",
    "Maroon",
    "Red",
    "Tan",
    "RosyBrown",
    "SaddleBrown",
    "Salmon",
    "Sienna",
    "Pink",
    "LightPink",
    "Plum",
    "Thistle",
    "HotPink",
    "Orchid",
    "Violet",
    "Magenta",
    "Fuchsia",
    "DeepPink",
    "MediumVioletRed",
    "DarkMagenta",
    "BlueViolet",
    "DarkOrchid",
    "DarkSlateBlue",
    "DarkViolet",
    "Indigo",
    "MediumOrchid",
    "MediumPurple",
    "MediumSlateBlue",
    "RebeccaPurple",
    "Purple",
    "GhostWhite",
    "AliceBlue",
    "Lavender",
    "LightBlue",
    "LightCyan",
    "LightSkyBlue",
    "LightSlateGray",
    "LightSlateGrey",
    "LightSteelBlue",
    "PaleTurquoise",
    "PowderBlue",
    "SkyBlue",
    "SlateGray",
    "SlateGrey",
    "Aquamarine",
    "CadetBlue",
    "CornflowerBlue",
    "Blue",
    "DarkBlue",
    "DarkTurquoise",
    "DeepSkyBlue",
    "DodgerBlue",
    "RoyalBlue",
    "MediumBlue",
    "MidnightBlue",
    "Navy",
    "SlateBlue",
    "MediumTurquoise",
    "Cyan",
    "Aqua",
    "SteelBlue",
    "DarkCyan",
    "Teal",
    "Turquoise",
    "LightSeaGreen",
];

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AssetsKit Demo</title>
</head>

<?= style("assets-kit/style.css", true) ?>
<?= assetsKitJSBundle(true) ?>

<body class="flex-column" style="--primary: #5757ff">

    <nav class="navbar bg-Crimson fg-White ">
        <h1>MyAPP</h1>
        <a href="">Link A</a>
        <a href="">Link B</a>
        <a href="">Link C</a>
        <a class="fill-left" href="">logout</a>
    </nav>
    <nav class="navbar bg-Moccasin fg-Black">
        <section class="content">
            <h1>MyAPP</h1>
            <a href="">Link A</a>
            <a href="">Link B</a>
            <a href="">Link C</a>
            <a class="fill-left" href="">logout</a>
        </section>
    </nav>


    <section class="flex-column flex-1 margin-top-10">

        <section class="centered">
            <section class="flex-row align-center justify-between">
                <h1>Hello !</h1>
                <section class="flex-row gap-0">
                    <input checked type="radio" class="tab" name="themeRadio" id="dayThemeButton">
                    <label for="dayThemeButton"><?= svg("sun") ?></label>
                    <input type="radio" class="tab" name="themeRadio" id="nightThemeButton">
                    <label for="nightThemeButton"><?= svg("moon") ?></label>
                </section>
            </section>
        </section>
        <script>
            nightThemeButton.addEventListener('change', ()=>{
                document.body.classList.remove("day-theme")
                document.body.classList.add("night-theme")
            });
            dayThemeButton.addEventListener('change', ()=>{
                document.body.classList.remove("night-theme")
                document.body.classList.add("day-theme")
            });
        </script>


        <section class="centered flex-column">

            <?php

                function wrapSample($sample)
                {
                    echo "
                    <section class='flex-row gap-0'>
                        <section class='bg-Black night-theme flex-column padding-2 flex-1'>$sample</section>
                        <section class='bg-White flex-column padding-2 flex-1'>$sample</section>
                    </section>";
                }

                function buttonSection($color, $extrasClass="", $extrasAttributes="")
                {
                    $button = "<button $extrasAttributes class='button $extrasClass $color'>$color</button>";
                    wrapSample($button);
                }
            ?>

            <section id="buttonSamples" class="card">
                <section class="bg-DodgerBlue fg-White">
                    <b>Buttons</b>
                </section>
                <section class="flex-column">
                    <textarea disabled><button class="button COLOR">some test</button></textarea>
                    <section class="flex-column scrollable gap-0 max-vh-30">
                        <?php foreach ($colors as $color) { buttonSection($color); }?>
                    </section>
                    <textarea disabled><button class="button secondary COLOR">some test</button></textarea>
                    <section class="flex-column scrollable gap-0 max-vh-30">
                        <?php foreach ($colors as $color) { buttonSection($color, "secondary"); }?>
                    </section>
                    <textarea disabled><button class="button tertiary COLOR">some test</button></textarea>
                    <section class="flex-column scrollable gap-0 max-vh-30">
                        <?php foreach ($colors as $color) { buttonSection($color, "tertiary"); }?>
                    </section>
                    <textarea disabled><button class="button disabled COLOR">some test</button></textarea>
                    <section class="flex-column scrollable gap-0 max-vh-30">
                        <?php foreach ($colors as $color) { buttonSection($color, "", "disabled"); }?>
                    </section>
                </section>
                <section class="flex-row flex-wrap">
                    <?php foreach ($colors as $color) { ?>
                        <button class="button icon <?= $color ?>"><?= svg("check") ?></button>
                    <?php }?>
                </section>
            </section>



            <section class="card">
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
                <section class="flex-column gap-0 scrollable max-vh-50">
                    <?php foreach ($colors as $color) {
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
                    <?php foreach ($colors as $color) {
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
                    <dl class="info">
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
                <section class="flex-column gap-0 scrollable max-vh-50">
                    <?php foreach ($colors as $color) {
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


            <section class="card">
                <section class="bg-DodgerBlue fg-White">
                    <b>Some Data !</b>
                </section>
                <section class="flex-row">
                    <table class="table">
                        <tr> <th>Col A</th> <th>Col B</th> <th>Col C</th> </tr>
                        <tr> <td>Val 1</td> <td>Val 2</td> <td>Val 3</td> </tr>
                        <tr> <td>Val 5</td> <td>Val 6</td> <td>Val 7</td> </tr>
                        <tr> <td>Val 7</td> <td>Val 8</td> <td>Val 9</td> </tr>
                    </table>
                    <table class="table stripped">
                        <tr> <th>Col A</th> <th>Col B</th> <th>Col C</th> </tr>
                        <tr> <td>Val 1</td> <td>Val 2</td> <td>Val 3</td> </tr>
                        <tr> <td>Val 5</td> <td>Val 6</td> <td>Val 7</td> </tr>
                        <tr> <td>Val 7</td> <td>Val 8</td> <td>Val 9</td> </tr>
                    </table>
                </section>
            </section>


            <section class="card">
                <section class="bg-DodgerBlue fg-White">
                    <b>Shadows</b>
                </section>
                <section class="flex-row justify-between">
                    <section class="card light-shadow">
                        <section>
                            <b>Light</b>
                        </section>
                    </section>
                    <section class="card medium-shadow">
                        <section>
                            <b>Medium</b>
                        </section>
                    </section>
                    <section class="card heavy-shadow">
                        <section>
                            <b>Heavy</b>
                        </section>
                    </section>
                </section>
            </section>





            <section class="card" id="form">
                <section class="bg-DodgerBlue fg-White">
                    <b>Form</b>
                </section>
                <section class="flex-column">
                    <label class="form-section">
                        <span>Dummy Input</span>
                        <input type="text">
                    </label>
                    <label class="form-section">
                        <span>Dummy Input</span>
                        <input class="subtle" placeholder="Hover here !" type="text">
                    </label>
                    <label class="flex-row align-center">
                        <span>Enable blablabla</span>
                        <input type="checkbox">
                    </label>
                    <label class="flex-row align-center">
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider"></span>
                            <span>Enable another blablabla</span>
                        </label>
                    </label>
                </section>


                <section>
                    <input type="text" placeholder="Search a country..." id="autocomplete">
                    <button class="button green" onclick="notifySuccess('Saved', 'Everything\'s OK !')">Save !</button>
                </section>
                <script>
                    const COUNTRY_OR_EUROPE = [
                        "Russia", "Germany", "United Kingdom", "France", "Italy", "Spain", "Ukraine", "Poland", "Romania", "Netherlands",
                        "Belgium", "Czechia", "Greece", "Portugal", "Sweden", "Hungary", "Belarus", "Austria", "Serbia", "Switzerland", "Bulgaria",
                        "Denmark", "Finland", "Slovakia", "Norway", "Ireland", "Croatia", "Moldova", "Bosnia and Herzegovina", "Albania", "Lithuania", "North Macedonia", "Slovenia",
                        "Latvia", "Kosovo", "Estonia", "Montenegro", "Luxembourg", "Malta", "Iceland", "Andorra", "Monaco", "Liechtenstein", "San Marino", "Holy See"
                    ]

                    SharpAssetsKit.autocomplete.addAutocompleteListener(autocomplete, (searchObject)=>{
                        let regex = new RegExp(searchObject.split(" ").map(e => `(?=.*${e})`).join(""), "i")
                        return COUNTRY_OR_EUROPE.filter(x => x.match(regex)).map((x,i)=>[i,x]);
                    }, (index, country) => {
                        notify("Country Selected", `You selected ${country} `)
                    });
                </script>



            </section>





            <section class="card" id="menus">
                <section class="bg-DodgerBlue fg-White">
                    <b>Some Menus !</b>
                </section>
                <section class="flex-row gap-0">

                    <input type="radio" class="tab" name="myRadio" id="r1">
                    <label for="r1">My First Radio</label>

                    <input type="radio" class="tab" name="myRadio" id="r2">
                    <label for="r2">My Second Radio</label>

                    <input type="radio" class="tab" name="myRadio" id="r3">
                    <label for="r3">My Third Radio</label>

                </section>
                <section class="flex-row">

                    <button menu="menu-left" left class="button svg-text blue">Left menu <?= svg("caret-left-fill") ?></button>
                    <section name="sampleMenu" class="menu" id="menu-left">Hello !</section>

                    <button menu="menu-top" top class="button svg-text blue">Top Menu <?= svg("caret-up-fill") ?></button>
                    <section name="sampleMenu" class="menu" id="menu-top">Hello !</section>

                    <button menu="menu-right" right class="button svg-text blue">Right Menu <?= svg("caret-right-fill") ?></button>
                    <section name="sampleMenu" class="menu" id="menu-right">Hello !</section>

                    <button menu="menu-bottom" bottom class="button svg-text blue">Bottom Menu <?= svg("caret-down-fill") ?></button>
                    <section name="sampleMenu" class="menu" id="menu-bottom">Hello !</section>

                </section>
                <section>
                    <button overlay="myTestOverlay" class="button blue svg-text"><?= svg("door-open") ?> Open overlay</button>
                </section>
                <section>
                    <b>menu-option</b>
                    <section class="menu-option">
                        <span>Element A</span>
                        <span>Element B</span>
                        <span>Element C</span>
                    </section>
                </section>
                <section id="myTestOverlay" class="overlay">
                    <section class="content">
                        <h1>Hello there !</h1>
                    </section>
                </section>
            </section>






            <section class="card">
                <section class="bg-DodgerBlue fg-white">
                    <b>Some Animations !</b>
                </section>

                <section class="flex-row">
                    <button class="button green" id="spinButton">Start</button>
                    <button class="button red" id="stopSpinButton">Stop</button>
                </section>
                <script>
                    let animation = null;

                    spinButton.onclick = ()=>{
                        animation ??= spinButton.animateLoop([
                            {rotate: '0deg'},
                            {rotate: '360deg'},
                        ], {duration: 750})
                    }

                    stopSpinButton.onclick = ()=>{
                        animation?.stop();
                        animation = null;
                    }

                </script>
            </section>
        </section>

        <section class="window bg-Black fg-White">
            <section class="centered flex-column">
                <h1>I'm a window !</h1>
                <p class="text-justify weight-500">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Autem incidunt aliquam aperiam asperiores quod a voluptas facere amet vel recusandae odio fugiat optio, maiores consequatur nihil expedita ut. Similique corrupti rem laboriosam provident dolorum odio labore, distinctio ipsum, iste sapiente, omnis cupiditate ad perspiciatis impedit architecto repellat nobis asperiores fugit!</p>
                <p class="text-justify weight-500">Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellat facilis, sit ullam repudiandae eos perspiciatis eligendi assumenda magni illo animi voluptates, quia dolore quidem modi, consequatur temporibus earum. Aperiam reprehenderit veritatis autem fuga ratione quae quos, beatae vero, natus ipsam doloribus nisi aut aspernatur officiis repudiandae corporis dolor, animi unde iure possimus? Cum, incidunt? Necessitatibus earum ipsum iure atque saepe. Quos ex ullam rerum? Molestias impedit rem obcaecati quisquam cumque!</p>
                <p class="text-justify weight-500">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorum adipisci ipsam quae veniam aut officiis sunt consequatur esse magni aspernatur.</p>
            </section>
        </section>
    </section>








</body>
</html>