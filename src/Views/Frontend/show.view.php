<h1><?= $vars['article']->getTitle(); ?></h1>
<?= $vars['article']->getContent(); ?>

<?php if (\Controller\SecurityController::isAuthenticated()) : ?>
    <form action="/post-comment" method="post">
        <input type="text" name="author" id="commentAuthor" value="<?= \Controller\SecurityController::getLoggedUser()->getFirstName(); ?>" disabled required/> <br />
        <textarea name="content" id="commentContent" required></textarea> <br />
        <input type="text" name="postId" id="commentPostId" value="<?= $vars['article']->getId(); ?>" hidden readonly required/>
        <input type="submit" value="Post Comment"/>
    </form>
<?php endif; ?>

<?php foreach ($vars['article']->getComments() as $comment) : ?>
    <div>
        <p><small>Ecrit par : <?= $comment->getAuthor()->getFirstName(); ?></small></p>
        <p><?= $comment->getContent(); ?></p>
    </div>
<?php endforeach; ?>