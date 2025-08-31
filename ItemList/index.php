<?php
// Example dataset (you can replace with database later)
$items = [
  1 => [
    "title" => "P.R IND Rackets",
    "description" => "Lightweight & powerful racket for advanced players.",
    "image" => "PR.jpg"
  ],
  2 => [
    "title" => "Li-Ning Turbo Shoes",
    "description" => "Comfort and agility for every match.",
    "image" => "LiningTBS.jpg"
  ],
  3 => [
    "title" => "Yonex Shuttles",
    "description" => "Durable feather shuttlecocks for tournaments.",
    "image" => "YonexS.jpg"
  ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Item Listing</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f9f9f9; margin: 0; padding: 20px; }
    h1 { text-align: center; margin-bottom: 20px; }
    .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
    .card { background: #fff; border-radius: 8px; padding: 1rem; box-shadow: 0 4px 10px rgba(0,0,0,0.1); text-align: center; }
    .card img { width: 100%; height: 500px; object-fit: cover; border-radius: 6px; }
    .card h3 { margin: 10px 0; }
    .btn { display: inline-block; padding: 8px 14px; background: #ffcc00; border-radius: 5px; text-decoration: none; color: #111; font-weight: bold; }
    .btn:hover { background: #ffaa00; }
  </style>
</head>
<body>
  <h1>Available Products</h1>
  <div class="grid">
    <?php foreach($items as $id => $item): ?>
      <div class="card">
        <img src="<?= $item['image'] ?>" alt="<?= $item['title'] ?>">
        <h3><?= $item['title'] ?></h3>
        <p><?= $item['description'] ?></p>
        <a href="details.php?id=<?= $id ?>" class="btn">View Details</a>
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>
 