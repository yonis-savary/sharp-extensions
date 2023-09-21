[< Back to extensions summary](../README.md)

# 🎨 AssetsKit

## SVG

The [`SVG`](./Components/Svg.php) component, can insert/serve SVG to your application

### Injecting a SVG

```php
$svg = Svg::getInstance();

$person = $svg->get("person");
$person = $svg->get("person", 128); // Changing size !

// Using the helper function
svg("person-fill");
svg("person-fill", 64);
```

### Configuration

```json
{
    "enabled" : true,
    "url": "/assets/svg",
    "path" : "Sharp/Extensions/AssetsKit/vendor/twbs/bootstrap-icons/icons",
    "cached" : true,
    "default-size" : 24,
    "max-age" : 604800, // 1 Weeks
    "middlewares" : []
}
```

- Set `enabled` to `true` to allow SVG to serve SVG through its API
- `url` is the path used to serve SVG
- `path` describe where svg icons are stored
- `cached`: set it to `true` to Cache SVG content
- `default-size`: Used default SVG size
- `max-age`: Set it to `null` to disallow client caching, or an integer to make the client cache the response
- `middlewares`: Middlewares classes for SVG serving routes

A `svg` function is available in AssetsKit scripts, see [the documentation](./Docs/scripts.md)

## Stylesheet

To build the stylesheet, [`lessc`](https://lesscss.org/) is needed,
after adding `Sharp/Extensions/AssetsKit`, execute

```bash
php do build
```

Then to insert the compiled stylesheet into your view, you can use the `style()` helper

```php
style("assets-kit/style.css") // Place a style tag
style("assets-kit/style.css", true) // Directly inject the content
```

### Stylesheet demo/documentation

To access the stylesheet demo/documentation

1. Make your environment is not set to `production` in `sharp.json`
```json
{
    "env": "debug"
}
```

2. Launch the PHP built-in server
```bash
php do serve 8080
```

3. Access
```
localhost:8080/assets-kit-demo
```

## Commons Scripts

Describing every scripts inside this documentation would make it too long

You can read the [`documentatio here`](./Docs/scripts.md)


[< Back to extensions summary](../README.md)