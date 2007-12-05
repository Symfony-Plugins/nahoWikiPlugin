<?php $action = sfContext::getInstance()->getActionName() ?>

<ul class="wiki-tools">
  <li><?php echo link_to(__('View'), 'nahoWiki/view?' . $uriParams, $action == 'view' ? 'class=active' : '') ?></li>
  <li><?php echo link_to(__('Edit'), 'nahoWiki/edit?' . $uriParams, $action == 'edit' ? 'class=active' : '') ?></li>
  <li><?php echo link_to(__('History'), 'nahoWiki/history?' . $uriParams, $action == 'history' ? 'class=active' : '') ?></li>
</ul>