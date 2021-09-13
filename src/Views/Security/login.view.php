<?php if (\Vendor\Core\Flash::hasFlash()) : ?>
    <?= \Vendor\Core\Flash::getFlash(); ?>
<?php endif; ?>

<form method="post">
    <input type="email" name="email" id="email" required/>
    <input type="password" name="password" id="password" required/>
    <input type="submit" value="LogIn"/>
</form>