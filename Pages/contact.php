<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Contact Us - PR ind</title>
	<link rel="stylesheet" href="../Styles/style.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
	<?php include '../includes/header.php'; ?>

	<section class="contact-hero">
		<div class="container">
			<h1>PR IND Berhad</h1>
			<p>Questions, feedback, or collaborations? We're just a message away.</p>
		</div>
	</section>

	<section class="contact-content">
		<div class="container">
			<div class="contact-card">
				<h2>Contact now with us!</h2>
				<p>Email: <a href="mailto:prind@gmail.com">prind@gmail.com</a></p>
				<p>Phone: <a href="tel:+0395433366">03-95433366</a></p>
				<p>Business hours: Mon–Fri, 9:00–18:00</p>
			</div>

			<div class="socials">
				<h3>Connect with us</h3>
				<div class="social-links">
					<a class="social-btn" href="https://www.facebook.com/profile.php?id=61555868033402" target="_blank" rel="noopener" aria-label="Facebook">
						<i class="fa-brands fa-facebook-f"></i>
						<span>Facebook</span>
					</a>
					<a class="social-btn" href="https://www.instagram.com/sportsdirectmy/" target="_blank" rel="noopener" aria-label="Instagram">
						<i class="fa-brands fa-instagram"></i>
						<span>Instagram</span>
					</a>
					<a class="social-btn" href="https://www.tiktok.com/@sportsdirectmy?lang=en" target="_blank" rel="noopener" aria-label="TikTok">
						<i class="fa-brands fa-tiktok"></i>
						<span>TikTok</span>
					</a>
				</div>
			</div>
		</div>
	</section>

	<?php include '../includes/footer.php'; ?>
	<div class="copyright">
		<p>© 2025 MyWebsite. All rights reserved.</p>
	</div>

	<style>
		.contact-hero { margin-top: 90px; padding: 80px 20px; text-align: center; background: #f7f8fb; }
		.contact-hero h1 { margin: 0; font-size: 40px; }
		.contact-hero p { margin-top: 10px; color: #666; }

		.contact-content { padding: 40px 20px 80px; }
		.container { max-width: 1100px; margin: 0 auto; }
		.contact-card { background: #fff; border: 1px solid #eee; border-radius: 10px; padding: 24px; margin-bottom: 24px; }
		.contact-card h2 { margin-top: 0; }

		.socials h3 { margin-bottom: 12px; }
		.social-links { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; }
		.social-btn { display: flex; align-items: center; gap: 10px; padding: 12px 14px; border: 1px solid #e5e7eb; border-radius: 10px; background: #fff; color: #111; text-decoration: none; transition: transform .15s ease, box-shadow .15s ease; }
		.social-btn i { width: 22px; font-size: 18px; }
		.social-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,.06); }
		@media (max-width: 640px) { .contact-hero { padding: 60px 16px; } .contact-hero h1 { font-size: 32px; } }
	</style>

</body>

</html>


