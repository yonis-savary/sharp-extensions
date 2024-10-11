[< Back to extensions summary](../README.md)

# RequestWatch

This extension purpose is to log every request that passes through your application

To use it, enable it as an application
```bash
php do enable-application SharpExtensions/RequestWatch
```

When it's done, a `requestWatch.db` file will be created in your `Storage` directory, containing
every users requests

## Data

To retrieve data, you can use:

- `RequestWatch::mostViewedRouteForPeriod(string $from, string $to, ?int $limit=10)`: Retrieve the most viewed routes in your application in a period of time (`yyyy-mm-dd` format are needed for `from` and `to` arguments)
- `RequestWatch::getUserNavigationTree(int $rowId)`: from a `request_user` row id, retrieve the request tree representing the naviguation path of the user inside of your website

[< Back to extensions summary](../README.md)