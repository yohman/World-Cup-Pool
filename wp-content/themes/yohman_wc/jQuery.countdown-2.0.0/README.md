[The Final Countdown](http://hilios.github.io/jQuery.countdown/)
=====================

#### A simple and html agnostic date countdown plugin for jQuery ####

To get started, check-it out: http://hilios.github.io/jQuery.countdown/

The ultimate countdown plugin designed to fit in any coupon, auction site or product launch. Read our [Documentation](http://hilios.github.io/jQuery.countdown/documentation.html) and follow our [Examples](http://hilios.github.io/jQuery.countdown/examples.html) to see what suits your particular needs.

#### Requirements ####

Since version 2.0.0 we only support jQuery above **1.7** (including **2.0**). For legacy **1.6** support please use the version [1.0.1](https://github.com/hilios/jQuery.countdown/tree/v1.0.1).

Getting started
---------------

```html
<div id="getting-started"></div>
<script type="text/javascript">
  $('#getting-started').countdown('2015/01/01', function(event) {
    $(this).html(event.strftime('%w weeks %d days %H:%M:%S'));
  });
</script>
```

### [Documentation](http://hilios.github.io/jQuery.countdown/documentation.html)

Our documentation is powered by [Jekyll](http://jekyllrb.com/) (see `gh-page` branch) and hosted in GitHub Pages at [http://hilios.github.io/jQuery.countdown/](http://hilios.github.io/jQuery.countdown/documentation.html).

### [Examples](http://hilios.github.io/jQuery.countdown/examples.html)

There are few ways to get started, from the most simple example to advanced, we support many different countdown styles, see wich one fits your scenario, and if anyone doesn't it's a good starting point to customize your output.

-   [Basic coupon site with format N days hr:min:sec](http://hilios.github.io/jQuery.countdown/examples/basic-coupon-site.html)
-   [Advance coupon with conditionals and pluralization, format N weeks N days hr:min:sec](http://hilios.github.io/jQuery.countdown/examples/advanced-coupon-site.html)
-   [Product launch in... (callback style)](http://hilios.github.io/jQuery.countdown/examples/website-launch.html)
-   [New year's eve (legacy style)](http://hilios.github.io/jQuery.countdown/examples/legacy-style.html)
-   [Multiple instances on the same page](http://hilios.github.io/jQuery.countdown/examples/multiple-instances.html)
-   [Calculate the countdown total hours](http://hilios.github.io/jQuery.countdown/examples/show-total-hours.html)

Release notes
-------------

#### What's new in 2.0.0

*   Add the `strftime` formatter
*   Add support for jQuery callback style
*   Add grunt tools
*   Better docs and examples

#### What's new in 1.0.1

*   Add AMD support
*   Fix bug that call finish 2sec earlier
*   Fix bug when send miliseconds has a string was not parsed

#### What's new in 1.0.0

*   First public release

Contributing
------------

The Final Countdown uses **Grunt** with convenient methods for working with the plugin. It's how we compile our code and run tests. To use it, get [NodeJS](http://nodejs.org/), install the required dependencies as directed and then run some Grunt commands.

```shell
npm install
grunt test  # Lint code and run unit test
grunt build # Generate the min version
grunt       # Watch for updates than test and build
```

This plugin is tested with [QUnit](http://qunitjs.com/), under jQuery 1.7.2 up to 2.0.3, Bootstrap 3.0 and RequireJS 2.1.9. 

The functional tests were made against:

*   Chrome >= 12
*   Safari >= 5
*   Firefox >= 5.0
*   IE 7/8/9

### Contributors ###

Thanks for bug reporting and fixes:

*   Daniel Leavitt (@dleavitt)
*   Fagner Brack (@FagnerMartinsBrack)
*   Matthew Sigley (@msigley)

### License ###

Copyright (c) 20011 Edson Hilios. This is a free software is licensed under the MIT License.

*   [Edson Hilios](http://edson.hilios.com.br). Mail me: edson (at) hilios (dot) com (dot) br