Zao Mock API
--------

Install this WordPress plugin, then create a mock api response by sending query params. Simply make a GET/POST/PUT/ETC request to <site_url>?mock_api.

By default, the status code will be `200`, and the JSON body response will be:

```json
{ success: true, data: "Hello World" }
```

Both of those values can be modified with query parameters. For instance, making a request to `<site_url>?mock_api=null&code=503&response[boolean]=1&response[string]=Hello World` will return a status code of `503`, and the JSON body response will be:

```json
{ boolean: "1", string: "Hello World" }
```

_Probably wise to not install in production._
