document.getElementById('showSignup').addEventListener('click', function(event) {
    event.preventDefault();
    document.getElementById('signupFormContainer').classList.remove('hidden');
    document.getElementById('loginFormContainer').classList.add('hidden');
});

document.getElementById('showLogin').addEventListener('click', function(event) {
    event.preventDefault();
    document.getElementById('signupFormContainer').classList.add('hidden');
    document.getElementById('loginFormContainer').classList.remove('hidden');
});

function validateLoginForm() {
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    if (!email || !password) {
        alert("Email and password are required.");
        return false;
    }

    const formData = new FormData(document.getElementById('loginForm'));

    fetch('login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = 'main.html';
        } else {
            alert(data.errors.loginErrorMessage);
        }
    })
    .catch(error => console.error('Error:', error));

    return false; // Prevent form submission
}

function validateSignupForm() {
    // Client-side validation
    const businessName = document.getElementById('businessName').value;
    const businessAddress = document.getElementById('businessAddress').value;
    const businessType = document.getElementById('businessType').value;
    const email = document.getElementById('businessEmail').value;
    const phoneNumber = document.getElementById('phoneNumber').value;
    const password = document.getElementById('signupPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const businessPan = document.getElementById('businessPan').value;

    if (!businessName || !businessAddress || !businessType || !email || !phoneNumber || !password || !confirmPassword || !businessPan) {
        alert("All fields are required.");
        return false;
    } else if (!/\d{6,}/.test(businessPan)) {
        alert("PAN must be a number with at least 6 digits.");
        return false;
    } else if (!/\d{7,}/.test(phoneNumber)) {
        alert("Phone number must be at least 7 digits.");
        return false;
    } else if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return false;
    }

    var formData = new FormData(document.getElementById('signupForm'));

    fetch('signup.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            document.getElementById('signupForm').reset();
            document.getElementById('signupSuccessMessage').classList.remove('hidden');
        } else {
            Object.keys(data.errors).forEach(function(key) {
                alert(data.errors[key]);
            });
        }
    })
    .catch(error => console.error('Error:', error));

    return false; // Prevent form submission
}
