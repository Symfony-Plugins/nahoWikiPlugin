<?php include_partial('page_tools', array('uriParams' => $uriParams)) ?>

<?php if (!$revision->isLatest()): ?>
  <p class="wiki-warning"><?php echo __('You are currently editing an old revision of this page. If you save these changes, all changed made since this revision will be lost !!') ?> <?php echo link_to(__('Don\'t you want to edit the latest version of this page ?'), 'nahoWiki/edit?page=' . $page->getName()) ?></p>
<?php endif ?>

<?php echo form_tag('nahoWiki/edit?' . $uriParams, 'name=edit_page id=edit_page class=wiki-form') ?>
  
  <?php if (!$sf_user->isAuthenticated()): ?>
    <p><?php echo __('You are not authenticated. Your IP address will be stored : %ip%.', array('%ip%' => '<strong>' . $userName . '</strong>')) ?></p>
  <?php else: ?>
    <p><?php echo __('You are authenticated. Your username will be stored : %username%.', array('%username%' => '<strong>' . $userName . '</strong>')) ?></p>
  <?php endif ?>
  
  <?php echo textarea_tag('content', $revision->getContent(true), 'size=80x20') ?>
  
  <p><?php echo label_for('comment', __('Comment')) ?> <?php echo input_tag('comment', $page->isNew() ? 'Creation' : '', 'size=80') ?></p>
  <p><?php echo submit_tag() ?></p>

</form>
