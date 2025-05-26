// Updated sendMail function that matches your EmailJS template
function sendMail() {
    const name = document.getElementById("signupName").value;
    const email = document.getElementById("signupEmail").value;

    if (!name || !email) {
        alert("Please fill in all required fields");
        return Promise.resolve(false);
    }

    const submitButton = document.querySelector('.auth-button');
    const originalText = submitButton.textContent;
    submitButton.textContent = 'Sending Welcome Email...';
    submitButton.disabled = true;

    // Template parameters that match your EmailJS template
    const templateParams = {
        name: name,        // Used in subject: "Welcome to [PathFinder], {{name}}!"
        email: email       // Used in "To Email" field: {{email}}
    };

    return emailjs.send("service_dlx5var", "template_jtalee8", templateParams)
        .then(function(response) {
            console.log('Welcome email sent successfully!', response);
            submitButton.textContent = 'Creating Account...';
            return true;
        })
        .catch(function(error) {
            console.error('Failed to send welcome email:', error);
            submitButton.textContent = originalText;
            submitButton.disabled = false;
            // Still allow signup to proceed even if email fails
            return true;
        });
}

// Form submission handler
function handleSignupSubmit(event) {
    event.preventDefault();

    // First validate the form (your existing validation)
    if (!validateForm()) {
        return false;
    }

    // Send welcome email, then submit form
    sendMail()
        .then(function(emailResult) {
            // Submit the form regardless of email success/failure
            // This ensures user registration always works
            document.getElementById('signupForm').submit();
        })
        .catch(function(error) {
            console.error('Email error:', error);
            // Still proceed with signup
            document.getElementById('signupForm').submit();
        });

    return false;
}