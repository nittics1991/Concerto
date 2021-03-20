/**
 *  <?= $reflection->getName(); ?> 
 * 
<?php foreach($reflection->getParameters as $parameter): ?>
 *  @param <?= $parameter->getType()->getName(); ?> $<?= $parameter->getName(); ?> 
<?php endforeach; ?>
<?php if($reflection->getReturnType()): ?>
 *  @return <?= $reflection->getReturnType()->getName(); ?>
<?php else: ?>
 *  @return void
<?php endif; ?>
<?php foreach($throws->getClasses() as $refClass): ?>
 *  @throws <?= $refClass->getShortName(); ?>
<?php endforeach; ?>
 *  
 */