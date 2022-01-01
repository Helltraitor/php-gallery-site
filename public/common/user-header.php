<?php declare(strict_types=1) ?>

<header class="fixed-top">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a href="/" class="navbar-brand d-flex align-items-center me-0">
            <img src="../img/favicon.png" width="20" height="20" alt="Gallery icon" class="ms-2 me-2" aria-hidden="true"/>
            <strong>Gallery</strong>
        </a>
        <div class="collapse navbar-collapse ms-2" id="navbarCollapse">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a href="/latest" class="nav-link text-light">Latest</a>
                </li>
                <li class="nav-item">
                    <a href="/best" class="nav-link text-light">Best</a>
                </li>
                <li class="nav-item">
                    <a href="/person" class="nav-link text-light">My page</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled">
                        Hello, <?=$_SESSION['USER']['NAME']?>!
                    </a>
                </li>
            </ul>
        </div>
        <a href="/logout" class="btn btn-outline-light ms-2 me-2" type="submit">Logout</a>
    </nav>
</header>