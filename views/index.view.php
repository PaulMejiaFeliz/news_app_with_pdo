<div class='row'>
    <div class="col-md-offset-1">
        <h1>News</h1>
    </div>
    <div class='row'>
        <div class="col-md-10 col-md-offset-1">
            <form action="/" method="get" class="form-inline">
                <div class="form-group input-group">
                    <span class="input-group-addon">Search By</span>
                    <select name="searchBy" class="form-control" required >
                        <?php foreach ($searchFields as $key => $field) : ?>
                            <option value="<?= $key ?>" <?= ($_GET["searchBy"] ?? 0) == $key ? "selected='selected'" : "" ?> ><?= $field ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group input-group">
                    <span class="input-group-addon">Value</span>
                    <input class="form-control" type="text" name="value" required value='<?= $_GET["value"] ?? "" ?>'>
                </div>
                <input class='btn btn-primary' type="submit" value='Search'/>
                <a class='btn btn-default' href="/">Clear</a>
            </form>
        </div>
    </div>
    </br>
    <div class='row'>
        <div class="col-md-10 col-md-offset-1">
            <?php if (isset($news)) : ?>
                <?php foreach($news as $new) : ?>
                <div class='row'>
                    <div class="col-md-10 col-md-offset-1">
                        <div class="panel panel-default">
                            <div class="panel-footer">
                                <div class='row'>
                                    <div class='col-md-6'>
                                        <h5>views: <?= $new['views'] ?? "" ?></h5>
                                        <h3><a href='/postDetails?id=<?= $new['id'] ?? "" ?>'><?= $new['title'] ?? "" ?></a></h3>
                                    </div>
                                    <div class='col-md-6'>
                                        <h5 class='text-right'>Posted at <?= $new['created_at'] ?? "" ?> by <?= $new['user']['name'] ?? "" ?> <?= $new['user']['lastName'] ?? "" ?></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <div class='row'>
                    <div class="col-md-10 col-md-offset-1 text-center">
                        <?php
                            if (isset($pagination)) {
                                Pagination::load(
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
                    <div class="col-md-offset-1">
                        <h3>No news to show you rigth now.</h3>
                    </div>
                <?php endif; ?>               
            <?php else : ?>
                <div class="col-md-offset-1">
                    <h3>No news to show you rigth now.</h3>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

