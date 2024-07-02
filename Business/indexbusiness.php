<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Login and Signup</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header>
    <div class="header-text">
      <a href="../landing.html" class="home-link">WELCOME TO ROJGAR SEWA</a> <!-- Updated Home link -->
    </div>
    <nav>
      <a href="../landing.html">Home</a> <!-- Added Home link -->
    </nav>
  </header>
  <div class="container">
    <div class="form-container" id="loginFormContainer">
      <h2>Business Login</h2>
      <form id="loginForm">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="submit" value="Login">
      </form>
      <p>Not yet registered? <a href="#" id="showSignup">Register here</a></p>
    </div>
    <div class="form-container hidden" id="signupFormContainer">
      <h2>Business Signup</h2>
      <form id="signupForm" enctype="multipart/form-data">
        <input type="text" name="businessName" id="businessName" placeholder="Business Name" required><br>
        <input type="text" name="businessAddress" id="businessAddress" placeholder="Business Address" required><br>
        <select name="businessType" id="businessType" required>
          <option value="">Select Type of Business</option>
          <option value="Administration/Customer Service">Administration/Customer Service</option>
          <option value="Finance/Accounting">Finance/Accounting</option>
          <option value="Marketing/Advertising/Public Relations">Marketing/Advertising/Public Relations</option>
          <option value="Agriculture/Farming">Agriculture/Farming</option>
          <option value="Architecture/Engineering">Architecture/Engineering</option>
          <option value="Arts/Design/Entertainment">Arts/Design/Entertainment</option>
          <option value="Banking/Insurance">Banking/Insurance</option>
          <option value="Consulting/Business Development">Consulting/Business Development</option>
          <option value="Education/Training">Education/Training</option>
          <option value="Environmental Services">Environmental Services</option>
          <option value="Healthcare/Medical">Healthcare/Medical</option>
          <option value="Human Resources">Human Resources</option>
          <option value="Information Technology (IT)">Information Technology (IT)</option>
          <option value="Legal">Legal</option>
          <option value="Manufacturing/Production">Manufacturing/Production</option>
          <option value="Media/Journalism">Media/Journalism</option>
          <option value="Non-profit/Volunteering">Non-profit/Volunteering</option>
          <option value="Project Management">Project Management</option>
          <option value="Real Estate">Real Estate</option>
          <option value="Sales/Retail">Sales/Retail</option>
          <option value="Other">Other</option>
        </select><br>
        <input type="email" name="businessEmail" id="businessEmail" placeholder="Business Email" required><br>
        <input type="text" name="businessPan" id="businessPan" placeholder="Business PAN" required><br>
        <input type="text" name="businessPhoneNumber" id="businessPhoneNumber" placeholder="Business Phone Number" required><br>
        <input type="password" name="password" id="password" placeholder="Password" required><br>
        <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password" required><br>
        <label for="businessPanDocument">Upload Business PAN Document:</label>
        <input type="file" name="businessPanDocument" id="businessPanDocument" accept=".jpg,.jpeg,.png" required><br>
        <label for="businessProfileImage">Upload Business Profile Image:</label>
        <input type="file" name="businessProfileImage" id="businessProfileImage" accept=".jpg,.jpeg,.png" required><br>
        <input type="submit" value="Signup">
      </form>
      <p>Already registered? <a href="#" id="showLogin">Login here</a></p>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('signupForm').addEventListener('submit', handleSignup);
      document.getElementById('loginForm').addEventListener('submit', handleLogin);

      document.getElementById("showSignup").addEventListener("click", function() {
        document.getElementById("loginFormContainer").classList.add("hidden");
        document.getElementById("signupFormContainer").classList.remove("hidden");
      });

      document.getElementById("showLogin").addEventListener("click", function() {
        document.getElementById("signupFormContainer").classList.add("hidden");
        document.getElementById("loginFormContainer").classList.remove("hidden");
      });
    });

    async function handleSignup(event) {
      event.preventDefault();
      if (!validateForm()) return;

      const form = document.getElementById('signupForm');
      const formData = new FormData(form);

      const response = await fetch('signup.php', {
        method: 'POST',
        body: formData
      });

      const result = await response.json();
      alert(result.message);

      if (result.success) {
        window.location.href = 'indexbusiness.html';
      }
    }

    async function handleLogin(event) {
      event.preventDefault();
      
      const form = document.getElementById('loginForm');
      const formData = new FormData(form);

      const response = await fetch('login.php', {
        method: 'POST',
        body: formData
      });

      const result = await response.json();
      alert(result.message);

      if (result.success) {
        window.location.href = 'main.php';
      }
    }

    function validateForm() {
      const businessName = document.getElementById('businessName').value;
      const businessAddress = document.getElementById('businessAddress').value;
      const businessEmail = document.getElementById('businessEmail').value;
      const businessPan = document.getElementById('businessPan').value;
      const businessPhoneNumber = document.getElementById('businessPhoneNumber').value;
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirmPassword').value;

      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      const phoneRegex = /^(0|9)\d{9}$/;
      const panRegex = /^\d{5,10}$/;

      if (businessName.length < 5) {
        alert("Business name must be at least 5 characters long.");
        return false;
      }
      if (businessAddress.length < 5) {
        alert("Address must be at least 5 characters long.");
        return false;
      }
      if (!emailRegex.test(businessEmail)) {
        alert("Invalid email format.");
        return false;
      }
      if (!phoneRegex.test(businessPhoneNumber)) {
        alert("Phone number must be 10 digits long and start with 0 or 9.");
        return false;
      }
      if (!panRegex.test(businessPan)) {
        alert("PAN number must be between 5 and 10 digits long.");
        return false;
      }
      if (password.length < 5) {
        alert("Password must be at least 5 characters long.");
        return false;
      }
      if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return false;
      }

      return true;
    }
  </script>
</body>
</html>
