<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AssetsKit Demo</title>
</head>

<?= style("assets-kit/style.css", true) ?>
<?= assetsKitJSBundle(true) ?>

<body class="flex-row">

    <nav class="navbar vertical ">
        <h1>MyAPP</h1>
        <a href="">Link A</a>
        <a href="">Link B</a>
        <a href="">Link C</a>
        <a class="fill-left" href="">logout</a>
    </nav>
    <nav class="navbar vertical bg-turquoise fg-white">
        <h1>MyAPP</h1>
        <a href="">Link A</a>
        <a href="">Link B</a>
        <a href="">Link C</a>
        <a class="fill-left" href="">logout</a>
    </nav>

    <section class="flex-column flex-1">

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

        <section class="window bg-blue fg-white">
            <h1>I'm a window !</h1>
        </section>

        <section class="centered flex-column">

            <section id="buttonSamples" class="card">
                <section class="bg-blue fg-white">
                    <b>Buttons</b>
                </section>
                <section class="flex-row flex-wrap">
                    <button class="button red">red</button>
                    <button class="button orange">orange</button>
                    <button class="button yellow">yellow</button>
                    <button class="button green">green</button>
                    <button class="button turquoise">turquoise</button>
                    <button class="button blue">blue</button>
                    <button class="button indigo">indigo</button>
                    <button class="button violet">violet</button>
                </section>
                <section class="flex-row flex-wrap">
                    <button class="button red secondary">red</button>
                    <button class="button orange secondary">orange</button>
                    <button class="button yellow secondary">yellow</button>
                    <button class="button green secondary">green</button>
                    <button class="button turquoise secondary">turquoise</button>
                    <button class="button blue secondary">blue</button>
                    <button class="button indigo secondary">indigo</button>
                    <button class="button violet secondary">violet</button>
                </section>
                <section class="flex-row flex-wrap">
                    <button class="button red tertiary">red</button>
                    <button class="button orange tertiary">orange</button>
                    <button class="button yellow tertiary">yellow</button>
                    <button class="button green tertiary">green</button>
                    <button class="button turquoise tertiary">turquoise</button>
                    <button class="button blue tertiary">blue</button>
                    <button class="button indigo tertiary">indigo</button>
                    <button class="button violet tertiary">violet</button>
                </section>
                <section class="flex-row flex-wrap">
                    <button disabled class="button red">red</button>
                    <button disabled class="button orange">orange</button>
                    <button disabled class="button yellow">yellow</button>
                    <button disabled class="button green">green</button>
                    <button disabled class="button turquoise">turquoise</button>
                    <button disabled class="button blue">blue</button>
                    <button disabled class="button indigo">indigo</button>
                    <button disabled class="button violet">violet</button>
                </section>
                <section>
                    <b>Some Icons</b>
                </section>
                <section class="flex-row flex-wrap align-center">
                    <button class="button icon red">   <?= svg("x") ?></button>
                    <button class="button icon orange"><?= svg("exclamation-triangle") ?></button>
                    <button class="button icon yellow"><?= svg("exclamation-triangle") ?></button>
                    <button class="button icon green"> <?= svg("check") ?></button>
                    <button class="button icon blue">  <?= svg("info-circle") ?></button>
                    <button class="button icon indigo"><?= svg("gear") ?></button>
                    <button class="button icon violet"><?= svg("send") ?></button>
                </section>
                <section class="flex-row flex-wrap align-center">
                    <button title="Delete" class="button icon red">   <?= svg("trash") ?></button>
                    <button title="Report" class="button icon orange"><?= svg("exclamation-triangle") ?></button>
                    <button title="Report" class="button icon yellow"><?= svg("exclamation-triangle") ?></button>
                    <button title="Confirm" class="button icon green"> <?= svg("check") ?></button>
                    <button title="More" class="button icon blue">  <?= svg("info-circle") ?></button>
                    <button title="Setup" class="button icon indigo"><?= svg("gear") ?></button>
                    <button title="Send" class="button icon violet"><?= svg("send") ?></button>
                </section>

            </section>




            <section class="card">
                <section class="bg-blue fg-white">
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
                <section class="flex-row flex-wrap">
                    <details class="red"> <summary>I'm a details</summary> <p> And so it goes ! </p></details>
                    <details class="orange"> <summary>I'm a details</summary> <p> And so it goes ! </p></details>
                    <details class="yellow"> <summary>I'm a details</summary> <p> And so it goes ! </p></details>
                    <details class="green"> <summary>I'm a details</summary> <p> And so it goes ! </p></details>
                    <details class="blue"> <summary>I'm a details</summary> <p> And so it goes ! </p></details>
                    <details class="indigo"> <summary>I'm a details</summary> <p> And so it goes ! </p></details>
                    <details class="violet"> <summary>I'm a details</summary> <p> And so it goes ! </p></details>
                </section>
                <section>
                    <b>Some Infos</b>
                </section>
                <section class="flex-row flex-wrap">
                    <section class="info-red">
                        <b>red infos</b>
                        <p> I'm an extra, and so it goes ! </p>
                    </section>
                    <section class="info-orange">
                        <b>orange infos</b>
                        <p> I'm an extra, and so it goes ! </p>
                    </section>
                    <section class="info-yellow">
                        <b>yellow infos</b>
                        <p> I'm an extra, and so it goes ! </p>
                    </section>
                    <section class="info-green">
                        <b>green infos</b>
                        <p> I'm an extra, and so it goes ! </p>
                    </section>
                    <section class="info-blue">
                        <b>blue infos</b>
                        <p> I'm an extra, and so it goes ! </p>
                    </section>
                    <section class="info-indigo">
                        <b>indigo infos</b>
                        <p> I'm an extra, and so it goes ! </p>
                    </section>
                    <section class="info-violet">
                        <b>violet infos</b>
                        <p> I'm an extra, and so it goes ! </p>
                    </section>
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
                <section>
                    <section class="notification">
                        Your notifications
                        <div class="number">12</div>
                    </section>
                </section>
                <section>
                    <b>Some Chips</b>
                </section>
                <section class="flex-row flex-wrap">
                    <button class="chip red">   <?= svg("check") ?></button>
                    <button class="chip orange"><?= svg("check") ?></button>
                    <button class="chip yellow"><?= svg("check") ?></button>
                    <button class="chip green"> <?= svg("check") ?></button>
                    <button class="chip blue">  <?= svg("check") ?></button>
                    <button class="chip indigo"><?= svg("check") ?></button>
                    <button class="chip violet"><?= svg("check") ?></button>
                </section>
                <section>
                    <b>Some Tags</b>
                </section>
                <section class="flex-row flex-wrap">
                    <button class="tag clickable red">   red</button>
                    <button class="tag clickable orange">orange</button>
                    <button class="tag clickable yellow">yellow</button>
                    <button class="tag clickable green"> green</button>
                    <button class="tag clickable blue">  blue</button>
                    <button class="tag clickable indigo">indigo</button>
                    <button class="tag clickable violet">violet</button>
                </section>
                <section class="flex-row flex-wrap">
                    <button class="badge clickable red">   red</button>
                    <button class="badge clickable orange">orange</button>
                    <button class="badge clickable yellow">yellow</button>
                    <button class="badge clickable green"> green</button>
                    <button class="badge clickable blue">  blue</button>
                    <button class="badge clickable indigo">indigo</button>
                    <button class="badge clickable violet">violet</button>
                </section>
            </section>


            <section class="card">
                <section class="bg-blue fg-white">
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
                <section class="bg-blue fg-white">
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
                <section class="bg-blue fg-white">
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
                    <input type="text" id="autocomplete">
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
                        notify("Country Selected", `You selected ${country} (${index})`)
                    });
                </script>



            </section>





            <section class="card" id="menus">
                <section class="bg-blue fg-white">
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

                    <button menu="menu-left" left class="button svg-text blue"><?= svg("caret-left") ?>Left menu</button>
                    <section name="sampleMenu" class="menu" id="menu-left">Hello !</section>

                    <button menu="menu-top" top class="button svg-text blue"><?= svg("caret-up") ?>Top Menu</button>
                    <section name="sampleMenu" class="menu" id="menu-top">Hello !</section>

                    <button menu="menu-right" right class="button svg-text blue"><?= svg("caret-right") ?>Right Menu</button>
                    <section name="sampleMenu" class="menu" id="menu-right">Hello !</section>

                    <button menu="menu-bottom" bottom class="button svg-text blue"><?= svg("caret-down") ?>Bottom Menu</button>
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
                <section class="bg-blue fg-white">
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
    </section>









</body>
</html>