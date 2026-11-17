
<?php
require_once "config/db.php";

// Fetch products from database
try {

    $stmt = $conn->query(
        "SELECT * FROM products WHERE status = 'active' ORDER BY id DESC"
    );

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    $products = [];
    $error = "Unable to load products.";

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Shop | Sufi Sweets</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-sufi sticky-top shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="index.html">🍬 Sufi <span>Sweets</span></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
        <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
        <li class="nav-item"><a class="nav-link active" href="products.html">Shop</a></li>
        <li class="nav-item"><a class="nav-link" href="about.html">About</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="dashboard.html">Dashboard</a></li>
        <li class="nav-item">
          <a class="nav-link position-relative" href="cart.html">
            Cart <span class="badge rounded-pill bg-warning text-dark cart-count">0</span>
          </a>
        </li>
        <li class="nav-item ms-lg-2"><a class="btn btn-sufi btn-sm px-3" href="login.html">Login</a></li>
        <li class="nav-item"><a class="btn btn-outline-light btn-sm px-3" href="register.html">Register</a></li>
      </ul>
    </div>
  </div>
</nav>

<main>

  <!-- Page header -->
  <section class="page-header">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-2">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active text-white-50" aria-current="page">Shop</li>
        </ol>
      </nav>
      <h1 class="fw-bold mb-1">Our Products</h1>
      <p class="mb-0 opacity-75">Fresh sweets, dairy products and gift boxes — made daily.</p>
    </div>
  </section>

  <section class="section">
    <div class="container">

      <!-- Toolbar: filter buttons + search form -->
      <div class="row g-3 align-items-center mb-4">
        <div class="col-lg-7">
          <div class="d-flex flex-wrap gap-2" id="filterBar">
            <button class="btn btn-sufi active" data-filter="all">All Products</button>
            <button class="btn btn-outline-sufi" data-filter="sweets">Sweets</button>
            <button class="btn btn-outline-sufi" data-filter="dairy">Dairy</button>
            <button class="btn btn-outline-sufi" data-filter="gifts">Gift Boxes</button>
          </div>
        </div>
        <div class="col-lg-5">
          <form class="d-flex" onsubmit="return false;">
            <input type="search" class="form-control" id="productSearch"
                   placeholder="Search products…" aria-label="Search products">
          </form>
        </div>
      </div>

      <p class="text-muted small">
        Showing <strong id="resultCount">12</strong> product(s)
      </p>

<!-- Product grid -->
<div class="row g-4" id="productGrid">

    <?php foreach ($products as $product): ?>

  
<div class="col-sm-6 col-lg-4 col-xl-3 product-col"
     data-id="<?= (int)$product['id'] ?>"
     data-name="<?= htmlspecialchars($product['name']) ?>"
     data-price="<?= (float)$product['price'] ?>"
     data-category="sweets"
     data-emoji="🍬"
     data-desc="<?= htmlspecialchars($product['description']) ?>">

    <div class="card card-sufi h-100">

        <div class="product-thumb">
            <img src="images/<?= rawurlencode($product['image']) ?>"
                 alt="<?= htmlspecialchars($product['name']) ?>"
                 class="img-fluid">
        </div>

        <div class="card-body d-flex flex-column">

            <span class="badge bg-light text-dark mb-2">
                Sweets
            </span>

            <h6 class="card-title">
                <?= htmlspecialchars($product['name']) ?>
            </h6>

            <p class="small text-muted">
                <?= htmlspecialchars($product['description']) ?>
            </p>

            <div class="mt-auto d-flex justify-content-between align-items-center">

                <span class="price">
                    Rs. <?= number_format((float)$product['price'], 2) ?>
                </span>

                <button class="btn btn-sufi add-to-cart"
                        type="button"
                        <?= (int)$product['stock'] <= 0 ? 'disabled' : '' ?>>
                    Add
                </button>

            </div>
        </div>
    </div>
</div>


    <?php endforeach; ?>

</div>
      <!-- No results -->
      <div id="noResults" class="text-center py-5 d-none">
        <div class="display-5 mb-3">🔍</div>
        <h5>No products found</h5>
        <p class="text-muted">Try a different search term or category.</p>
      </div>

      <!-- ================= BULK ORDER PRICE TABLE ================= -->
      <div class="card card-sufi mt-5">
        <div class="card-body p-4">
          <h4 class="section-title mb-3">Bulk Order Price List</h4>
          <p class="text-muted small">Special rates for weddings, corporate events and large orders.</p>
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th scope="col">Product</th>
                  <th scope="col">Category</th>
                  <th scope="col" class="text-end">Retail Price</th>
                  <th scope="col" class="text-end">Bulk Price (5 kg+)</th>
                  <th scope="col" class="text-center">Availability</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Gulab Jamun</td><td>Sweets</td>
                  <td class="text-end">Rs. 1,200 / kg</td>
                  <td class="text-end fw-semibold">Rs. 1,050 / kg</td>
                  <td class="text-center"><span class="badge bg-success">In Stock</span></td>
                </tr>
                <tr>
                  <td>Kaju Katli</td><td>Sweets</td>
                  <td class="text-end">Rs. 3,600 / kg</td>
                  <td class="text-end fw-semibold">Rs. 3,250 / kg</td>
                  <td class="text-center"><span class="badge bg-success">In Stock</span></td>
                </tr>
                <tr>
                  <td>Motichoor Ladoo</td><td>Sweets</td>
                  <td class="text-end">Rs. 1,100 / kg</td>
                  <td class="text-end fw-semibold">Rs. 980 / kg</td>
                  <td class="text-center"><span class="badge bg-success">In Stock</span></td>
                </tr>
                <tr>
                  <td>Pure Desi Ghee</td><td>Dairy</td>
                  <td class="text-end">Rs. 2,600 / kg</td>
                  <td class="text-end fw-semibold">Rs. 2,400 / kg</td>
                  <td class="text-center"><span class="badge bg-warning text-dark">Limited</span></td>
                </tr>
                <tr>
                  <td>Fresh Paneer</td><td>Dairy</td>
                  <td class="text-end">Rs. 1,500 / kg</td>
                  <td class="text-end fw-semibold">Rs. 1,350 / kg</td>
                  <td class="text-center"><span class="badge bg-success">In Stock</span></td>
                </tr>
                <tr>
                  <td>Royal Gift Box</td><td>Gifts</td>
                  <td class="text-end">Rs. 3,500 / box</td>
                  <td class="text-end fw-semibold">Rs. 3,100 / box</td>
                  <td class="text-center"><span class="badge bg-danger">Pre-order</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </section>

</main>

<footer class="footer-sufi pt-5 pb-3 mt-4">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4 col-md-6">
        <h5 class="mb-3">🍬 Sufi Sweets</h5>
        <p class="small">Traditional sweets and dairy products made with pure desi ghee since 1994.</p>
      </div>
      <div class="col-lg-2 col-md-6">
        <h6 class="mb-3">Quick Links</h6>
        <ul class="list-unstyled small d-grid gap-2">
          <li><a href="index.html">Home</a></li>
          <li><a href="products.html">Shop</a></li>
          <li><a href="about.html">About Us</a></li>
          <li><a href="contact.html">Contact</a></li>
        </ul>
      </div>
      <div class="col-lg-3 col-md-6">
        <h6 class="mb-3">My Account</h6>
        <ul class="list-unstyled small d-grid gap-2">
          <li><a href="login.html">Login</a></li>
          <li><a href="register.html">Register</a></li>
          <li><a href="cart.html">Shopping Cart</a></li>
          <li><a href="dashboard.html">Dashboard</a></li>
        </ul>
      </div>
      <div class="col-lg-3 col-md-6">
        <h6 class="mb-3">Contact Us</h6>
        <ul class="list-unstyled small d-grid gap-2">
          <li>📍 12-B, Food Street, Lahore</li>
          <li>📞 +92 300 1234567</li>
          <li>✉️ info@sufisweets.com</li>
        </ul>
      </div>
    </div>
    <hr class="border-light opacity-25 mt-4">
    <p class="text-center small mb-0">© 2026 Sufi Sweets. All rights reserved.</p>
  </div>
</footer>

<!-- Quick View Modal -->
<div class="modal fade" id="quickViewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Product Quick View</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-4">
          <div class="col-md-5">
            <div class="product-thumb rounded" id="qvEmoji" style="font-size:5rem;height:220px;">🍮</div>
          </div>
          <div class="col-md-7">
            <span class="badge bg-light text-dark mb-2" id="qvCat">Sweets</span>
            <h4 id="qvName">Product</h4>
            <h3 class="price mb-3" id="qvPrice">Rs. 0</h3>
            <p id="qvDesc" class="text-muted"></p>
            <ul class="list-unstyled small">
              <li>✔ Prepared fresh daily</li>
              <li>✔ Pure desi ghee</li>
              <li>✔ Same-day delivery available</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-sufi" id="qvAdd">Add to Cart</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>