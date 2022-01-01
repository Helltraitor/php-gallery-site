<?php declare(strict_types=1) ?>

<!doctype html>
<html lang="en">
<head>
    <title>Gallery - Sign Up</title>
    <?php include_once __DIR__ . '/../common/meta.html' ?>
    <link rel="stylesheet" href="../css/login.css">
    <script defer src="../js/signup.js"></script>
</head>
<body class="header-top-padding">
<?php include_once __DIR__ . '/../common/auto-header.php' ?>
<main class="masthead d-flex">
    <div class="container align-self-center">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-dark text-white" style="border-radius: 1rem;">
                    <div class="card-body p-4 text-center">
                        <div class="mb-1">
                            <h2 class="fw-bold mb-2 text-uppercase">Sign Up</h2>
                            <p class="text-white-50 mb-4">Please fill all fields!</p>
                            <form method="post" action="/signup" oninput="signupTrigger()">
                                <div>
                                    <div class="form-floating mb-3">
                                        <input autocomplete="name" name="name" oninput="nameTrigger()" type="text" id="signupName" class="form-control form-control-lg" placeholder="Jhon Dow" value="<?=$this->name?>">
                                        <label class="form-label text-dark" for="signupName">Name</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input autocomplete="email" type="email" name="email" oninput="emailTrigger()" id="signupEmail" class="form-control form-control-lg" placeholder="name@example.com" value="<?=$this->email?>"/>
                                        <label class="form-label text-dark" for="signupEmail">Email</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input autocomplete="new-password" type="password" name="password" oninput="passwordTrigger(); confirmPasswordTrigger()" id="signupPassword" class="form-control form-control-lg" placeholder="Password"/>
                                        <label class="form-label text-dark" for="signupPassword">Password</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input autocomplete="new-password" type="password" name="password_repeat" oninput="confirmPasswordTrigger()" id="signupConfirmPassword" class="form-control form-control-lg" placeholder="Confirm password"/>
                                        <label class="form-label text-dark" for="signupConfirmPassword">Confirm password</label>
                                    </div>
                                    <div class="d-inline-block mb-3">
                                        <div class="d-flex">
                                            <div class="ms-2 me-2">
                                                <input autocomplete="off" type="checkbox" class="btn-check" name="confirm_pd" id="signupConfirmPD"/>
                                                <label class="btn btn-outline-primary" for="signupConfirmPD">Confirm</label>
                                            </div>
                                            <div class="text-xl-left">
                                                I agree with personal data processing according to
                                                <a class="text-light" href="https://archives.un.org/sites/archives.un.org/files/_un-principles-on-personal-data-protection-privacy-hlcm-2018.pdf">
                                                    UN Principles on Personal Data Protection Privacy
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-1">
                                        <button class="btn btn-outline-info btn-lg px-5 disabled" id="signupConfirm" type="submit">Sign Up</button>
                                    </div>
                                </div>
                            </form>
                            <div class="d-flex justify-content-center text-center  pt-1">
                                <p class="mb-0">Already have an account? <a href="/login" class="text-white-50">Login</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include_once __DIR__ . '/../common/footer.html' ?>
</body>
</html>