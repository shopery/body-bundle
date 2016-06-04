shopery/body-bundle
===================

Process request body and injects it into Symfony2 controllers.

## Installation
You can install this library:

- Install via [composer](https://getcomposer.org): `composer require shopery/body-bundle`
- Use the [official Git repository](https://github.com/shopery/body-bundle): `git clone https://github.com/shopery/body-bundle`.

And add the bundle to your kernel as usual.

If you have any trouble, please refer to the [symfony docs](http://symfony.com/doc/current/cookbook/bundles/installation.html).

## Usage

Just type-hint any parameter to `Shopery\Bundle\BodyBundle\Request\Body` in your symfony controller and your ready to go.
