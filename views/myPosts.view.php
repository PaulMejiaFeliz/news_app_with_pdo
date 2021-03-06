<div class='row'>
    <div class='col-md-offset-1'>
        <h1>My News</h1>
        <form action='/myPosts' method='get' class='form-inline'>
            <div class='form-group input-group'>
                <span class='input-group-addon'>Search By</span>
                <select name='s' class='form-control' required >
                    <?php foreach ($searchFields as $key => $field) : ?>
                        <option value='<?= $key ?>' <?= ($_GET['s'] ?? array_keys($searchFields)[0]) == $key ? 'selected=\'selected\'' : '' ?> ><?= $field ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class='form-group input-group'>
                <span class='input-group-addon'>Value</span>
                <input class='form-control' type='text' name='v' required value='<?= $_GET['v'] ?? '' ?>'>
            </div>
            <input class='btn btn-primary' type='submit' value='Search'/>
            <a class='btn btn-default' href='/myPosts'>Clear</a>
        </form>
    </div>
    </br>
    <div class='row'>
        <div class='col-md-10 col-md-offset-1'>
            <?php if (isset($news)) : ?>
                <table class='table'>
                    <thead>
                        <tr>
                            <th>
                                <?= newsapp\core\Control::loadOrderByAnchor('Title', 'title'); ?> 
                            </th>
                            <th>Author</th>
                            <th>
                                <?= newsapp\core\Control::loadOrderByAnchor('Posted Date', 'created_at'); ?>
                            </th>
                            <th>
                                <?= newsapp\core\Control::loadOrderByAnchor('Views Count', 'views'); ?>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($news as $new) : ?>
                        <tr class='clickable-row' data-href='/postDetails?id=<?= $new['id'] ?? '' ?>'>
                            <td><?= $new['title'] ?? '' ?></td>
                            <td><?= $new['user']['name'] ?? '' ?> <?= $new['user']['lastName'] ?? '' ?></td>
                            <td><?= $new['created_at'] ?? '' ?></td>
                            <td><?= $new['views'] ?? '' ?></td>
                            <td class='action-cell'>
                                <a class='btn btn-xs btn-warning' href='/editPost?id=<?= $new['id'] ?? '' ?>'>Edit Post</a>
                                <button onClick='fillFormDeletePost(<?= $new['id'] ?? '' ?>);' type='button' class='btn btn-xs btn-danger' data-toggle='modal' data-target='#deletePostModal'>Delete Post</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class='row'>
                    <div class='col-md-10 col-md-offset-1 text-center'>
                        <?php
                        if (isset($pagination)) {
                            newsapp\core\Control::loadPagination(
                                $pagination['count'],
                                $pagination['itemsPerPage'],
                                $pagination['linksCount'],
                                $pagination['current']
                            );
                        }
                        ?>
                    </div>
                </div>
                <?php if (count($news) == 0) : ?>
                    <h3>No news to show you rigth now.</h3>
                <?php endif; ?>               
            <?php else : ?>
                <h3>No news to show you rigth now.</h3>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Delete Post Modal -->
<div class='modal fade' id='deletePostModal' role='dialog'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal'>&times;</button>
                <h4 class='modal-title'>Delete Post</h4>
            </div>
            <div class='modal-body'>
                <div class='row'>
                    <div class='col col-md-10 col-md-offset-1'>
                        <h3>Do you really want to delete the Post?</h3>
                    </div>
                </div>
            </div>
            <div class='modal-footer'>
                <div class='row'>
                    <form action='/deletePost' method='post'>
                        <input name='_method' type='hidden' value='delete'>
                        <input id='deletePostFormPostId' name='PostId' type='hidden'/>
                        <div class='col col-md-5'>
                            <input class='btn btn-danger' type='submit' value='Confirm Delete'/>
                        </div>
                    </form>
                    <div class='col col-md-5 text-right'>
                        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>