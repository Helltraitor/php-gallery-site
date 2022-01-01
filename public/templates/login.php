<?php declare(strict_types=1) ?>

<!doctype html>
<html lang="en">
<head>
    <title>Gallery - Login</title>
    <?php include_once __DIR__ . '/../common/meta.html' ?>
    <link rel="stylesheet" href="../css/login.css">
    <script defer src="../js/login.js"></script>
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
                            <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
                            <p class="text-white-50 mb-4">Please enter your login and password!</p>
                            <form method="post" action="/login?next=<?=$this->next?>" oninput="loginTrigger()">
                                <div>
                                    <div class="form-floating mb-3">
                                        <input autocomplete="email" oninput="emailTrigger()" type="email" name="email" id="loginEmail" class="form-control form-control-lg" placeholder="name@example.com"/>
                                        <label class="form-label text-dark" for="loginEmail">Email</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input autocomplete="current-password" oninput="passwordTrigger()" type="password" name="password" id="loginPassword" class="form-control form-control-lg" placeholder="Password"/>
                                        <label class="form-label text-dark" for="loginPassword">Password</label>
                                    </div>
                                    <p class="small mb-5 pb-lg-2"><a class="text-white-50" href="#">Forgot password?</a></p>
                                    <button class="btn btn-outline-light btn-lg px-5 disabled" id="loginConfirm" type="submit">Login</button>
                                </div>
                            </form>
                            <div class="d-flex justify-content-center text-center mt-4 pt-1">
                                <p class="mb-0">Don't have an account? <a href="/signup" class="text-white-50">Sign Up</a></p>
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