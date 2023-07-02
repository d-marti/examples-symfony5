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
