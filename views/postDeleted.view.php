<?php if (! $exist) : ?>
    <h1>Post Deleted</h1>
    <h3>This post does not exist.</h3>
<?php elseif (! $owner) : ?>
    <h1>Error Deleting Post</h1>
    <h3>You are not the owner of the post "<?= $title ?? "" ?>".</h3>    
<?php else : ?>
    <h1>Post Deleted</h1>
    <h3>The post "<?= $title ?? "" ?>" has been deleted.</h3>
<?php endif ?>
