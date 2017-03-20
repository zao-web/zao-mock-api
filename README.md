Zao Mock API
--------

Install this WordPress plugin, then create a mock api response by sending query params. Simply make a GET/POST/PUT/ETC request to `<site_url>?mock_api=1`.

## The Basics

By default, the status code will be `200`, and the JSON body response will be:

```json
{"success": true, "data": "Hello World"}
```

## Mocking Responses

Both of those values can be modified with query parameters. For instance, making a request to `<site_url>?mock_api=1&code=503&response[boolean]=1&response[string]=Hello World` will return a status code of `503`, and the JSON body response will be:

```json
{"boolean": "1", "string": "Hello World"}
```

## Managing Mock API access

If you want to modify if/how the mock api can be used, use the `allow_mock_api` filter.

Examples:

```php
// Disable:
add_filter( 'allow_mock_api', '__return_false' );

// Allow for logged-in users only:
add_filter( 'allow_mock_api', 'is_user_logged_in' );
```

_Probably wise to not install in production unless modifiying the `allow_mock_api` filter in some way._

## Using Post/Term meta values as the API response

Along with the mock data functionality, you can also store mock data as post or term meta, and your API response will contain the meta value. To do so, you need to make a request to the permalink of the term/post with the `?mock_api=meta` query parameter (e.g. `<site_url>?mock_api=meta`). If that post/term contains a meta value for the `_mock_api` meta key, that value will be returned in the `data` object:

```json
{"success": true, "data": ["<meta value>"]}
```

If there is no meta value, you will still get a `200` response, but success will be false, with no data object:

```json
{"success": false}
```

Again, this is for mocking API data only. If you want advanced meta REST API capabilities, consider [CMB2](https://github.com/WebDevStudios/CMB2/wiki/REST-API).
