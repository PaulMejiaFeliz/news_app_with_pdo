<div class='row'>
    <div class='col-md-offset-1'>
        <h1>News</h1>
    </div>
    <div class='row'>
        <div class='col-md-10 col-md-offset-1'>
            <form action='/' method='get' class='form-inline'>
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
                <a class='btn btn-default' href='/'>Clear</a>
            </form>
        </div>
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
                            <th>
                                <?= newsapp\core\Control::loadOrderByAnchor('Author', 'user'); ?>
                            </th>
                            <th>
                                <?= newsapp\core\Control::loadOrderByAnchor('Posted Date', 'created_at'); ?>
                            </th>
                            <th>
                                <?= newsapp\core\Control::loadOrderByAnchor('Views Count', 'views'); ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($news as $new) : ?>
                        <tr class='clickable-row' data-href='/postDetails?id=<?= $new['id'] ?? '' ?>'>
                            <td><?= $new['title'] ?? '' ?></td>
                            <td><?= $new['user']['name'] ?? '' ?> <?= $new['user']['lastName'] ?? '' ?></td>
                            <td><?= $new['created_at'] ?? '' ?></td>
                            <td><?= $new['views'] ?? '' ?></td>
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
                    <div class='col-md-offset-1'>
                        <h3>No news to show you rigth now.</h3>
                    </div>
                <?php endif; ?>               
            <?php else : ?>
                <div class='col-md-offset-1'>
                    <h3>No news to show you rigth now.</h3>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

