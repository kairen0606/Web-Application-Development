// ============ Email Validation =================
let emailInput = document.getElementById("email");
let emailError = document.getElementById("emailError");

function validateEmail() {
    let email = emailInput.value.trim();
    emailError.innerHTML = ""; // Clear previous error
    let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,3}$/;

    
    if (email === "") {
        emailError.innerHTML = '<i class="ri-error-warning-fill"></i> Email is required.';
        return;
    }

    if (!emailPattern.test(email)) {
        emailError.innerHTML = '<span class="invalid">❌ Invalid email format (e.g., user@example.com).</span>';
        return; // Stop execution if the format is invalid
    }

    // If email format is valid, check availability
    emailError.innerHTML = '<span class="valid">✅ Valid email format.</span>';
}

if(emailInput){
    emailInput.addEventListener("input", validateEmail);
}

// ============ Password =================
let passwordInput = document.getElementById("password");
let passwordError = document.getElementById("passwordError");

// Function to validate password
function validatePassword() {
    let password = passwordInput.value.trim();
    passwordError.innerHTML = ""; // Clear previous error

    // Define patterns for each requirement
    const hasUppercase = /[A-Z]/.test(password);
    const hasLowercase = /[a-z]/.test(password);
    const hasNumber = /\d/.test(password);
    const hasSpecialChar = /[\W_]/.test(password); // Matches any special character
    const minLength = password.length >= 8;
    
    let messages = [];

    if (password === "") {
        passwordError.innerHTML = '<i class="ri-error-warning-fill"></i> Password is required.';
        return;
    }

    // Check if this is a signup form
    let isSignUp = document.getElementById("signUp-form") !== null;

    // If this is for signup
    if(isSignUp)
    {
        // Define validation rules
        const rules = [
            { regex: /[A-Z]/, message: "At least one uppercase letter (A-Z)." },
            { regex: /[a-z]/, message: "At least one lowercase letter (a-z)." },
            { regex: /\d/, message: "At least one number (0-9)." },
            { regex: /[\W_]/, message: "At least one special character (!, @, #, etc.)." },
            { test: password.length >= 8, message: "At least 8 characters long." }
        ];

        // Check each rule and generate messages
        passwordError.innerHTML = rules.map(rule => 
            (rule.regex?.test(password) || rule.test) 
                ? `<span class="valid">✅ ${rule.message}</span>` 
                : `<span class="invalid">❌ ${rule.message}</span>`
        ).join("<br>");
    }
}

// Attach event listeners for real-time validation
if(passwordInput){
    passwordInput.addEventListener("input", validatePassword);
}


// ============ Toggle Password Visibility =================
let togglePassword = document.getElementById("togglePassword");

if(togglePassword){
    togglePassword.addEventListener("click", function () {
        let passwordInput = document.getElementById("password");
        let icon = this.querySelector("i"); // Get the icon inside the button
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.classList.remove("ri-eye-off-line");
            icon.classList.add("ri-eye-line");
        } else {
            passwordInput.type = "password";
            icon.classList.remove("ri-eye-line");
            icon.classList.add("ri-eye-off-line"); // Change to closed eye icon
        }
    });
}

// ============ Name Validation =================
let nameInput = document.getElementById("name");
let nameError = document.getElementById("nameError");

function validateName() {
    let name = nameInput.value.trim();
    nameError.innerHTML = ""; // Clear previous error
    
    // Define patterns for each requirement
    let namePattern = /^[A-Za-z\s]{1,}$/;

    if (name === "") {
        nameError.innerHTML = '<i class="ri-error-warning-fill"></i> Name is required.';
        return;
    }
    if (!namePattern.test(name)) {
        nameError.innerHTML = '<span class="invalid">❌ Invalid name. Only letters and spaces allowed.</span>';
        return;
    }

    nameError.innerHTML = '<span class="valid">✅ Valid name.</span>';
}

// This ensure it only work for sign Up section
if (nameInput) {
    nameInput.addEventListener("input", validateName);
}

// ============ Phone Validation =================
let phoneInput = document.getElementById("phone");
let phoneError = document.getElementById("phoneError");
function validatePhone() {
    let phone = phoneInput.value.trim();
    phoneError.innerHTML = ""; // Clear previous error

    // Phone format: 012-3456789 (3 digits - 7 digits)
    let phonePattern = /^0\d{2}-\d{7}$/;

    if (phone === "") {
        phoneError.innerHTML = '<i class="ri-error-warning-fill"></i> Phone number is required.';
        return;
    }

    if (!phonePattern.test(phone)) {
        phoneError.innerHTML = '<span class="invalid">❌ Invalid phone format (e.g., 012-3456789).</span>';
        return;
    }

    phoneError.innerHTML = '<span class="valid">✅ Valid phone number.</span>';

}

if (phoneInput) {
    phoneInput.addEventListener("input", validatePhone);
}

// ============ Message Validation =================
let messageInput = document.getElementById("message");
let messageError = document.getElementById("messageError");
function validateMessage() {
    let message = messageInput.value.trim();
    messageError.innerHTML = ""; // Clear previous error

    if (message === "") {
        messageError.innerHTML = '<i class="ri-error-warning-fill"></i> Message is required.';
        return;
    }

    if (message.length < 4) {
        messageError.innerHTML = '<span class="invalid">❌ Message must be at least 4 characters long.</span>';
        return;
    }

    messageError.innerHTML = '<span class="valid">✅ Valid message.</span>';
}

if (messageInput) {
    messageInput.addEventListener("input", validateMessage);
}

// ============ Form submission validation =================
const loginForm = document.querySelector("#login-form");  // Selects the login form
const signUpForm = document.querySelector("#signUp-form"); // Selects the signup form
const editProfileForm = document.querySelector('#editProfile-form'); // Selects the edit profile form
const contactForm = document.querySelector('#contact-form'); // Selects the contact form
const ratingForm = document.querySelector('#rating-form'); // Selects the rating form

// ============ Log In Form submission validation =================
if(loginForm){
    loginForm.addEventListener("submit", function (event) {
        validateEmail();
        validatePassword(); // Enforce regex validation for signup

        // Prevent form submission if there are validation errors
        if (emailError.innerHTML.includes("❌") || passwordError.innerHTML.includes("❌") || emailInput.value.trim() === "" || passwordInput.value.trim() === "") {
            event.preventDefault(); // Stop form submission
        }
    });
}

// ============ Sign Up Form submission validation =================
if(signUpForm){
    signUpForm.addEventListener("submit", function(event){
        validateName();
        validatePhone();
        validateEmail();
        validatePassword();  // No regex validation for login

        if (nameError.innerHTML.includes("❌") || phoneError.innerHTML.includes("❌") || emailError.innerHTML.includes("❌") || passwordError.innerHTML.includes("❌") ||
            nameInput.value.trim() === "" || phoneInput.value.trim() === "" || emailInput.value.trim() === "" || passwordInput.value.trim() === "") {
            event.preventDefault(); // Stop submission
        }
    });
}

// ============ Edit Profile Form Submission Validation =================
if(editProfileForm){
    editProfileForm.addEventListener("submit", function (event){
        validateName();
        validatePhone();

        if (nameError.innerHTML.includes("❌") || phoneError.innerHTML.includes("❌") || nameInput.value.trim() === "" || phoneInput.value.trim() === "") {
            event.preventDefault(); // Stop form submission
        }
    });
}

// ============ Contact Form Submission Validation =================
if(contactForm){
    contactForm.addEventListener("submit", function (event) {
        validateName();
        validateEmail();
        validateMessage();

        if( nameError.innerHTML.includes("❌") || emailError.innerHTML.includes("❌") || messageError.innerHTML.includes("❌") ||
            nameInput.value.trim() === "" || emailInput.value.trim() === "" || messageInput.value.trim() === "") {
            event.preventDefault(); // Stop form submission
        }
        else {
            alert('Message sent! Thank you for the response.');
            this.reset(); // clear the form
        }
    });
}