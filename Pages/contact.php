<?php
	$errors = [];
	$name = '';
	$email = '';
	$phone = '';
	$salutation = '';
	$enquiryType = '';
	$subject = '';
	$success = false;

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$salutation = trim($_POST['sal'] ?? '');
		$name = trim($_POST['name'] ?? '');
		$email = trim($_POST['email'] ?? '');
		$phone = trim($_POST['phone'] ?? '');
		$enquiryType = trim($_POST['enquiry'] ?? '');
		$subject = trim($_POST['subject'] ?? '');

		if ($salutation === '') {
			$errors['sal'] = 'Please select your salutation';
		}
		if ($name === '') {
			$errors['name'] = 'Please enter your name';
		}
		if ($email === '') {
			$errors['email'] = 'Please enter your email address';
		} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors['email'] = 'Please enter a valid email address';
		}
		if ($phone === '') {
			$errors['phone'] = 'Please enter your phone number';
		} elseif (!preg_match('/^\d{10,15}$/', $phone)) {
			$errors['phone'] = 'Enter a valid phone number (10-15 digits).';
		}
		if ($enquiryType === '') {
			$errors['enquiry'] = 'Please select the type of enquiry';
		}
		if ($subject === '') {
			$errors['subject'] = 'Please enter your message';
		}

		$success = empty($errors);
	}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Contact Us - PR ind</title>
	<link rel="stylesheet" href="../Styles/style.css">
	<link rel="stylesheet" href="../Styles/contact.css">
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

			<div class="contact-card">
				<h3>Visit Our Store</h3>
				<p>Find us at our main location in Kuala Lumpur</p>
				<div class="map-container">
					<iframe 
						src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3983.7899999999998!2d101.686855!3d3.139003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc37d12d669c1f%3A0x8e4a7c1f0d1d1d1d!2sKuala%20Lumpur%20City%20Centre!5e0!3m2!1sen!2smy!4v1640995200000!5m2!1sen!2smy" 
						width="100%" 
						height="300" 
						style="border:0;" 
						allowfullscreen="" 
						loading="lazy" 
						referrerpolicy="no-referrer-when-downgrade"
						title="PR IND Store Location">
					</iframe>
				</div>
				<div class="location-info">
					<p><strong>Address:</strong> Kuala Lumpur City Centre, Malaysia</p>
					<p><strong>Nearest Station:</strong> KLCC LRT Station (5 min walk)</p>
					<p><strong>Parking:</strong> Available at KLCC Mall</p>
				</div>
			</div>

			<?php if ($success): ?>
				<div class="contact-card" role="status" aria-live="polite">
					<h3>Thank you for your enquiry</h3>
					<p>We will get back to you as soon as possible. Below are your details:</p>
					<ul>
						<li><strong>Salutation</strong>: <?php echo htmlspecialchars($salutation); ?></li>
						<li><strong>Name</strong>: <?php echo htmlspecialchars($name); ?></li>
						<li><strong>Email</strong>: <?php echo htmlspecialchars($email); ?></li>
						<li><strong>Phone</strong>: <?php echo htmlspecialchars($phone); ?></li>
						<li><strong>Enquiry</strong>: <?php echo htmlspecialchars($enquiryType); ?></li>
						<li><strong>Message</strong>: <?php echo nl2br(htmlspecialchars($subject)); ?></li>
					</ul>
				</div>
			<?php else: ?>
				<div class="contact-card">
					<h3>Send us a message</h3>
					<form id="contactForm" method="post" novalidate>
						<div class="form-grid">
							<div class="form-field">
								<label for="sal">Salutation</label>
								<select id="sal" name="sal">
									<option value="">Select</option>
									<option value="Mr." <?php echo $salutation==='Mr.'?'selected':''; ?>>Mr.</option>
									<option value="Ms." <?php echo $salutation==='Ms.'?'selected':''; ?>>Ms.</option>
									<option value="Mrs." <?php echo $salutation==='Mrs.'?'selected':''; ?>>Mrs.</option>
									<option value="Mdm." <?php echo $salutation==='Mdm.'?'selected':''; ?>>Mdm.</option>
								</select>
								<div class="field-error"><?php echo $errors['sal'] ?? ''; ?></div>
							</div>

							<div class="form-field">
								<label for="name">Name</label>
								<input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
								<div class="field-error"><?php echo $errors['name'] ?? ''; ?></div>
							</div>

							<div class="form-field">
								<label for="email">Email</label>
								<input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
								<div class="field-error"><?php echo $errors['email'] ?? ''; ?></div>
							</div>

							<div class="form-field">
								<label for="phone">Phone Number</label>
								<input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" placeholder="e.g. 0123456789">
								<div class="field-error"><?php echo $errors['phone'] ?? ''; ?></div>
							</div>

							<div class="form-field">
								<label>Type of Enquiry</label>
								<div class="enquiry-options">
									<label><input type="radio" name="enquiry" value="General" <?php echo $enquiryType==='General'?'checked':''; ?>> General</label>
									<label><input type="radio" name="enquiry" value="Complaints" <?php echo $enquiryType==='Complaints'?'checked':''; ?>> Complaints</label>
									<label><input type="radio" name="enquiry" value="Suggestions" <?php echo $enquiryType==='Suggestions'?'checked':''; ?>> Suggestions</label>
								</div>
								<div class="field-error"><?php echo $errors['enquiry'] ?? ''; ?></div>
							</div>

							<div class="form-field">
								<label for="subject">Message</label>
								<textarea id="subject" name="subject" rows="6" placeholder="Your message..."><?php echo htmlspecialchars($subject); ?></textarea>
								<div class="field-error"><?php echo $errors['subject'] ?? ''; ?></div>
							</div>

							<div class="form-actions">
								<button type="submit" class="submit-btn">Submit</button>
							</div>
						</div>
					</form>
				</div>
			<?php endif; ?>

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


	<script>
		(function() {
			var form = document.getElementById('contactForm');
			if (!form) return;
			form.addEventListener('submit', function(e) {
				var errors = 0;
				function setError(id, message) {
					var field = document.getElementById(id);
					var err = field && field.parentElement ? field.parentElement.querySelector('.field-error') : null;
					if (err) err.textContent = message || '';
				}

				var sal = document.getElementById('sal');
				if (!sal.value) { setError('sal', 'Please select your salutation'); errors++; } else { setError('sal', ''); }

				var name = document.getElementById('name');
				if (!name.value.trim()) { setError('name', 'Please enter your name'); errors++; } else { setError('name', ''); }

				var email = document.getElementById('email');
				var emailOk = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/.test(email.value.trim());
				if (!email.value.trim()) { setError('email', 'Please enter your email address'); errors++; }
				else if (!emailOk) { setError('email', 'Please enter a valid email address'); errors++; } else { setError('email', ''); }

				var phone = document.getElementById('phone');
				var phoneOk = /^\d{10,15}$/.test(phone.value.trim());
				if (!phone.value.trim()) { setError('phone', 'Please enter your phone number'); errors++; }
				else if (!phoneOk) { setError('phone', 'Enter a valid phone number (10-15 digits).'); errors++; } else { setError('phone', ''); }

				var enquiryChecked = !!document.querySelector('input[name="enquiry"]:checked');
				var enquiryErrorEl = document.querySelector('.enquiry-options') && document.querySelector('.enquiry-options').parentElement ? document.querySelector('.enquiry-options').parentElement.querySelector('.field-error') : null;
				if (!enquiryChecked) { if (enquiryErrorEl) enquiryErrorEl.textContent = 'Please select the type of enquiry'; errors++; } else { if (enquiryErrorEl) enquiryErrorEl.textContent = ''; }

				var subject = document.getElementById('subject');
				if (!subject.value.trim()) { setError('subject', 'Please enter your message'); errors++; } else { setError('subject', ''); }

				if (errors > 0) {
					e.preventDefault();
				}
			});
		})();
	</script>

</body>

</html>


