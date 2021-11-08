<?php if (\Vendor\Core\Flash::hasFlash()) : ?>
    <?= \Vendor\Core\Flash::getFlash(); ?>
<?php endif; ?>

<form method="post">
    <label for="firstName">First Name</label>
    <input type="text" name="firstName" id="firstName" required/> <br />

    <label for="lastName">Last Name</label>
    <input type="text" name="lastName" id="lastName" required/> <br />

    <label for="email">Email</label>
    <input type="email" name="email" id="email" required/> <br />

    <label for="password">Password</label>
    <input type="password" name="password" id="password" required/> <br />

    <label for="password_check">Verify Password</label>
    <input type="password" name="password_check" id="password_check" required/> <br />

    <input type="submit" value="LogIn"/>
</form>