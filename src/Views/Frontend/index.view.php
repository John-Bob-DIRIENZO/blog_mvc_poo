<h1 class="text-center mb-5">Home Page</h1>
<?php
foreach ($vars as $article) :
    ?>

    <div class="card w-75 mx-auto mb-3">
        <div class="card-body">
            <h5 class="card-title"><?= $article->getTitle(); ?></h5>
            <p class="card-text"><?= substr($article->getContent(), 0, 200); ?></p>
            <a href="/article/<?= $article->getId(); ?>" class="btn btn-primary">Lire plus</a>
        </div>
    </div>

<?php endforeach; ?>