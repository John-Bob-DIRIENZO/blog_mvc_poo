<div class="p-5 mb-4 bg-light rounded-3">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold"><?= $vars['article']->getTitle(); ?></h1>

        <p><small>Ecrit par : <?= $vars['article']->getAuthor()->getFirstName(); ?> <br />
                Le : <?= $vars['article']->getDate()->format('Y/m/d à H:i:s'); ?></small></p>

        <?php if (\Controller\SecurityController::isAuthenticated() && \Controller\SecurityController::getLoggedUser()->havePostRights($vars['article'])) : ?>
            <a href="/delete-article/<?= $vars['article']->getId(); ?>" class="btn btn-danger">Delete article</a>
            <a href="/update-article/<?= $vars['article']->getId(); ?>" class="btn btn-warning">Update article</a>
        <?php endif; ?>

        <?php if ($vars['article']->hasImage()) : ?>
            <div class="mt-5">
                <img src="<?= $vars['article']->getImageUrl(); ?>" style="max-width: 500px; max-height: 300px" />
            </div>
        <?php endif; ?>

        <p class="col-md-8 fs-4 mt-5"><?= $vars['article']->getContent(); ?></p>
    </div>
</div>


<!-- Comments -->

<?php if (\Controller\SecurityController::isAuthenticated()) : ?>
    <form action="/post-comment" method="post" class="p-3 mb-2 bg-light text-dark rounded-3">

        <h2>Écrire un commentaire :</h2>

        <div class="mb-3">
            <label for="commentAuthor" class="form-label">Author</label>
            <input type="text" class="form-control" name="author" id="commentAuthor" value="<?= \Controller\SecurityController::getLoggedUser()->getFirstName(); ?>" disabled required />
        </div>

        <div class="mb-3">
            <label for="commentContent" class="form-label">Commentaire</label>
            <textarea name="content" id="commentContent" class="form-control" rows="3" required></textarea>
        </div>

        <input type="text" name="postId" id="commentPostId" value="<?= $vars['article']->getId(); ?>" hidden readonly required />

        <input class="btn btn-primary" type="submit" value="Post Comment" />
    </form>
<?php endif; ?>

<h2 class="my-3">Les commentaires</h2>

<?php foreach ($vars['article']->getComments() as $comment) : ?>
    <div class="border-top py-3">
        <p><small>Ecrit par : <?= $comment->getAuthor()->getFirstName(); ?></small></p>
        <p><?= $comment->getContent(); ?></p>

        <?php if (\Controller\SecurityController::isAuthenticated() && \Controller\SecurityController::getLoggedUser()->haveCommentRights($comment)) : ?>
            <a href="/delete-comment/<?= $comment->getId(); ?>" class="btn btn-danger btn-sm">Delete comment</a>
        <?php endif; ?>
    </div>
<?php endforeach; ?>