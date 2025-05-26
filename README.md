# laravel-htmx

Laravel integration for [htmx](https://htmx.org/).

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mauricius/laravel-htmx.svg?style=flat-square)](https://packagist.org/packages/mauricius/laravel-htmx)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/mauricius/laravel-htmx/run-tests.yml?branch=master&label=tests)](https://github.com/mauricius/laravel-htmx/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/mauricius/laravel-htmx.svg?style=flat-square)](https://packagist.org/packages/mauricius/laravel-htmx)

Supported Laravel Versions >= v8.80.0.

## Installation

You can install the package via composer:

```bash
composer require mauricius/laravel-htmx
```

To install htmx please browse [their documentation](https://htmx.org/docs/#installing)

## Usage

### Request

You can resolve an instance of the `HtmxRequest` from the container which provides shortcuts for reading the htmx-specific [request headers](https://htmx.org/reference/#request_headers).

```php
use Mauricius\LaravelHtmx\Http\HtmxRequest;

Route::get('/', function (HtmxRequest $request)
{
    // always true if the request is performed by Htmx
    $request->isHtmxRequest();
    // indicates that the request is via an element using hx-boost
    $request->isBoosted();
    // the current URL of the browser
    $request->getCurrentUrl();
    // true if the request is for history restoration after a miss in the local history cache
    $request->isHistoryRestoreRequest()
    // the user response to an hx-prompt
    $request->getPromptResponse();
    // 	the id of the target element if it exists
    $request->getTarget();
    // the name of the triggered element if it exists
    $request->getTriggerName();
    // the id of the triggered element if it exists
    $request->getTriggerId();
});
```

### Response

- `HtmxResponseClientRedirect`

htmx can trigger a client side redirect when it receives a response with the `HX-Redirect` [header](https://htmx.org/reference/#response_headers). The `HtmxResponseClientRedirect` makes it easy to trigger such redirects.

```php
use Mauricius\LaravelHtmx\Http\HtmxResponseClientRedirect;

Route::get('/', function (HtmxRequest $request)
{
    return new HtmxResponseClientRedirect('/somewhere-else');
});
```

- `HtmxResponseClientRefresh`

htmx will trigger a page reload when it receives a response with the `HX-Refresh` [header](https://htmx.org/reference/#response_headers). `HtmxResponseClientRefresh` is a custom response class that allows you to send such a response. It takes no arguments, since htmx ignores any content.

```php
use Mauricius\LaravelHtmx\Http\HtmxResponseClientRefresh;

Route::get('/', function (HtmxRequest $request)
{
    return new HtmxResponseClientRefresh();
});
```

- `HtmxResponseStopPolling`

When using a [polling trigger](https://htmx.org/docs/#polling), htmx will stop polling when it encounters a response with the special HTTP status code 286. `HtmxResponseStopPolling` is a custom response class with that status code.

```php
use Mauricius\LaravelHtmx\Http\HtmxResponseStopPolling;

Route::get('/', function (HtmxRequest $request)
{
    return new HtmxResponseStopPolling();
});
```

For all the remaining [available headers](https://htmx.org/reference/#response_headers) you can use the `HtmxResponse` class.

```php
use Mauricius\LaravelHtmx\Http\HtmxResponse;

Route::get('/', function (HtmxRequest $request)
{
    return with(new HtmxResponse())
        ->location($location) // Allows you to do a client-side redirect that does not do a full page reload
        ->pushUrl($url) // pushes a new url into the history stack
        ->replaceUrl($url) // replaces the current URL in the location bar
        ->reswap($option) // Allows you to specify how the response will be swapped
        ->retarget($selector); // A CSS selector that updates the target of the content update to a different element on the page
});
```

Additionally, you can trigger [client-side events](https://htmx.org/headers/hx-trigger/) using the `addTrigger` methods.

```php
use Mauricius\LaravelHtmx\Http\HtmxResponse;

Route::get('/', function (HtmxRequest $request)
{
    return with(new HtmxResponse())
        ->addTrigger("myEvent")
        ->addTriggerAfterSettle("myEventAfterSettle")
        ->addTriggerAfterSwap("myEventAfterSwap");
});
```

If you want to pass details along with the event you can use the second argument to send a body. It supports strings or arrays.

```php
use Mauricius\LaravelHtmx\Http\HtmxResponse;

Route::get('/', function (HtmxRequest $request)
{
    return with(new HtmxResponse())
        ->addTrigger("showMessage", "Here Is A Message")
        ->addTriggerAfterSettle("showAnotherMessage", [
            "level" => "info",
            "message" => "Here Is A Message"
        ]);
});
```

You can call those methods multiple times if you want to trigger multiple events.


```php
use Mauricius\LaravelHtmx\Http\HtmxResponse;

Route::get('/', function (HtmxRequest $request)
{
    return with(new HtmxResponse())
        ->addTrigger("event1", "A Message")
        ->addTrigger("event2", "Another message");
});
```

### Render Blade Fragments

This library also provides a basic Blade extension to render [template fragments](https://htmx.org/essays/template-fragments/).

The library provides two new Blade directives: `@fragment` and `@endfragment`. You can use these directives to specify a block of content within a template and render just that bit of content. For instance:

```blade
{{-- /contacts/detail.blade.php  --}}
<html>
    <body>
        <div hx-target="this">
            @fragment("archive-ui")
                @if($contact->archived)
                    <button hx-patch="/contacts/{{ $contact->id }}/unarchive">Unarchive</button>
                @else
                    <button hx-delete="/contacts/{{ $contact->id }}">Archive</button>
                @endif
            @endfragment
        </div>
        <h3>Contact</h3>
        <p>{{ $contact->email }}</p>
    </body>
</html>
```

With this fragment defined in our template, we can now render either the entire template:

```php
Route::get('/', function ($id) {
    $contact = Contact::find($id);

    return View::make('contacts.detail', compact('contact'));
});
```

Or we can render only the `archive-ui` fragment of the template by using the `renderFragment` macro defined in the `\Illuminate\View\View` class:

```php
Route::patch('/contacts/{id}/unarchive', function ($id) {
    $contact = Contact::find($id);

    // The following approaches are equivalent

    // Using the View Facade
    return \Illuminate\Support\Facades\View::renderFragment('contacts.detail', 'archive-ui', compact('contact'));

    // Using the view() helper
    return view()->renderFragment('contacts.detail', 'archive-ui', compact('contact'));

    // Using the HtmxResponse Facade
    return \Mauricius\LaravelHtmx\Facades\HtmxResponse::renderFragment('contacts.detail', 'archive-ui', compact('contact'));

    // Using the HtmxResponse class
    return with(new \Mauricius\LaravelHtmx\Http\HtmxResponse())
        ->renderFragment('contacts.detail', 'archive-ui', compact('contact'));
});
```

#### OOB Swap support

htmx supports updating multiple targets by returning multiple partial responses with [`hx-swap-oob`](https://htmx.org/docs/#oob_swaps). With this library you can return multiple fragments by using the `HtmxResponse` as a return type.

For instance, let's say that we want to mark a todo as completed using a PATCH request to `/todos/{id}`. With the same request, we also want to update in the footer how many todos are left:

```blade
{{-- /todos.blade.php  --}}
<html>
    <body>
        <main hx-target="this">
            <section>
                <ul class="todo-list">
                    @fragment("todo")
                        <li id="todo-{{ $todo->id }}" @class(['completed' => $todo->done])>
                            <input
                                type="checkbox"
                                class="toggle"
                                hx-patch="/todos/{{ $todo->id }}"
                                @checked($todo->done)
                                hx-target="#todo-{{ $todo->id }}"
                                hx-swap="outerHTML"
                            />
                            {{ $todo->name }}
                        </li>
                    @endfragment
                </ul>
            </section>
            <footer>
                @fragment("todo-count")
                    <span id="todo-count" hx-swap-oob="true">
                        <strong>{{ $left }} items left</strong>
                    </span>
                @endfragment
            </footer>
        </main>
    </body>
</html>
```

We can use the `HtmxResponse` to return multiple fragments:

```php
Route::patch('/todos/{id}', function ($id) {
    $todo = Todo::find($id);
    $todo->done = !$todo->done;
    $todo->save();

    $left = Todo::where('done', 0)->count();

    return HtmxResponse::addFragment('todomvc', 'todo', compact('todo'))
        ->addFragment('todomvc', 'todo-count', compact('left'));
});
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [mauricius](https://github.com/mauricius)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
