# Installation

If you do not wish to follow the step by step examples, install this app with:
```shell
composer install
yarn install
yarn build
```

You can then access it through a web server on your localhost.

You can use Symfony web server (if you installed the `symfony` binary):
```shell
symfony serve -d
```
And access it at:
`https://localhost:8000`


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

To check if a URI (with params) matches a route, use for example:
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

You can install extra functionality for Twig, for example a markdown converter:
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

You can also use a filter with it, for example:
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

CSS files can be imported in `assets/styles/app.css` by adding an `import` statement with full-path syntax like: `./path/style.css`, or from the `node_modules` directory with "~", for example:
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


## HTTP Client installation

Install Symfony HTTP Client with:
```shell
composer require symfony/http-client -W
```
Note that this service will be loaded by the FrameworkBundle.


## HTTP Client usage basics

Autowire with `Symfony\Contracts\HttpClient\HttpClientInterface` typehint and use the `request` method to make a request.

See [Symfony HTTP Client docs](https://symfony.com/doc/5.4/http_client.html) for more info.


## Caching basics

The `Cache` component is part of FrameworkBundle. It can be configured to use Redis or other adapters, by default it uses the filesystem.

Autowire it with `Symfony\Contracts\Cache\CacheInterface` typehint and use `get` method to get a cached item or, if it doesn't exist or is expired, do the work (maybe do a request or query the DB, etc) to get the item and save it to cache.

When using caching, you will notice a new icon in the Web Profiler toolbar and clicking it will open the "Cache" panel.

To clear only the app's cached items, use:
```shell
symfony console cache:pool:clear cache.app
```

## Configuration basics

To see all configurable bundles, use:
```shell
symfony console config:dump
```
 
If you want to configure any bundle and its services, besides looking at the symfony docs for it, you can run a command to see what options are available:
```shell
symfony console config:dump BundleName
```

You can further fitler that by a config option, for example `cache` from the `FrameworkBundle`:
```shell
symfony console config:dump FrameworkBundle cache
```
Or use the extension alias:
```shell
symfony console config:dump framework cache
```

To see all currently configured values, use `debug:config` instead of `config:dump`, for example:
```shell
symfony console debug:config framework cache
```


## Configuration per environment

You can create configurations that are different per environment (`APP_ENV` in `.env` files, default is `dev`).

Just put the config files in a subfolder matching the environment's name, for example:
* `config/packages/dev`
* `config/routes/prod`

The `services.yaml` config can be overwritten by creating a file per env, for example:
* `config/services_dev.yaml`

Of course you can always use the `when@env` keyword inside the main config too, instead of creating other files.

Note that you don't need to put all values in per-env configs, just the ones you want to overwrite.

On `prod` environment certain features (`require-dev` packages from `composer.json`) are not included, like the Web Profiler.

The YAML and Twig files are cached on all envs but on `dev` envs they are auto-cleared if Symfony detects a change. On `prod` they should be manually cleared when deploying:
```shell
php bin/console cache:clear
php bin/console cache:warmup
```


## Configuration parameters

To see all container parameters, use:
```shell
symfony console debug:container --parameters
```

Global parameters can be configured under `parameters` key in `services.yaml`.


## Services and dependency injection

You can create services anywhere in the `"src"` directory and thanks to `services.yaml` they will be autoloaded, autowired and autoconfigured.

To add a dependency for a service, for example `HttpClientInterface`, add it in its constructor. Autowiring will do the rest, as long as you typehint it correctly (and use the correct parameter name in case of named autowiring).


## Scoped HTTP Clients

You can configure a different set of options per scoped (by host URI) Http Clients in `framework.yaml` config file. See [Symfony HTTP Client docs](https://symfony.com/doc/5.4/http_client.html#scoping-client) for more info.


## Environment variables and secrets

You can see all environment variables configured in `.env` files with:
```shell
symfony console debug:dotenv
```
You can pass it `--env=ENV` argument to show them for "ENV" environment, in case you have multiple files.

If you have sensitive variables, like passwords, keys and so on, you can set them as secrets instead of adding them to `.env.local` files. They will be automatically used, but note that variables defined in `.env` overwrite them, so make sure to remove them from those files.

To set a secret, for "ENV" environment, use:
```shell
symfony console secrets:set VARIABLE_NAME --env=ENV
```

To list secrets of "ENV" environment, and also reveal them, use:
```shell
symfony console secrets:list --reveal --env=ENV
```

Note that the `prod.decrypt.private.php` key is ignored by git on purpose as it should not be commited. It is needed for decryption and should be added manually to any `prod` environments.


## Maker bundle installation

Install with:
```shell
composer require maker --dev
```

You will get a set of new console commands with which you can make things like Commands and Entities:
```shell
symfony console make:
```
