<?php

/**
 * Subclass for representing a row from the 'sf_simple_wiki_page' table.
 *
 * 
 *
 * @package plugins.nahoWikiPlugin.lib.model
 */ 
class PluginnahoWikiPage extends BasenahoWikiPage
{
  
  public function getRevision($revision = null)
  {
    $latest_revision = null;
    foreach ($this->getRevisions() as $rev) {
      if (is_null($latest_revision) || $latest_revision->getRevision() < $rev->getRevision()) {
        $latest_revision = $rev;
      }
      if ($rev->getRevision() == $revision) {
        return $rev;
      }
    }
    
    return $latest_revision;
  }
  
  public function getRevisions($criteria= null)
  {
    if ($criteria === null) {
      $criteria = new Criteria();
    } elseif ($criteria instanceof Criteria) {
      $criteria = clone $criteria;
    }
    $criteria->addDescendingOrderByColumn(nahoWikiRevisionPeer::CREATED_AT);
    return $this->getnahoWikiRevisions($criteria);
  }
  
  /**
   * Should be called by maintenance applications
   */
  public function optimizeRevisions()
  {
    foreach ($this->getRevisions() as $revision) {
      if ($revision->isLatest()) {
        $revision->unarchiveContent();
      } else {
        $revision->archiveContent();
      }
    }
  }

}
