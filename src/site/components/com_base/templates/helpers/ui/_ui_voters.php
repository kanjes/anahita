<?php defined('KOOWA') or die; ?>
<?php 
$query = 'avatars=1';
if (is($entity, 'ComBaseDomainEntityComment')) {
    $query = 'comment[avatars]=1';
}
$url = $entity->getURL().'&get=voters&'.$query
?>
<?php if ($entity->voteUpCount > 0): ?>

    <?php if ($entity->voteUpCount == 1) : ?>
        <?php if ($entity->voterUpIds->offsetExists($viewer->id)) : ?>    
            <?= @text('LIB-AN-VOTE-ONLY-YOU-VOTED')?> 
        <?php else :?>          
        	<?php $ids = $entity->voterUpIds->toArray(); ?>
            <?= sprintf(@text('LIB-AN-VOTE-ONE-VOTED'), @name(@service('repos:actors.actor')->fetch(end($ids)))) ?> 
        <?php endif;?>  
    <?php elseif ($entity->voteUpCount > 1) : ?> 
        <?php if ($entity->voterUpIds->offsetExists($viewer->id)) : ?>    
            <?php if ($entity->voteUpCount == 2) : ?> 
                <?php 
                $ids = $entity->voterUpIds->toArray();
                unset($ids[$viewer->id]);
                ?>
                <?= sprintf(@text('LIB-AN-VOTE-YOU-AND-ONE-PERSON'),  @name(@service('repos:actors.actor')->fetch(end($ids))))?> 
            <?php else : ?>
                <?= sprintf(@text('LIB-AN-VOTE-YOU-AND-OTHER-VOTED-POPOVER'), @route($url), $entity->voteUpCount - 1)?> 
            <?php endif;?>
        <?php else :?>
            <?= sprintf(@text('LIB-AN-VOTE-OTHER-VOTED-POPOVER'), @route($url), $entity->voteUpCount)?> 
        <?php endif;?>
    <?php endif;?>

<?php endif; ?>