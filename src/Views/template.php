<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title><?= $title; ?></title>
</head>

<body>

<header class="p-3 bg-dark text-white">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                <img class="bi me-2" width="40" role="img" aria-label="Bootstrap" src="/logo.png" />
            </a>

            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="/" class="nav-link px-2 text-secondary">Home</a></li>
                <li><a href="/write-article" class="nav-link px-2 text-white">Write article</a></li>
                <li><a href="/api/posts" class="nav-link px-2 text-white" target="_blank">Post API</a></li>
                <li><a href="/api/comments" class="nav-link px-2 text-white" target="_blank">Comments API</a></li>
                <li><a href="/userlist" class="nav-link px-2 text-white">User List</a></li>
            </ul>

            <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
                <input type="search" class="form-control form-control-dark" placeholder="Search..." aria-label="Search">
            </form>

            <div class="text-end">
                <?php if (\Controller\SecurityController::isAuthenticated()) : ?>
                    <a type="button" href="/admin" class="btn btn-outline-light me-2">Admin</a>
                    <a type="button" href="/logout" class="btn btn-warning">Logout</a>
                <?php else : ?>
                    <a type="button" href="/login" class="btn btn-outline-light me-2">Login</a>
                    <a type="button" href="/signup" class="btn btn-warning">Sign-up</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<?php if (\Vendor\Core\Flash::hasFlash()) : ?>
    <div class="alert alert-danger" role="alert">
        <?= \Vendor\Core\Flash::getFlash(); ?>
    </div>
<?php endif; ?>

<div class="container py-5">
    <?= $content; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>