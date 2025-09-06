<?php
session_start();

// Items grouped by category ID
$categories = [
  1 => [
    [
      "title" => "P.R IND Racket Model A",
      "description" => "Lightweight racket for speed.",
      "image" => "../ItemList/PR.jpg",
      "price" => "RM180"
    ],
    [
      "title" => "P.R IND Racket Model B",
      "description" => "Powerful racket for advanced players.",
      "image" => "../ItemList/PR.jpg",
      "price" => "RM200"
    ]
  ],
  2 => [
    [
      "title" => "Li-Ning Turbo Shoes Red",
      "description" => "Comfortable red shoes for matches.",
      "image" => "../ItemList/LiningTBS.jpg",
      "price" => "RM120"
    ],
    [
      "title" => "Li-Ning Turbo Shoes Blue",
      "description" => "Agile blue shoes for quick movement.",
      "image" => "../ItemList/LiningTBS.jpg",
      "price" => "RM130"
    ]
  ],
  3 => [
    [
      "title" => "Yonex Tournament Shuttles",
      "description" => "Durable feather shuttlecocks.",
      "image" => "../ItemList/YonexS.jpg",
      "price" => "RM30"
    ],
    [
      "title" => "Yonex Practice Shuttles",
      "description" => "Affordable shuttles for practice.",
      "image" => "../ItemList/YonexS.jpg",
      "price" => "RM20"
    ]
  ]
];

// Get category ID from URL
$catId = $_GET['id'] ?? null;

// Handle add to cart
if (isset($_GET['add'])) {
  $addId = $_GET['add'];
  $_SESSION['cart'][] = $addId;
  $msg = "Item added to cart!";
}

// Show items for selected category
if (!$catId || !isset($categories[$catId])) {
  echo "Category not found!";
  exit;
}
$items = $categories[$catId];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Category Details</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f9f9f9; margin: 0; padding: 20px; }
    .container { max-width: 1200px; margin: auto; }
    .items-row { display: flex; gap: 20px; flex-wrap: wrap; }
    .card { background: #fff; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); padding: 20px; width: 300px; display: flex; flex-direction: column; align-items: center; margin-bottom: 20px; }
    .card img { width: 100%; max-width: 250px; border-radius: 10px; margin-bottom: 20px; }
    .item-title { font-size: 1.2rem; margin-bottom: 10px; text-align: center; }
    .item-desc { margin-bottom: 10px; text-align: center; }
    .item-price { color: #e67e22; font-weight: bold; margin-bottom: 10px; text-align: center; }
    .btn { display: inline-block; padding: 8px 14px; background: #ffcc00; border-radius: 5px; text-decoration: none; color: #111; font-weight: bold; margin-bottom: 10px;}
    .btn:hover { background: #ffaa00; }
    .msg { color: green; margin-bottom: 15px; text-align: center; }
    .cart { margin-bottom: 20px; text-align: center; }
  </style>
</head>
<body>
  <div class="container">
    <?php if (!empty($msg)): ?>
      <div class="msg"><?= $msg ?></div>
    <?php endif; ?>

    <div class="cart">
      <strong>Cart:</strong>
      <?php
        $cart = $_SESSION['cart'] ?? [];
        if ($cart) {
          echo count($cart) . " item(s)";
        } else {
          echo "Empty";
        }
      ?>
    </div>

    <div class="items-row">
      <?php foreach ($items as $index => $item): ?>
        <div class="card">
          <img src="<?= $item['image'] ?>" alt="<?= $item['title'] ?>">
          <div class="item-title"><?= $item['title'] ?></div>
          <div class="item-desc"><?= $item['description'] ?></div>
          <div class="item-price"><?= $item['price'] ?></div>
          <a href="details.php?id=<?= $catId ?>&add=<?= $catId . '-' . $index ?>" class="btn">Add to Cart</a>
        </div>
      <?php endforeach; ?>
    </div>
    <a href="../ItemList/index.php" class="btn">Back to Categories</a>
  </div>
</body>
</html>