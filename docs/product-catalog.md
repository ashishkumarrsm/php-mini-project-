# Product Catalog Documentation

## Overview

The product catalog is the main customer-facing storefront flow. It includes:

- A searchable, paginated product list
- Category-based filtering
- A slug-based product detail page
- Review summary and approved review display
- Add-to-cart actions from both listing and detail screens

The primary implementation lives in:

- `app/Controller/ProductsController.php`
- `app/View/Products/index.ctp`
- `app/View/Products/view.ctp`
- `app/Controller/AppController.php`
- `app/Controller/CartsController.php`
- `app/Model/Product.php`
- `app/Model/Review.php`

## Request Flow

### Product List

`ProductsController::index()` builds a public catalog query with the base condition:

`Product.is_active = 1`

Optional query string filters:

- `q`: searches `Product.name` and `Product.description` with `LIKE`
- `category`: filters by `Product.category_id`

The result is paginated with:

- `limit = 12`
- `order = Product.created DESC`
- `contain = Category`

The action passes these variables to the view:

- `products`
- `categories`
- `title_for_layout = "Discover Products"`

### Product Detail

`ProductsController::view($slug)` fetches one active product by slug and loads:

- `Category`
- `Review`
- `Review.User`

If no matching product is found, the action throws `NotFoundException`.

After loading the product, the controller runs a second query to calculate approved-review statistics:

- average rating
- review count

The action passes these variables to the view:

- `product`
- `ratingData`
- `title_for_layout = product name`

## View Behavior

### `app/View/Products/index.ctp`

The catalog page contains three main sections:

1. A hero block with a simple live count using `count($products)`
2. A GET filter form for search and category
3. A product grid with pagination or an empty state

Each product card shows:

- product image, with `/img/cake.icon.png` fallback
- category name, with `General` fallback
- truncated description
- price
- stock count
- links for `View details` and `Add to cart`

Important view expectations:

- Each product row should include `Product` data
- Category data should be available through the `Category` association
- Product detail links use the product slug

### `app/View/Products/view.ctp`

The product detail page shows:

- product image
- category name, with `Featured` fallback
- product name
- full description
- price
- average rating
- review count
- stock count
- add-to-cart and back-to-shop actions

Review rendering rules:

- reviews are shown only when `product['Review']` is not empty
- only approved reviews are rendered when `is_approved` exists
- if `is_approved` is not present in a review record, the template treats it as displayable

Rating display rules:

- if approved review aggregates exist, average rating is formatted to one decimal place
- otherwise the rating label displays `New`

## Data Model Dependencies

### Product Model

`Product` defines:

- `belongsTo Category`
- `hasMany Review`
- `hasMany OrderItem`
- `actsAs = Containable, Sluggable`

Validation rules:

- `name` must not be blank
- `price` must be numeric
- `category_id` must be numeric
- `stock` must be greater than or equal to zero

### Review Model

`Review` belongs to:

- `Product`
- `User`

Validation rules:

- `rating` must be between 1 and 5
- `body` must be at least 10 characters
- `title` must be less than 150 characters

## Cart Integration

Both catalog templates link to `CartsController::add($productId)`.

Cart behavior:

- cart data is stored in the session under `Cart`
- adding an existing product increases its quantity
- adding a new product stores `name`, `price`, `qty`, and `image`
- after adding, the user is redirected back to the referring page

`AppController::beforeFilter()` also computes `cartCount` from the session so layouts can display the current cart quantity globally.

## Shared Application Context

`AppController::beforeFilter()` affects the catalog indirectly:

- public access is allowed for `index` and `view`
- global `cartCount` is prepared for layouts
- `currentUser` is exposed to all views
- `isAdminArea` is exposed to all views
- unread notifications are counted when a `Notification` model exists

Admin route protection is enforced for prefixed admin actions unless the authenticated user has the `admin` role.

## Admin Product Management

`ProductsController` also includes admin-oriented methods:

- `add()`
- `edit($id)`
- `delete($id)`
- `admin_index()`
- `admin_edit($id)`
- `admin_delete($id)`

Current implementation notes:

- `add()` supports image upload to `app/webroot/img/products/`
- allowed upload MIME types are JPEG, PNG, and WEBP
- uploaded filenames are prefixed with `uniqid()`
- admin-prefixed actions use a separate `admin_` route namespace

## Query Parameters

The public catalog currently supports these query parameters on the listing page:

| Parameter | Example | Purpose |
| --- | --- | --- |
| `q` | `?q=phone` | Search product name and description |
| `category` | `?category=3` | Filter by category id |

These filters can be combined:

`/products?q=phone&category=3`

## Frontend Fallbacks

The templates intentionally provide simple fallbacks to avoid broken UI:

- missing product image -> `/img/cake.icon.png`
- missing category name on list page -> `General`
- missing category name on detail page -> `Featured`
- missing description on list page -> `No description available yet.`
- no approved rating data -> `New`

## Maintenance Notes

When changing this module, keep these dependencies aligned:

- If product detail URLs change, update slug-based links in `index.ctp`
- If review approval behavior changes, update both the aggregate query and detail template conditions
- If cart structure changes, update `AppController::beforeFilter()` because cart count assumes each item has a `qty`
- If new product fields are introduced, confirm both list and detail templates degrade gracefully when data is empty

## Known Implementation Observations

These are not necessarily bugs, but they are worth knowing before further changes:

- `index.ctp` uses `count($products)`, which reflects only the current paginated page, not the total catalog size
- the product detail view loads all product reviews and filters approval at render time, while rating stats are calculated from approved reviews only
- `ProductsController::add()` is labeled as admin-oriented in comments, but the method currently calls `$this->Auth->deny()` rather than explicitly checking for admin role

## Quick File Map

- Listing controller logic: `app/Controller/ProductsController.php`
- Shared auth/context logic: `app/Controller/AppController.php`
- Cart behavior: `app/Controller/CartsController.php`
- Listing template: `app/View/Products/index.ctp`
- Detail template: `app/View/Products/view.ctp`
- Product relationships: `app/Model/Product.php`
- Review validation: `app/Model/Review.php`
