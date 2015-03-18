C2isCookieBundle
================

Bundles the resources and logic needed to display a cookie acceptance message on a website.
The message disapears after the user closed it three times or explicitely clicked on "accept".

Includes a default twig + css template.

Usage
-----

You need to add the bundle routing configuration to your application routing like so:

``` yaml
c2is_cookie:
    resource: "@C2isCookieBundle/Resources/config/routing.yml"
```

There are three routes used in this bundle. One serves the HTML template for your cookie acceptance panel, the others are called with Ajax requests to register the user actions.

To display the message, I recommend using an esi - the cache duration is managed by the plugin. This will allow the message to appear to a new user whatever your page cache configuration if you use HTTPCacheing. If you don't, it will fallback to the render Twig function and work aswell, so no worries. The default tempalte works best if placed just before or close to the body closing tag in your template.

``` twig
<html>
    ...
    <body>
        ...
        {{ render_esi(url('c2is_cookie_message')) }}
    </body>
</html>
```

The default template comes with a default css you can include aswell:

``` twig
<html>
    <head>
        ...
        <link rel="stylesheet" href="{{ asset('bundles/c2iscookie/css/cookie.min.css') }}" />
    </head>
    ...
</html>
```

For the whole thing to work properly you will need to include the javascript (you will need jquery aswell):

``` twig
<html>
    ...
    <body>
        ...
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="{{ asset('bundles/c2iscookie/js/jquery.cookie.min.js') }}"></script>
    </body>
</html>
```

Configuration
=============

Bundle
------

The exhaustive default configuration is as follows:

``` yaml
c2is_cookie:
    cookie_name: 'c2is_cookie_acknowledged'
    occurrences: 3 # The number of times the cookie panel has to be closed / accepted before it won't appear again
    actions:
        close: 1 # This number is incremented to the user current occurrences value when he closes the panel
        accept: 3 # This number is incremented to the user current occurrences value when he clicks "I accept"
```

Javascript
----------

The default twig template creates a div with an id of "cookiesLegalMessage". The JQuery plugin used by the bundle expects a container with that id to bind itself to.

If you override the Twig template and create your own panel, you can initialize the JQuery plugin like so:

``` js
$('#my-container').c2isCookie(
    on_closed: function(data) {
        ...
    },
    on_acknowledged: function(data) {
        ...
    }
);
```

Available configurations for that plugin is:

- on_closed: 

    defaults to false
    Can be a function that will be executed when the user closes the cookie acceptance panel. Will receive as argument a json array with the values success: true and message: 'a confirmation message'
    
- on_acknowledged: 

    defaults to false
    Can be a function that will be executed when the user accepts the cookie. Will receive as argument a json array with the values success: true and message: 'a confirmation message'

There also are events fired when the user closes or accepts the cookie panel:

- cookie_closed
- cookie_acknowledged

The default plugin behaviour on those actions is to hide the cookie panel. This is done before triggering the events, so you can display it back again in your event listener if you want to display a confirmation message or something.
The events are fired from the container so you'll want to listen to that:

``` js
$('#my-container').c2isCookie();
$('#my-container').on('cookie_closed', function(data) {
    ...
});
```

Overriding
==========

You can override parts of this bundle to better fit your application needs

Template
--------

You can create a twig template of your own:

```
app/Resources/C2isCookieBundle/views/message.html.twig
```

Messages and translations
-------------------------

The exhaustive list of messages used and their default english value:

``` yaml
c2is.cookie.accept.message: 'By continuing to browse without changing your parameters, you accept the use of cookies or similar technologies to get services and offers tailored to your interests and to ensure secure transactions on our website.'
c2is.cookie.learn.more: 'Know more'
c2is.cookie.acknowledge: 'OK'
c2is.cookie.close.message: 'By continuing to browse without changing your parameters, you accept the use of cookies or similar technologies on our website.'
c2is.cookie.acknowledge.message: 'You accepted the use of cookies or similar technologies on our website.'
```