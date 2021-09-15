<h1>Write article</h1>

<form method="post" enctype="multipart/form-data">
    <input type="text" name="author" id="postAuthor"
           value="<?= \Controller\SecurityController::getLoggedUser()->getFirstName(); ?>" disabled/> <br/>
    <input type="text" name="title" id="postTitle" required/> <br/>
    <textarea name="content" id="postContent" required></textarea> <br/>

    <label for="fileUpload">Fichier:</label>
    <input type="file" name="image" id="fileUpload"> <br/>

    <input type="submit" value="Post Article"/>
</form>