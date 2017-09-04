<div class='row'>
    <div class='text-center'>
        <h3>Login</h3>
    </div>
</div>
<div class='row'>
    <div class='col col-md-4 col-md-offset-4'>
        <div class='row'>
            <ul>
                <?php if (isset($errorMessage)) : ?>
                    <?php foreach ($errorMessage as $message) : ?>
                        <li class='text-danger'><?= $message ?></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
        <form action='/login' method='post'>
            <div class='form-group input-group'>
                <span class='input-group-addon'>E-mail</span>
                <input class='form-control' type='email' name='email' required value='<?= $email ?? '' ?>'>
            </div>
            <div class='form-group input-group'>
                <span class='input-group-addon'>Password</span>
                <input class='form-control' type='password' name='password' required>
            </div>
            <div class='text-center'>
                <input type='submit' class='btn btn-primary' value='Login'>
            </div>
        </form>
    </div>
</div>
