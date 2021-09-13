<h1>My account</h1>

<?php if (\Vendor\Core\Flash::hasFlash()) : ?>
    <?= \Vendor\Core\Flash::getFlash(); ?>
<?php endif; ?>

<form action="/update-user" method="post">
    <label for="userFirstName">First Name</label>
    <input type="text" name="userFirstName" id="userFirstName"
           value="<?= \Controller\SecurityController::getLoggedUser()->getFirstName(); ?>" required/> <br/>

    <label for="userLastName">Last Name</label>
    <input type="text" name="userLastName" id="userLastName"
           value="<?= \Controller\SecurityController::getLoggedUser()->getLastName(); ?>" required/> <br/>

    <label for="userEmail">Email</label>
    <input type="email" name="userEmail" id="userEmail"
           value="<?= \Controller\SecurityController::getLoggedUser()->getEmail(); ?>" disabled required/> <br/>

    <label for="userRole">Is Admin ?</label>
    <input type="checkbox" name="userRole" id="userRole"
           value="isAdmin" <?= \Controller\SecurityController::getLoggedUser()->isAdmin() ? 'checked' : ''; ?>/> <br/>

    <label for="userPassword">Password</label>
    <input type="password" name="userPassword" id="userPassword" required/> <br/>

    <label for="userCheckPassword">Verify Password</label>
    <input type="password" name="userCheckPassword" id="userCheckPassword" required/> <br/>
    <input type="submit" value="Update Infos"/>
</form>