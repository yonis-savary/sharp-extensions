[< Back to AssetsKit summary](../README.md)

# AssetsKit Scripts

## TL;DR : Injecting scripts

Use this in your view
```php
<?= assetsKitJSBundle() ?>
```

## bridge.js

This script is used by every scripts below

## lang.js

Utility functions used by every script below,
array and object prototypes got some additionnal method too !

```js
isMobile() // Is the client a mobile

await sleep(250) // Sleep 250 ms

let clone = cloneObject({A: 1});
let clone = clone({A: 1});

let color = getCSSVariable("color");

// Transform an object to a FormData object
let formData = objectToFormData({A: 1, B: 2});

html`
    <p>${element.description}</p>
    <ul>
        ${comments.map( comment => html`
            <li>${comment.data.content}</li>
        `).join("")}
    </ul>
`

let myArray = [ /* ... */ ]

// Sort the array and return a copy
myArray.sortByKey(x => x.data.creation_date);

// Return [1,2,3,4,5]
[1, 1, 2, 3, 3, 3, 4, 5, 5].uniques();

// Return an inversed copy
[3,2,1].invert()

// Regroup elements that have the same key
let commentsByUser = comments.groupByKey(x => x.fk_user);

// Returns 3
[1,2,3].last()

// Slice the array in chunks of n elements
[1,2,3,4,5,6,7].chunk(2);
// Returns [ [1,2], [3,4], [5,6], [7] ]

// Return -1264993755
"someString".hash()

// Equivalent of PHP's ucfirst
"first name".toUpperCaseFirst() // "First name"

// Wait for an event to happen to resolve the promise
await myElement.waitForEvent("change");

// Return every sibling element (non-text)
myInput.siblings()

// getAttributeS return an array of attribute values
let [day, hour] = myCard.getAttributes("day", "hour");

// appendChildS append multiples childs at the same time
myList.appendChilds(firstChoice, secondeChoice);

// Return the first parent element that match the given selector
event.target.firstParentThatMatch("card[day]");

let myCard = document.nodeFromHTML(`
    <section class="card"></section>
`);

// Return either 0 or 1
Math.randomBit()

// Return a random float between min and max (not included)
Math.randomFloat(min, max)

// Return a random integer between min and max (included)
Math.randomInt()

// Return a range from start to max (set inclusive to true to have max in returned range)
Math.range(10) // [0..9]
Math.range(10, 1) // [1..9]
Math.range(10, 1, true) // [1..10]

Math.average(10, 15, 20) // Return 15

// Return 90
let scaledValue = Math.map(50, 0, 100, 0, 180)
// Return -90
let scaledValue = Math.map(50, 0, 100, -180, 0)
```

### Mobile - Screen Events

Properties :
- Always on mobile, and on desktop when `window.innerWith < 1000px`, `document.body` got the `is-mobile` class
- On desktop, when adding/removing the `is-mobile` class, a `mobileModeSwitched` event is triggered on `window` (the event detail got the boolean `mobile` key)
- On mobile, `document.body` has the `landscape-mode` or `portrait-mode` class depending on the screen rotation (this class is dynamic and can change when the phone is rotated)
- When rotating your phone, `switchedToLandscapeMode` and `switchedToPortraitMode` events are automatically triggered on the `window` object

## date.js

```js
// Are period overlaping ? (Not touching)
doPeriodOverlap('2023-01-01', '2023-01-10', '2023-01-05', '2023-01-15')

// Do period overlap or touch ?
doPeriodTouch('2023-01-01', '2023-01-10', '2023-01-05', '2023-01-15')

// Transform any string/date object to a
// human readeable format
// Give `true` as second parameter to get the time too
dateToString('2023-01-01 15:12:12')
dateToString(new Date())
dateToString('2023-01-01 15:12:12', true)

// Transform any string/date object to a
// Database readeable format
// Give `true` as second parameter to get the time too
dateToSQL('2023-01-01 15:12:12')
dateToSQL(new Date())
dateToSQL('2023-01-01 15:12:12', true)

// Add an interval to a date object
// Available keywords are
// SECONDS or SECOND
// MINUTES or MINUTE
// HOURS or HOUR
// DAYS or DAY
// WEEKS or WEEK
// MONTHS or MONTH
// YEARS or YEAR
let date = new Date();
date.add(5, WEEKS);
date.sub(5, MONTHS);

let date = (new Date());
let tomorrow = (new Date()).add(1, DAY);

date.diff(tomorrow, DAY) // 1

date.isBetween('2023-01-01', '2023-05-12') // True/False

date.resetTime() // Set Time to 00:00:00:00

// Compare two dates (not their time value)
date.sameDayAs('2023-01-05') // True/False
date.sameDayAs(new Date) // True/False
```

## eventSource.js

```js
eventSource("/path/my-event", {
    eventA: function(data){
        console.log(data)
    }
});

```

## fetch.js

```js
// Get an url to the api
apiUrl("/contact/list");

// Directly fetch the data
apiFetch("/contact/list");
apiFetch("/contact/create", myData, "POST");

// The 4 functions below are made to be
// use with the Autobahn component API !
apiCreate(model, data);
apiRead(model, filter);
apiUpdate(model, filter);
apiDelete(model, filter);

// Get the last response (fetch response)
apiLastResponse();
// Get last response's raw text
apiLastResponseText();
```

## svg.js

This script is made to work with AssetsKit's SVG component

```js
svg("person")
svg("person", 128) // Change size
```

## animation.js

```js
// Wait for the animation to end
await myElement.animateAsync([
    {opacity: 0},
    {opacity: 1}
], {duration: 1250})

// Fade animation
await myElement.fadeIn()
await myElement.fadeOut()

// "Pop" animation
await myElement.popIn()
await myElement.popOut()

let animation = myElement.animateLoop([
    {opacity: 0},
    {opacity: 1}
], {duration: 1250})
// Stop created animation
animation.stop()
```

## menu.js

```html
<button id="myButton"></button>
<section id="myMenu" class="menu">
    <p>Hello</p>
</section>

<!-- Clicking this element will open the menu above it -->
<button id="autoOpen" top menu="myMenu"></button>
```

```js
// Manually open a menu
openMenu(myMenu, myButton);
// Change direction
openMenu(myMenu, myButton, "bottom");

// Manually open a menu on the screen
openMenuAtCoord(myMenu, event.clientX, event.clientY);
// Change direction
openMenuAtCoord(myMenu, event.clientX, event.clientY, "right");

// Close the last opened menu
closeMenu();

// Refresh the menu listeners
addMenuListeners();

// true/false
isMenuOpened(myMenu);
```

## overlay.js

```html
<button id="myButton"></button>
<section id="myOverlay" class="overlay">
    <section class="content">
        <p>Hello</p>
    </section>
</section>

<!-- Clicking this element will open the overlay -->
<button id="autoOpen" overlay="myOverlay"></button>
```

```js
// Manually open an overlay
openOverlay(myOverlay);

// Close the last opened overlay
await closeOverlay();

// Close every opened overlay
await closeAllOverlays();

// Refresh overlay listeners
addOverlayListeners();
```

## component.js

```js
let counter = Component( c => `
    <div>
        <button onclick="${c.method.decrement}">-</button>
        <span>${c.html.count}</span>
        <button onclick="${c.method.increment}">+</button>
    </div>
`, {
    count: 0,
    decrement: (evt, c)=> c.props.count--,
    increment: (evt, c)=> c.props.count++
});

document.body.appendChild(counter);
```

- The first argument given to `Component` is the render function
- The second is the properties of the component, its data and its methods

- Accessing `c.props` gives you access to its data, changing a state re-render the component
- Accessing `c.html` will sterialize the content before returning it
- Accessing `c.method` will return an expression that call the object method

## highstate.js

Highstate is a script that is used to give colors to state element

Imagine a list of orders with a column `state`, to make your list more readable and enjoyable, you may want to give a little icon and a color to each possible state

```js
AssetsKitHighstate.registerPreset(
    new AssetsKitHighstatePreset("PaymentStatus")
    .addState("Pending"     , AssetsKitHighstateColors.BLUE, "hourglass-split")
    .addState("Paid"        , AssetsKitHighstateColors.GREEN, "check")
    .addState("Cancelled"   , AssetsKitHighstateColors.GRAY, "dash"),
)

AssetsKitHighstate.highlightAll("#myExampleTable td[status]", "PaymentStatus")
```

## validate.js

```js
async function myCreationFunction()
{
    let form = await validateAsync({
        full_name: read(myTextInput).error("This input is needed").notNull(),
        some_flag: read(myCheckbox),
        age: read(myNumberInput).error("This input is needed").notNull().error("Must be between 0 and 150").between(0, 150),
        username: read(myUserName).notNull().match(/^[a-z0-9\-]{5,}$/).respect(usernameDontExists)
    })

    let {
        full_name,
        some_flag,
        age,
        username
    } = form;
}

validate({
    full_name: read(myTextInput).error("This input is needed").notNull(),
    some_flag: read(myCheckbox),
    age: read(myNumberInput).error("This input is needed").notNull().error("Must be between 0 and 150").between(0, 150),
    username: read(myUserName).notNull().match(/^[a-z0-9\-]{5,}$/).respect(usernameDontExists)
}, async form =>{
    // Treat data is no error were found

    let {
        full_name,
        some_flag,
        age,
        username
    } = form;

};
```

## notify.js

`notify` open a toast message on the top of the screen for an instant
to notify the user of an information/feedback

```js
notify(title, description="", icon="info", tone="blue")

// Shortcuts
notifyError(title, description);
notifySuccess(title, description);
notifyWarning(title, description);
notifyInfo(title, description);
```

## autocomplete.js

```js
const COUNTRY_OR_EUROPE = [
    "Russia", "Germany", "United Kingdom", "France", "Italy", "Spain", "Ukraine", "Poland", "Romania", "Netherlands",
    "Belgium", "Czechia", "Greece", "Portugal", "Sweden", "Hungary", "Belarus", "Austria", "Serbia", "Switzerland", "Bulgaria",
    "Denmark", "Finland", "Slovakia", "Norway", "Ireland", "Croatia", "Moldova", "Bosnia and Herzegovina", "Albania", "Lithuania", "North Macedonia", "Slovenia",
    "Latvia", "Kosovo", "Estonia", "Montenegro", "Luxembourg", "Malta", "Iceland", "Andorra", "Monaco", "Liechtenstein", "San Marino", "Holy See"
]

myInput.addAutocompleteListener(searchObject =>{
    let regex = new RegExp(searchObject.split(" ").map(e => `(?=.*${e})`).join(""), "i")
    return COUNTRY_OR_EUROPE.filter(x => x.match(regex)).map((x,i)=>[i,x]);
}, (index, country) => {
    notify("Country Selected", `You selected ${country} `);

    let selectedIndex = index;
    selectedIndex = myInput.getAttribute("selection")
})
```

## entity.js

This script can be used to have interactive read/write data page, it was made to work
with `apiUpdate` method (Autobahn utils)

```html
<section entity="client" entity-id="3">
    <input type="email" client="email">
</section>
```

Everything INSIDE the section will be bound to the `client` table (id: 3),
when the input value changes, `apiUpdate` shall be called to update the row

Supported input types:
- Text + email + tel
- Date + time
- checkbox + radio

When a row is update, two events are triggered on `document`
- `successful-entity-update` when `apiUpdate` is successful
- `failed-entity-update` when `apiUpdate` failed

Both of these event got the `detail` key as :
- `entity`: name of the updated table
- `field`: name of the updated field
- `value`: new value for the field
- `entityId`: updated row id
- `error` (only for failed event): thrown exception by `apiUpdate`

[< Back to AssetsKit summary](../README.md)
