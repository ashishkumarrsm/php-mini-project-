# MyApp Storefront

This repository is a CakePHP 2.x storefront application with a public product catalog, cart flow, reviews, order handling, and an admin area.

## Project Docs

- [Product Catalog Documentation](docs/product-catalog.md) - Detailed documentation for the product listing and product detail flow.

## Core Areas

- Public catalog browsing is handled in `ProductsController::index()` and `ProductsController::view()`.
- Shared auth, cart count, notification count, and admin access checks are handled in `AppController::beforeFilter()`.
- Cart session management is handled in `CartsController`.
- Product/review relationships and validation live in `Product` and `Review` models.

## Stack

- PHP with CakePHP 2.x MVC conventions
- Server-rendered `.ctp` templates
- Session-based cart storage
- CakePHP Auth, Flash, Session, and Paginator components

## Notes

- Product detail pages are slug-based.
- Product listing supports search and category filtering through query string parameters.
- Product review stats are calculated from approved reviews only.

## Framework References

- [CakePHP](https://cakephp.org)
- [CakePHP 2.x Cookbook](https://book.cakephp.org/2.0/en/index.html)
- [CakePHP API](https://api.cakephp.org/2.10/)
