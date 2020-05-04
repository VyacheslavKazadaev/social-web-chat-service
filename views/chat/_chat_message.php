<?php
/**
 * @var $messages array
 * @var $author array
 * @var $recipient array
 */
foreach ($messages as $item): $fromAuthUser = $item['idauthor'] == $author['id']; ?>
<li class="<?= $fromAuthUser ? 'left' : 'right' ?> clearfix">
    <span class="chat-img pull-<?= $fromAuthUser ? 'left' : 'right' ?>">
    <img src="<?= $fromAuthUser
        ? 'http://placehold.it/50/55C1E7/fff&text=' . ucfirst($author['first_name'][0])
        : 'http://placehold.it/50/FA6F57/fff&text=' . ucfirst($recipient['first_name'][0]) ?>" alt="User Avatar" class="img-circle" />
    </span>
    <div class="chat-body clearfix">
        <div class="header">
            <?php
            if ($fromAuthUser):?>
                <strong class="primary-font"><?= $author['first_name'] ?> <?= $author['surname'] ?></strong>
                <small class="pull-right text-muted">
                    <span class="glyphicon glyphicon-time"></span>12 mins ago</small>
            <?php else: ?>
                <small class=" text-muted"><span class="glyphicon glyphicon-time"></span>13 mins ago</small>
                <strong class="pull-right primary-font"><?= $recipient['first_name'] ?> <?= $recipient['surname'] ?></strong>
            <?php endif; ?>
        </div>
        <p><?= $item['message'] ?></p>
    </div>
</li>
<?php endforeach; ?>
