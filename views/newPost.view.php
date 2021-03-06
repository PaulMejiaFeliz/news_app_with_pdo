<div class='row'>
    <div class='text-center'>
        <h3>New Post</h3>
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
        <form action='/newPost' method='post'>
            <div class='form-group input-group'>
                <span class='input-group-addon'>Title</span>
                <input class='form-control' type='text' name='postTitle'  minlength='5' required value='<?= $postTitle ?? '' ?>'>
            </div>
            <div class='form-group input-group'>
                <span class='input-group-addon'>Content</span>
                <textarea cols='30' rows='10' class='form-control' type='text' name='content' required><?= $content ?? '' ?></textarea>
            </div>
            <div class='text-center'>
                <input type='submit' class='btn btn-primary' value='Post'>
            </div>
        </form>
    </div>
</div>
