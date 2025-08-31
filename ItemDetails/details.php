<?php

$items = [
  1 => [
    "title" => "P.R IND Rackets",
    "description" => "Lightweight & powerful racket for advanced players.",
    "image" => "PR.jpg"
    //"price" => "$180",
    //"metadata" => "Category: Rackets | Brand: Yonex"
  ],
  2 => [
    "title" => "Li-Ning Turbo Shoes",
    "description" => "Comfort and agility for every match.",
    "image" => "../ItemList/LiningTBS.jpg",
    //"price" => "$120",
    //"metadata" => "Category: Shoes | Brand: Li-Ning"
  ],
  3 => [
    "title" => "Yonex Shuttles",
    "description" => "Durable feather shuttlecocks for tournaments.",
    "image" => "../ItemList/YonexS.jpg",
    //"price" => "$30",
   // "metadata" => "Category: Shuttles | Brand: Yonex"
  ]
];

// Get item ID from URL
$id = $_GET['id'] ?? null;

// Check if item exists
if(!$id || !isset($items[$id])) {
  die("Item not found!");
}
$item = $items[$id];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $item['title'] ?></title>
  <style>
    body { font-family: Arial, sans-serif; background: #f9f9f9; margin: 0; padding: 20px; }
    .container { max-width: 800px; margin: auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    img { max-width: 100%; border-radius: 10px; margin-bottom: 20px; }
    h2 { margin: 0 0 10px; }
    .price { font-size: 1.5rem; color: #e67e22; margin-bottom: 10px; }
    .btn { display: inline-block; padding: 10px 16px; background: #ffcc00; border-radius: 5px; text-decoration: none; color: #111; font-weight: bold; margin-top: 10px; }
    .btn:hover { background: #ffaa00; }
    .reviews { margin-top: 30px; }
    .review { background: #f1f1f1; padding: 10px; border-radius: 5px; margin-bottom: 10px; }
  </style>
</head>
<body>
  <div class="container">
    <h2><?= $item['title'] ?></h2>
    <img src="<?= $item['image'] ?>" alt="<?= $item['title'] ?>">
    <p class="price"><?= $item['price'] ?></p>
    <p><strong><?= $item['metadata'] ?></strong></p>
    <p><?= $item['description'] ?></p>

    <a href="listing.php" class="btn">⬅ Back to Listing</a>
    <a href="#" class="btn">Add to Cart</a>

    <div class="reviews">
      <h3>User Reviews</h3>
      <div class="review"><strong>⭐️⭐️⭐️⭐️⭐️ John D.</strong><br>Excellent quality racket!</div>
      <div class="review"><strong>⭐️⭐️⭐️⭐️ Maria L.</strong><br>Very durable and easy to handle.</div>
    </div>
  </div>
</body>
</html>
