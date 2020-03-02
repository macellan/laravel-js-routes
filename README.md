# Laravel Javascript Routes

## Why

I love the Laravel routing system and I often use named routes like `route('users.show', array('id' => 1))` to generate `http://domain.tld/users/1`.
With the amazing uprising of Javascript frameworks (AngularJS, EmberJS, Backbone, etc.) it's hard to track changes on your routes between the backend and the REST calls from your Javascript.
The goal of this library is to expose those named routes to your frontend so you can do: `Router.route('users.show', {id: 1})` and get the same result.

## Installation

You can install this package via composer:

``` bash
composer require macellan/laravel-js-routes
```

## Usage

By default the command will generate a `routes.js` file on your project root. This contains all **named** routes in your app.
That's it! You're all set to go. Run the `artisan` command from the Terminal to see the new `routes:javascript` commands.

```bash
php artisan routes:javascript
```

> **Lazy Tip** If you use Grunt, you could set up a watcher that runs this command whenever your routes files change.

## Arguments

| Name     | Default     | Description     |
| -------- |:-----------:| --------------- |
| **name** | *routes.js* | Output filename |

## Options

| Name     | Default     | Description     |
| -------- |:-----------:| --------------- |
| **path**   | *base_path()* | Where to save the generated filename. (ie. public assets folder) |
| **object** | *Router*      | If you want to choose your own global JS object (to avoid collision) |
| **prefix** | *null*        | If you want to a path to prefix to all your routes |

## Javascript usage

You have to include the generated file in your views (or your assets build process).

```html
<script src="/path/to/routes.js" type="text/javascript">
```

And then you have a `Routes` object on your global scope. You can use it as:

```javascript
Router.route(route_name, params)
```

Example:

```javascript
Router.route('users.show', {id: 1}) // returns http://dommain.tld/users/1
```

If you assign parameters that are not present on the URI, they will get appended as a query string:

```javascript
Router.route('users.show', {id: 1, name: 'John', order: 'asc'}) // returns http://dommain.tld/users/1?name=John&order=asc
```

## Contributing

```bash
composer install --dev
./vendor/bin/phpunit
```

```bash
npm install -g grunt-cli
npm install
grunt travis --verbose
```

In addition to a full test suite, there is Travis integration.

## Found a bug?

Please, let me know! Send a pull request or a patch. Questions? Ask! I will respond to all filed issues.

## Inspiration

Although no code was copied, this package is greatly inspired by [FOSJsRoutingBundle](https://github.com/FriendsOfSymfony/FOSJsRoutingBundle) for Symfony.

## License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
