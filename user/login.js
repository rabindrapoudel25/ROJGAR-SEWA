document.getElementById('signup-link').addEventListener('click', function(event) {
    event.preventDefault();
    document.getElementById('container').classList.add('signup-active');
});

document.getElementById('login-link').addEventListener('click', function(event) {
    event.preventDefault();
    document.getElementById('container').classList.remove('signup-active');
});

document.querySelector('.signup-form button').addEventListener('click', function(event) {
    event.preventDefault();
    const form = document.querySelector('.signup-form');
    const formData = new FormData(form);
    const fullname = formData.get('fullname');
    const password = formData.get('signup-password');
    const confirmPassword = formData.get('signup-confirm-password');
    const email = formData.get('signup-email');
    const phone = formData.get('signup-phone');

    // Validate full name
    if (!/^[A-Za-z ]{5,}$/.test(fullname)) {
        alert('Full Name should contain only alphabets and have at least 5 characters.');
        return;
    }

    // Validate password
    if (!/(?=.*[A-Za-z])(?=.*\d).{5,}/.test(password)) {
        alert('Password must contain at least one alphabet, one number, and have at least 5 characters.');
        return;
    }

    // Validate password confirmation
    if (password !== confirmPassword) {
        alert('Passwords do not match.');
        return;
    }

    // Validate email
    if (!/\S+@\S+\.\S+/.test(email)) {
        alert('Please enter a valid email address.');
        return;
    }

    // Validate phone number
    if (!/^[9]\d{9}$/.test(phone)) {
        alert('Phone number must start with 9 and have 10 digits.');
        return;
    }

    fetch('signup.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            document.getElementById('container').classList.remove('signup-active'); // Switch to login form after successful signup
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

document.querySelector('.login-form button').addEventListener('click', function(event) {
    event.preventDefault();
    const form = document.querySelector('.login-form');
    const formData = new FormData(form);

    fetch('login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.href = 'main.html'; // Redirect to main.html upon successful login
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

document.getElementById('show-password-login').addEventListener('change', function() {
    const passwordInput = document.querySelector('.login-form input[name="password"]');
    if (this.checked) {
        passwordInput.type = 'text';
    } else {
        passwordInput.type = 'password';
    }
});

document.getElementById('show-password-signup').addEventListener('change', function() {
    const passwordInput = document.querySelector('.signup-form input[name="signup-password"]');
    const confirmPasswordInput = document.querySelector('.signup-form input[name="signup-confirm-password"]');
    if (this.checked) {
        passwordInput.type = 'text';
        confirmPasswordInput.type = 'text';
    } else {
        passwordInput.type = 'password';
        confirmPasswordInput.type = 'password';
    }
});
