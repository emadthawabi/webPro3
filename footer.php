<!--Footer-->
<footer class="footer">
    <div class="footer-content">
        <div class="footer-col brand">
            <div class="logo" style="color:white"><i class="fa-solid fa-globe"></i> &nbsp;Pathfinder</div>
            <p>
                A worldwide tour involves visiting multiple destinations across the globe, often spanning several
                countries, continents
            </p>
            <div class="socials">
                <a href="#"><i class="fa-brands fa-facebook"></i></a>
                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                <a href="#"><i class="fa-brands fa-linkedin"></i></a>
                <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>
        <div class="footer-col">
            <h2>Quick Links</h2>
            <ul>
                <li>
                    <i class="fa-solid fa-angle-right"></i><a href="index.php">Home</a>
                </li>
                <li>
                    <i class="fa-solid fa-angle-right"></i><a href="ThingsToDo.php">Things To Do</a>
                </li>
                <li>
                    <i class="fa-solid fa-angle-right"></i><a href="Tours.php">Tours</a>
                </li>
                <li>
                    <i class="fa-solid fa-angle-right"></i><a href="CustomTour.php">Custom Tour</a>
                </li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Get In Touch</h4>
            <ul class="contact-info">
                <li><i class="fa-solid fa-phone"></i> +01 234 567 890</li>
                <li><i class="fa-solid fa-envelope"></i> Pathfinder33@gmail.com</li>
                <li><i class="fa-solid fa-phone"></i> Nablus , Palestine</li>
            </ul>
        </div>
    </div>
</footer>

<!-- Back to Top Button will be added via JavaScript -->

<!-- Auth Modal -->
<div id="authModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>

        <!-- Auth Forms Container -->
        <div class="auth-container">
            <!-- Tab Navigation -->
            <div class="tab">
                <button class="tablinks <?php echo (!isset($_SESSION['active_tab']) || $_SESSION['active_tab'] == 'login') ? 'active' : ''; ?>" onclick="openTab(event, 'login')">Login</button>
                <button class="tablinks <?php echo (isset($_SESSION['active_tab']) && $_SESSION['active_tab'] == 'signup') ? 'active' : ''; ?>" onclick="openTab(event, 'signup')">Sign Up</button>
                <?php if(isset($_SESSION['active_tab'])) { unset($_SESSION['active_tab']); } ?>
            </div>

            <!-- login Form -->
            <div id="login" class="tabcontent <?php echo (!isset($_SESSION['active_tab']) || $_SESSION['active_tab'] == 'login') ? 'active' : ''; ?>">
                <h2>Welcome Back!</h2>
                <?php if(isset($_SESSION['login_error'])): ?>
                    <div class="error-message" id="loginError"><?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?></div>
                <?php endif; ?>
                <form id="loginForm" action="login.php" method="post">
                    <!-- Add a hidden input to store the current page URL -->
                    <input type="hidden" name="referer_page" value="<?php echo basename($_SERVER['PHP_SELF']); ?>">

                    <div class="form-group auth-form-group">
                        <label for="loginEmail">Email</label>
                        <input type="email" id="loginEmail" name="loginEmail" required>
                    </div>
                    <div class="form-group auth-form-group">
                        <label for="loginPassword">Password</label>
                        <input type="password" id="loginPassword" name="loginPassword" required>
                    </div>
                    <button type="submit" class="auth-button">Login</button>
                </form>
            </div>

            <!-- Signup Form -->
            <div id="signup" class="tabcontent <?php echo (isset($_SESSION['active_tab']) && $_SESSION['active_tab'] == 'signup') ? 'active' : ''; ?>">
                <h2>Create Account</h2>
                <?php if(isset($_SESSION['signup_error'])): ?>
                    <div class="error-message" style="color: red; margin-bottom: 10px;"><?php echo $_SESSION['signup_error']; unset($_SESSION['signup_error']); ?></div>
                <?php endif; ?>
                <?php if(isset($_SESSION['signup_success'])): ?>
                    <div class="auth-alert success"><?php echo $_SESSION['signup_success']; unset($_SESSION['signup_success']); ?></div>
                <?php endif; ?>
                <form id="signupForm" action="signup.php" method="post" onsubmit="return validateForm()">
                    <!-- Add a hidden input to store the current page URL -->
                    <input type="hidden" name="referer_page" value="<?php echo basename($_SERVER['PHP_SELF']); ?>">

                    <div class="form-group auth-form-group">
                        <label for="signupssn">SSN</label>
                        <input type="text" id="signupssn" name="signupssn" value="<?php echo isset($_SESSION['form_data']['signupssn']) ? htmlspecialchars($_SESSION['form_data']['signupssn']) : ''; ?>" required>
                    </div>

                    <div class="form-group auth-form-group">
                        <label for="signupName">User Name</label>
                        <input type="text" id="signupName" name="signupName" value="<?php echo isset($_SESSION['form_data']['signupName']) ? htmlspecialchars($_SESSION['form_data']['signupName']) : ''; ?>" required>
                    </div>

                    <div class="form-group auth-form-group">
                        <label for="signupEmail">Email</label>
                        <input type="email" id="signupEmail" name="signupEmail" value="<?php echo isset($_SESSION['form_data']['signupEmail']) ? htmlspecialchars($_SESSION['form_data']['signupEmail']) : ''; ?>" required>
                    </div>

                    <div class="form-group auth-form-group">
                        <label>Gender</label><br>
                        <label>
                            <input type="radio" id="genderMale" name="gender" value="male" <?php echo (isset($_SESSION['form_data']['gender']) && $_SESSION['form_data']['gender'] == 'male') ? 'checked' : ''; ?> required>
                            Male
                        </label>
                        <label style="margin-left: 10px;">
                            <input type="radio" id="genderFemale" name="gender" value="female" <?php echo (isset($_SESSION['form_data']['gender']) && $_SESSION['form_data']['gender'] == 'female') ? 'checked' : ''; ?>>
                            Female
                        </label>
                    </div>

                    <div class="form-group auth-form-group">
                        <label for="signupBdate">Bdate</label>
                        <input type="date" id="signupBdate" name="signupBdate" value="<?php echo isset($_SESSION['form_data']['signupBdate']) ? htmlspecialchars($_SESSION['form_data']['signupBdate']) : ''; ?>" required>
                    </div>

                    <div class="form-group auth-form-group">
                        <label for="signupVisa">Visa Num</label>
                        <input type="text" id="signupVisa" name="signupVisa" value="<?php echo isset($_SESSION['form_data']['signupVisa']) ? htmlspecialchars($_SESSION['form_data']['signupVisa']) : ''; ?>" required>
                    </div>

                    <div class="form-group auth-form-group">
                        <label for="signupPassword">Password</label>
                        <input type="password" id="signupPassword" name="signupPassword" required>
                    </div>

                    <div class="form-group auth-form-group">
                        <label for="signupConfirmPassword">Confirm Password</label>
                        <input type="password" id="signupConfirmPassword" name="signupConfirmPassword" required>
                        <div id="passwordError" class="error-message" style="display: none; color: red; margin-top: 5px;"></div>
                    </div>

                    <button type="submit" class="auth-button">Create Account</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script for tab switching and password validation -->
<script>
    function openTab(evt, tabName) {
        // Declare all variables
        var i, tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].classList.remove("active");
        }

        // Get all elements with class="tablinks" and remove the class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }

        // Show the current tab, and add an "active" class to the button that opened the tab
        document.getElementById(tabName).classList.add("active");
        evt.currentTarget.classList.add("active");
    }

    function validateForm() {
        var password = document.getElementById("signupPassword").value;
        var confirmPassword = document.getElementById("signupConfirmPassword").value;
        var passwordError = document.getElementById("passwordError");

        if (password !== confirmPassword) {
            passwordError.textContent = "Passwords do not match!";
            passwordError.style.display = "block";
            return false;
        }

        passwordError.style.display = "none";
        return true;
    }
</script>

<?php
// Show the modal automatically if the flag is set
if(isset($_SESSION['show_auth_modal']) && $_SESSION['show_auth_modal'] === true) {
    echo '<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("authModal").style.display = "block";
        document.body.style.overflow = "hidden";
    });
    </script>';
    unset($_SESSION['show_auth_modal']); // Clear the flag after using it
}

// Clear form data after displaying
if(isset($_SESSION['form_data'])) {
    unset($_SESSION['form_data']);
}
?>