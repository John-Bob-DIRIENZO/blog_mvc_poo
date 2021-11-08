<table class="table table-striped">
    <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Is Admin</th>
        <th>Action</th>
    </tr>
    <?php foreach ($vars['users'] as $user) : ?>

        <tr>
            <td><?= $user->getFirstName(); ?></td>
            <td><?= $user->getLastName(); ?></td>
            <td><?= $user->getEmail(); ?></td>
            <td><input type="checkbox" <?= $user->isAdmin() ? 'checked' : ''; ?> disabled /></td>
            <td><a href="/delete-user/<?= $user->getId(); ?>">Delete User</a></td>
        </tr>

    <?php endforeach; ?>
</table>