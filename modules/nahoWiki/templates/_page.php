<?php include_partial('page_tools', array('uriParams' => $uriParams)) ?>

<?php if (!$revision->isLatest()): ?>
  <p class="wiki-warning"><?php echo __('You are currently viewing an old revision of this page.') ?> <?php echo link_to(__('Don\'t you want to see the latest version of this page ?'), 'nahoWiki/view?page=' . $page->getName()) ?></p>
<?php endif ?>

<div class="wiki-page">
  <?php if (has_slot('wiki_page_header')) echo get_slot('wiki_page_header') ?>
  <?php if ($page->isNew()): ?>
    <p class="wiki-warning"><?php echo __(isset($nopage_msg) ? $nopage_msg : 'There is no article here yet. Edit this page to contribute.') ?></p>
  <?php else: ?>
    <?php echo $revision->getXHTMLContent() ?>
  <?php endif ?>
  <?php if (has_slot('wiki_page_footer')) echo get_slot('wiki_page_footer') ?>
</div>
