<h1>Update article</h1>

<form method="post">
    <input type="text" name="author" id="updatePostAuthor"
           value="<?= $vars['article']->getAuthor()->getFirstName(); ?>" disabled/> <br/>
    <input type="text" name="title" id="updatePostTitle" value="<?= $vars['article']->getTitle(); ?>" required/> <br/>
    <textarea name="content" id="updatePostContent" required><?= str_replace('<br />', "", $vars['article']->getContent()); ?></textarea> <br/>
    <input type="submit" value="Update Article"/>
</form>