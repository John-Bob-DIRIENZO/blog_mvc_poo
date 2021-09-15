<h1><?= $vars['article']->getTitle(); ?></h1>

<p><small>Ecrit par : <?= $vars['article']->getAuthor()->getFirstName(); ?> <br/>
        Le : <?= $vars['article']->getDate()->format('Y/m/d à H:i:s'); ?></small></p>

<?php if (\Controller\SecurityController::isAuthenticated() && \Controller\SecurityController::getLoggedUser()->havePostRights($vars['article'])) : ?>
    <a href="/delete-article/<?= $vars['article']->getId(); ?>">Delete article</a>
    <a href="/update-article/<?= $vars['article']->getId(); ?>">Update article</a>
<?php endif; ?>

<?php if ($vars['article']->hasImage()) : ?>
    <div>
        <img src="<?= $vars['article']->getImageUrl(); ?>" style="max-width: 500px; max-height: 300px"/>
    </div>
<?php endif; ?>

<p><?= $vars['article']->getContent(); ?></p>

<!-- Comments -->

<?php if (\Controller\SecurityController::isAuthenticated()) : ?>
    <form action="/post-comment" method="post">
        <input type="text" name="author" id="commentAuthor"
               value="<?= \Controller\SecurityController::getLoggedUser()->getFirstName(); ?>" disabled required/> <br/>
        <textarea name="content" id="commentContent" required></textarea> <br/>
        <input type="text" name="postId" id="commentPostId" value="<?= $vars['article']->getId(); ?>" hidden readonly
               required/>
        <input type="submit" value="Post Comment"/>
    </form>
<?php endif; ?>

<?php foreach ($vars['article']->getComments() as $comment) : ?>
    <div>
        <p><small>Ecrit par : <?= $comment->getAuthor()->getFirstName(); ?></small></p>
        <p><?= $comment->getContent(); ?></p>
    </div>
    <?php if (\Controller\SecurityController::isAuthenticated() && \Controller\SecurityController::getLoggedUser()->haveCommentRights($comment)) : ?>
        <a href="/delete-comment/<?= $comment->getId(); ?>">Delete comment</a>
    <?php endif; ?>
<?php endforeach; ?>