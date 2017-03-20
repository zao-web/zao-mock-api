Zao Mock API
--------

Install this WordPress plugin, then create a mock api response by sending query params. Simply make a GET/POST/PUT/ETC request to <site_url>?mock_api.

By default, the status code will be `200`, and the JSON body response will be:

```json
{"success": true, "data": "Hello World"}
```

Both of those values can be modified with query parameters. For instance, making a request to `<site_url>?mock_api=null&code=503&response[boolean]=1&response[string]=Hello World` will return a status code of `503`, and the JSON body response will be:

```json
{"boolean": "1", "string": "Hello World"}
```

If you want to modify if/how the mock api can be used, use the `allow_mock_api` filter.

Examples:

```php
// Disable:
add_filter( 'allow_mock_api', '__return_false' );

// Allow for logged-in users only:
add_filter( 'allow_mock_api', 'is_user_logged_in' );
```

_Probably wise to not install in production unless modifiying the `allow_mock_api` filter in some way._
