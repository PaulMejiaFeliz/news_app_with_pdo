<a href='<?= $paginationConfig['url'].'1' ?>' class="btn btn-default <?= $paginationConfig['prevDisabled'] ?>">
    <<
</a>

<a href='<?= $paginationConfig['url']. ($paginationConfig['current'] - 1) ?>' class="btn btn-default <?=  $paginationConfig['prevDisabled'] ?>">
    <
</a>
<?php if ($paginationConfig['current'] > $paginationConfig['linksCount'] && $paginationConfig['current'] < $paginationConfig['pageCount'] - $paginationConfig['linksCount'] + 1) : ?>
    <?php for ($i = $paginationConfig['current'] - $paginationConfig['linksCount']; $i < $paginationConfig['current'] + $paginationConfig['linksCount'] + 1; $i++) : ?>
        <?php $btnDisabled = ($i == $paginationConfig['current']) ? "disabled" : ""; ?>
        <a href='<?= $paginationConfig['url']. $i ?>' class="btn btn-default <?= $btnDisabled ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>
<?php elseif($paginationConfig['current'] <= $paginationConfig['pageCount'] - $paginationConfig['linksCount']) : ?>
    <?php for ($i = 1; $i <= $paginationConfig['pageCount'] && $i <=  $paginationConfig['linksCount'] * 2 + 1; $i++) : ?>
        <?php $btnDisabled = ($i == $paginationConfig['current']) ? "disabled" : ""; ?>
        <a href='<?= $paginationConfig['url']. $i ?>' class="btn btn-default <?= $btnDisabled ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>
<?php else : ?>
    <?php for ($i = ($paginationConfig['pageCount']  > $paginationConfig['linksCount'] * 2 + 1) ?  $paginationConfig['pageCount'] - $paginationConfig['linksCount'] * 2 : 1; $i <= $paginationConfig['pageCount'] ; $i++) : ?>
        <?php $btnDisabled = ($i == $paginationConfig['current']) ? "disabled" : ""; ?>
        <a href='<?= $paginationConfig['url']. $i ?>' class="btn btn-default <?= $btnDisabled ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>
<?php endif; ?>

<a href='<?= $paginationConfig['url']. ($paginationConfig['current'] + 1) ?>' class="btn btn-default <?= $paginationConfig['nextDisabled'] ?>">
    >
</a>

<a href='<?= $paginationConfig['url']. $paginationConfig['pageCount'] ?>' class="btn btn-default <?= $paginationConfig['nextDisabled'] ?>">
    >>
</a>