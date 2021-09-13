<h1>Write article</h1>

<form method="post">
    <input type="text" name="author" id="commentAuthor"
           value="<?= \Controller\SecurityController::getLoggedUser()->getFirstName(); ?>" disabled/> <br/>
    <input type="text" name="title" id="postTitle" required/> <br />
    <textarea name="content" id="postContent" required></textarea> <br/>
    <input type="submit" value="Post Article"/>
</form>