<a href='<?= $page['url'].'1' ?>' class='btn btn-default <?= $page['prevDisabled'] ?>'>
    <<
</a>
<a href='<?= $page['url']. ($page['current'] - 1) ?>' class='btn btn-default <?=  $page['prevDisabled'] ?>'>
    <
</a>
<?php if ($page['current'] > $page['linksCount'] && $page['current'] < $page['pageCount'] - $page['linksCount'] + 1) : ?>
    <?php for ($i = $page['current'] - $page['linksCount']; $i < $page['current'] + $page['linksCount'] + 1; $i++) : ?>
        <?php $btnDisabled = ($i == $page['current']) ? 'disabled' : ''; ?>
        <a href='<?= $page['url']. $i ?>' class='btn btn-default <?= $btnDisabled ?>'>
            <?= $i ?>
        </a>
    <?php endfor; ?>
<?php elseif ($page['current'] <= $page['pageCount'] - $page['linksCount']) : ?>
    <?php for ($i = 1; $i <= $page['pageCount'] && $i <=  $page['linksCount'] * 2 + 1; $i++) : ?>
        <?php $btnDisabled = ($i == $page['current']) ? 'disabled' : ''; ?>
        <a href='<?= $page['url']. $i ?>' class='btn btn-default <?= $btnDisabled ?>'>
            <?= $i ?>
        </a>
    <?php endfor; ?>
<?php else : ?>
    <?php for ($i = ($page['pageCount']  > $page['linksCount'] * 2 + 1) ?  $page['pageCount'] - $page['linksCount'] * 2 : 1; $i <= $page['pageCount']; $i++) : ?>
        <?php $btnDisabled = ($i == $page['current']) ? 'disabled' : ''; ?>
        <a href='<?= $page['url']. $i ?>' class='btn btn-default <?= $btnDisabled ?>'>
            <?= $i ?>
        </a>
    <?php endfor; ?>
<?php endif; ?>

<a href='<?= $page['url']. ($page['current'] + 1) ?>' class='btn btn-default <?= $page['nextDisabled'] ?>'>
    >
</a>

<a href='<?= $page['url']. $page['pageCount'] ?>' class='btn btn-default <?= $page['nextDisabled'] ?>'>
    >>
</a>