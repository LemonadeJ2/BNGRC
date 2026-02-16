<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Forms - Modern Bootstrap Admin</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="Advanced form examples with real-time validation, file uploads, and multi-step wizards">
    <meta name="keywords" content="bootstrap, admin, dashboard, forms, validation">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="./assets/icons/favicon.svg">
    <link rel="icon" type="image/png" href="./assets/icons/favicon.png">

    <!-- PWA Manifest -->
    <link rel="manifest" href="./assets/manifest-DTaoG9pG.json">

    <!-- Preload critical fonts -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" as="style">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script type="module" crossorigin src="./assets/vendor-bootstrap-C9iorZI5.js"></script>
    <script type="module" crossorigin src="./assets/vendor-charts-DGwYAWel.js"></script>
    <script type="module" crossorigin src="./assets/vendor-ui-CflGdlft.js"></script>
    <script type="module" crossorigin src="./assets/main-B24LRf0x.js"></script>
    <script type="module" crossorigin src="./assets/forms-CC-rf4V3.js"></script>
    <link rel="stylesheet" crossorigin href="./assets/main-BQhM7myw.css">
    

</head>

<body>

    <div class="container min-vh-100 d-flex align-items-center justify-content-center ">

        <div class="row g-4 mb-5 d-flex justify-content-center align-items-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="bi bi-person-plus me-2 text-success"></i>
                            LOGIN
                        </h3>
                    </div>
                    <div class="card-body">
                        <form x-data="registrationForm()" @submit.prevent="submitForm()" action="/principal" method="get">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input
                                            type="text"
                                            value="admin"
                                            name="username"
                                            class="form-control"
                                            x-model="form.username"
                                            @input="validateField('username')"
                                            :class="getFieldClass('username')"
                                            placeholder="Enter username"
                                            required>
                                    </div>
                                    <div class="invalid-feedback" x-show="errors.username" x-text="errors.username"></div>
                                </div>
                                <!-- <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input
                                            type="email"
                                            class="form-control"
                                            x-model="form.email"
                                            @input="validateField('email')"
                                            :class="getFieldClass('email')"
                                            placeholder="Enter email"
                                            required>
                                    </div>
                                    <div class="invalid-feedback" x-show="errors.email" x-text="errors.email"></div>
                                </div> -->
                                <div class="col-md-6">
                                    <label class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input
                                            :type="showPassword ? 'text' : 'password'"
                                            class="form-control"
                                            value="1234"
                                            x-model="form.password"
                                            @input="validatePassword()"
                                            :class="getFieldClass('password')"
                                            placeholder="Enter password"
                                            required>
                                        <button
                                            type="button"
                                            class="btn btn-outline-secondary"
                                            @click="showPassword = !showPassword">
                                            <i :class="showPassword ? 'bi-eye-slash' : 'bi-eye'"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback" x-show="errors.password" x-text="errors.password"></div>

                                    <!-- Password Strength Indicator -->
                                    <div class="password-strength mt-2" x-show="form.password">
                                        <div class="strength-bar">
                                            <div
                                                class="strength-fill"
                                                :class="passwordStrength.level"
                                                :style="`width: ${passwordStrength.percentage}%`"></div>
                                        </div>
                                        <small :class="`text-${passwordStrength.color}`" x-text="`Password strength: ${passwordStrength.text}`"></small>
                                    </div>
                                </div>
                                <!-- <div class="col-md-6">
                                    <label class="form-label">Confirm Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                        <input
                                            type="password"
                                            class="form-control"
                                            x-model="form.confirmPassword"
                                            @input="validateField('confirmPassword')"
                                            :class="getFieldClass('confirmPassword')"
                                            placeholder="Confirm password"
                                            required>
                                    </div>
                                    <div class="invalid-feedback" x-show="errors.confirmPassword" x-text="errors.confirmPassword"></div>
                                </div> -->
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" x-model="form.agreeTerms" required>
                                        <label class="form-check-label">
                                            I agree to the <a href="#" class="text-primary">Terms of Service</a> and <a href="#" class="text-primary">Privacy Policy</a>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success" :disabled="isSubmitting || !isFormValid">
                                        <!-- <span x-show="!isSubmitting">
                                            <i class="bi bi-person-plus me-2"></i>Create Account
                                        </span> -->
                                        <span x-show="isSubmitting">
                                            <!-- <div class="spinner-border spinner-border-sm me-2"></div> -->
                                            Login
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Password Requirements</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>At least 8 characters</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>One uppercase letter</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>One lowercase letter</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>One number</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>One special character</li>
                        </ul>
                    </div>
                </div>
            </div> -->
        </div>

    </div>


</body>

</html>