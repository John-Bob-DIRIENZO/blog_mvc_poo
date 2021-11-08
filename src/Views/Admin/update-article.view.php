<h1>Update article</h1>

<form method="post" enctype="multipart/form-data">
    <input type="text" name="author" id="updatePostAuthor"
           value="<?= $vars['article']->getAuthor()->getFirstName(); ?>" disabled/> <br/>
    <input type="text" name="title" id="updatePostTitle" value="<?= $vars['article']->getTitle(); ?>" required/> <br/>
    <textarea name="content" id="updatePostContent" required><?= str_replace('<br />', "", $vars['article']->getContent()); ?></textarea> <br/>

    <?php if ($vars['article']->hasImage()) : ?>
        <div>
            <p>Image actuelle :</p>
            <img src="<?= $vars['article']->getImageUrl(); ?>" style="max-width: 500px; max-height: 300px"/>
        </div>
    <?php endif; ?>

    <label for="fileUpload">Fichier:</label>
    <input type="file" name="image" id="fileUpload"> <br/>

    <input type="submit" value="Update Article"/>
</form>