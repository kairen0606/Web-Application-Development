<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Your Cart - PR ind</title>
	<link rel="stylesheet" href="../Styles/style.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
	<?php include '../includes/header.php'; ?>

	<section class="cart-hero">
		<div class="container">
			<h1>Your Cart</h1>
			<p>Review your items and complete your purchase.</p>
		</div>
	</section>

	<section class="cart-section">
		<div class="container cart-grid">
			<div class="cart-items">
				<table class="cart-table">
					<thead>
						<tr>
							<th>Product</th>
							<th>Price</th>
							<th>Qty</th>
							<th>Subtotal</th>
							<th></th>
						</tr>
					</thead>
					<tbody id="cart-body">
						<tr id="cart-empty-row"><td colspan="5" style="text-align:center; padding: 24px; color:#666;">Your cart is empty.</td></tr>
					</tbody>
				</table>
			</div>

			<aside class="cart-summary">
				<h2>Order Summary</h2>
				<div class="summary-row"><span>Items total</span><span id="items-total">RM 0.00</span></div>
				<div class="summary-row"><span>Shipping</span><span id="shipping">RM 0.00</span></div>
				<hr>
				<div class="summary-total"><span>Total</span><span id="grand-total">RM 0.00</span></div>

				<div class="payment-methods">
					<h3>Payment Method</h3>
					<label class="pay-option"><input type="radio" name="pay" value="online_banking" checked> <span>Online Banking (FPX)</span></label>
					<label class="pay-option"><input type="radio" name="pay" value="tng"> <span>TNG eWallet</span></label>
					<label class="pay-option"><input type="radio" name="pay" value="grabpay"> <span>GrabPay</span></label>
				</div>

				<button id="checkout-btn" class="checkout-btn">Proceed to Pay</button>
				<p id="checkout-msg" class="checkout-msg" style="display:none;"></p>
			</aside>
		</div>
	</section>

	<?php include '../includes/footer.php'; ?>
	<div class="copyright">
		<p>© 2025 MyWebsite. All rights reserved.</p>
	</div>

	<style>
		.cart-hero { margin-top: 90px; padding: 60px 20px; text-align:center; background:#f7f8fb; }
		.cart-hero h1 { margin:0; font-size:36px; }
		.cart-section { padding: 30px 20px 80px; }
		.container { max-width: 1100px; margin: 0 auto; }
		.cart-grid { display: grid; grid-template-columns: 1fr 320px; gap: 24px; }
		.cart-items { overflow-x:auto; }
		.cart-table { width:100%; border-collapse: collapse; background:#fff; border:1px solid #eee; border-radius: 10px; }
		.cart-table th, .cart-table td { padding: 14px; border-bottom:1px solid #f0f0f0; text-align:left; }
		.cart-table th { background:#fafafa; font-weight:700; }
		.cart-item { vertical-align: middle; }
		.cart-item-title { display:flex; align-items:center; gap:12px; }
		.cart-item-title img { width:60px; height:60px; object-fit:cover; border-radius:8px; border:1px solid #eee; }
		.qty-input { width:60px; padding:8px; text-align:center; border:1px solid #e5e7eb; border-radius:8px; }
		.remove-btn { background: none; border:none; color:#e11d48; cursor:pointer; }
		.cart-summary { background:#fff; border:1px solid #eee; border-radius: 10px; padding: 16px; height: fit-content; }
		.summary-row, .summary-total { display:flex; justify-content: space-between; margin: 8px 0; }
		.summary-total { font-size:18px; font-weight:700; }
		.payment-methods { margin-top: 12px; }
		.pay-option { display:block; margin:8px 0; }
		.checkout-btn { width:100%; margin-top:16px; padding:12px 14px; background:#111; color:#fff; border:none; border-radius:10px; cursor:pointer; }
		.checkout-btn:hover { opacity:.9; }
		.checkout-msg { margin-top:10px; color:#16a34a; }
		@media (max-width: 900px) { .cart-grid { grid-template-columns: 1fr; } }
	</style>

	<script>
		// Minimal client-side cart using localStorage key: 'cartItems'
		// Expected item shape: { id, name, price, quantity, image }
		function readCart() {
			try {
				return JSON.parse(localStorage.getItem('cartItems') || '[]');
			} catch (e) { return []; }
		}
		function writeCart(items) {
			localStorage.setItem('cartItems', JSON.stringify(items));
		}
		function formatRM(value) {
			return 'RM ' + (Math.round(value * 100) / 100).toFixed(2);
		}

		function renderCart() {
			const tbody = document.getElementById('cart-body');
			const emptyRow = document.getElementById('cart-empty-row');
			const items = readCart();
			tbody.innerHTML = '';

			if (!items.length) {
				tbody.appendChild(emptyRow);
				emptyRow.style.display = '';
				updateTotals(items);
				return;
			}
			emptyRow.style.display = 'none';

			items.forEach((item, index) => {
				const tr = document.createElement('tr');
				tr.className = 'cart-item';
				tr.innerHTML = `
					<td>
						<div class="cart-item-title">
							<img src="${item.image || '../img/item.png'}" alt="${item.name}">
							<div>
								<div>${item.name}</div>
								<small>ID: ${item.id}</small>
							</div>
						</div>
					</td>
					<td>${formatRM(item.price)}</td>
					<td><input class="qty-input" type="number" min="1" value="${item.quantity || 1}" data-index="${index}"></td>
					<td class="line-subtotal">${formatRM((item.quantity || 1) * item.price)}</td>
					<td><button class="remove-btn" data-index="${index}"><i class="fa-solid fa-trash"></i></button></td>
				`;
				tbody.appendChild(tr);
			});

			bindCartEvents();
			updateTotals(items);
		}

		function bindCartEvents() {
			const qtyInputs = document.querySelectorAll('.qty-input');
			qtyInputs.forEach(input => {
				input.addEventListener('change', (e) => {
					let items = readCart();
					const idx = parseInt(e.target.getAttribute('data-index'));
					const val = Math.max(1, parseInt(e.target.value || '1'));
					e.target.value = val;
					items[idx].quantity = val;
					writeCart(items);
					renderCart();
				});
			});

			const removeBtns = document.querySelectorAll('.remove-btn');
			removeBtns.forEach(btn => {
				btn.addEventListener('click', (e) => {
					let items = readCart();
					const idx = parseInt(e.currentTarget.getAttribute('data-index'));
					items.splice(idx, 1);
					writeCart(items);
					renderCart();
				});
			});
		}

		function updateTotals(items) {
			const itemsTotal = items.reduce((sum, it) => sum + (it.price * (it.quantity || 1)), 0);
			document.getElementById('items-total').textContent = formatRM(itemsTotal);
			document.getElementById('shipping').textContent = formatRM(0);
			document.getElementById('grand-total').textContent = formatRM(itemsTotal);
		}

		document.addEventListener('DOMContentLoaded', () => {
			renderCart();
			document.getElementById('checkout-btn').addEventListener('click', () => {
				const items = readCart();
				if (!items.length) { alert('Your cart is empty.'); return; }
				const method = document.querySelector('input[name="pay"]:checked').value;
				const msg = document.getElementById('checkout-msg');
				msg.textContent = 'Redirecting to ' + (method === 'tng' ? 'TNG eWallet' : method === 'grabpay' ? 'GrabPay' : 'Online Banking (FPX)') + ' checkout... (demo)';
				msg.style.display = '';
				setTimeout(() => { alert('Payment successful (demo). Thank you!'); localStorage.removeItem('cartItems'); location.reload(); }, 1200);
			});
		});
	</script>

</body>

</html>


