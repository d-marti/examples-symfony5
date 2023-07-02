# Examples using Symfony 5

## Project creation, PHP version and server setup

Create your Symfony skeleton project with:

```
symfony new project-name --version=lts
```

Set your PHP version in `composer.json`:
```json
    "require": {
        "php": ">=8.1",
        ...
    },
    "config": {
        "platform": {
            "php": "8.1"
        },
        ...
    }
```

Then run:
```shell
composer update
```

If using the Symfony binary tool, check the project's PHP version with:
```shell
symfony local:php:list
```
You should see the PHP version and something like:
>The current PHP version is selected from composer.json from current dir

You can test your Symfony app with any webserver or with Symfony's own local web server, by running:
```shell
symfony server:start -d
```
Or:
```shell
symfony serve -d
```
*The `"-d"` option runs it in the background as a daemon so you can continue using the shell.*
To stop the server, use:
```shell
symfony server:stop
```

*Note that the first time your run the server you will need to create certificates, with:*
```shell
symfony server:ca:install
```

**Tip on WSL certificates**
>If you are running WSL, you will also need to manually install the Symfony generated certificate into your Windows Certificates Store. To do this, go to `~/.symfony5/certs/` and copy `rootCA.pem` to a mounted Windows drive (for ex. `/mnt/d`) and rename it to `rootCA.crt`. Open it through Windows Explorer, then `Install Certificate` under `Local Machine` > `Trusted Root Certification Authorities`.<br>
<br>
If that doesn't work, you can install `mkcert` in Windows, copy both `~/.symfony5/certs/*.pem` files to the location listed with `mkcert -CAROOT` (usually `%LOCALAPPDATA%\mkcert`), and then run `mkcert -install`.

You should also add a `README.md` and `LICENSE` file, then commit and push everything with `git`.

You are now ready to expand on the Symfony skeleton and add bundles as you need them.


## Namespace customization

Change all instances of `App` (case-sensitive) in *all* files (not just .php) with your own namespace, which usually looks like:
>`Author\ProjectName`

Make sure to also change it in `./config/preload.php`, but replace any "\" with "_", for example:
>`Author_ProjectName_KernelProdContainer.preload.php`

Then run:
```shell
composer install
```


## Routes configuration using attributes (or annotations)

Instead of defining routes in the `config\routes.yaml` file, you can define them using attributes (or annotations) in Controllers.

Create a `routes\attributes.yaml` config file with (note that the config filename does not matter):
```yaml
controllers:
    resource: ../../src/Controller/
    type: annotation

kernel:
    resource: ../../src/Kernel.php
    type: annotation
```
In Symfony 5 the `"type: annotation"` works for attributes too. See [Symfony 5.4 docs](https://symfony.com/doc/5.4/routing.html) for more info.

*Note that in Symfony 6 the config file is slightly different, so check out the [Symfony 6 docs](https://symfony.com/doc/6.2/routing.html) for that.*

You might want to run `composer install` again, or simply clear the cache with:
```shell
symfony console cache:clear
```


## API basics

In your Controller functions (actions) you can return different types of responses (like JSON, or HTML), configure allowed methods for your routes, and also add regex validation on your route params. To get access to some shortcuts for HTTP-related features, make your Controllers extend `Symfony\Bundle\FrameworkBundle\Controller\AbstractController`.

To check if a URI (with params) matches a route, use for ex.:
```shell
symfony console router:match /api/orders/1 --method=GET
```


## Twig installation

Install twig bundle with:
```shell
composer require twig
```


## Twig HTML templates

Create templates in the new `templates` directory (or config a new one).

Use `$this->render('template.html.twig')` in Controllers to render a page using the given template.

See [Twig documentation](https://twig.symfony.com/doc/), or use the following command to see all available twig functions, filters and tests:
```shell
symfony console debug:twig
```


## Public assets and generated URLs

You can install the Symfony asset component to manage the URLs of your CSS, JS and image files:
```shell
composer require asset
```

Name your routes for which you want to be able to get their URIs using the "path" function (you can also pass it parameters if using slugs). Works inside of twig templates too.

To view all your routes (and the Web profiler's on a dev env) use:
```shell
symfony console debug:router
```


#Twig extras

You can install extra functionality for Twig, for ex. a markdown converter:
```shell
composer require twig/markdown-extra league/commonmark
```


## Debug tools installation

Install the Symfony Web Profiler and logging (monolog) with:
```shell
composer require debug
```

Access the Web Profiler from the new bar at the bottom of any page, or (for API requests) from:
<https://localhost:8000/_profiler/>

To start the Symfony var dumper server, which is useful for AJAX requests, use:
```shell
symfony console server:dump
```


## Services, autowiring and logging

To see what services are available for use, run:
```shell
symfony console debug:autowiring
```

You can also use a filter with it, for ex.:
```shell
symfony console debug:autowiring log
```
If you use the `LoggerInterface` logger, you will be able to see logs in the Web Profiler and also in `var/log` directory.

You can then use the result to autowire the service with the given typehint (and variable name in case there are multiple instances).

*Note that you could also see all private services with:*
```shell
symfony console debug:container
```

## JavaScript and AJAX basics

Just like with CSS assets, you can also add JS assets, just make sure to use `defer` tag since they are added to the header, to not load them till after the page loads.

Depending on your app's needs, you can get it more frontend tools with bundles like [Encore](https://symfony.com/doc/current/frontend/encore), [Stimulus](https://symfony.com/bundles/StimulusBundle/) and [Turbo](https://symfony.com/bundles/ux-turbo/).


## Webpack Encore installation

You will need [Node.js](https://nodejs.org) and optionally Yarn v1.22, which you can install with (don't install the latest one with corepack, as it won't work):
```shell
npm install --global yarn
```

Then require Encore:
```shell
composer require encore
```

Install with Yarn:
```shell
yarn install
```
Which on success will create the `yarn.lock` file.

To create the build, use:
```shell
yarn build
```

To watch the build, use:
```shell
yarn watch
```


## Webpack Encore usage basics

Instead of using CDNs we can add packages, like `jQuery` and `simple.css`, using:
```shell
yarn add jquery --dev
yarn add simpledotcss --dev
```

Encore will minify the assets for us, amongst other things, so you can import not minified versions of assets.

CSS files can be imported in `assets/styles/app.css` by adding an `import` statement with full-path syntax like: `./path/style.css`, or from the `node_modules` directory with "~", for ex:
```css
@import "~bootstrap";
@import "~some-module/dist/mod.css";
```

JS files can be imported by adding an `import` statement in `assets/app.js`, for example:
```js
import jQuery from 'jquery';
import './topic-vote.js';
```

Both `app.css` and `app.js` can be renamed. Just change `./styles/app.css` in `app.js`, and `.addEntry('app', './assets/app.js')` in `webpack.config.js` to the new filenames.
