<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @package    symfony
 * @subpackage plugin
 * @author     Nicolas Chambrier <naholyr@yahoo.fr>
 * @version    SVN: $Id: $
 */
class BasenahoWikiActions extends sfActions
{
  
  public function preExecute()
  {
  }
  
  protected function setPage($page_name = null)
  {
    if (is_null($page_name)) {
      $page_name = ucfirst(sfInflector::camelize($this->getRequestParameter('page')));
    }
    $c = new Criteria;
    $c->add(nahoWikiPagePeer::NAME, $page_name);
    $this->page = nahoWikiPagePeer::doSelectOne($c);
    if (!$this->page) {
      $this->initNewPage($page_name);
    }
    
    $revision = $this->getRequestParameter('revision', $this->page->getLatestRevision());
    $this->revision = $this->page->getRevision($revision);
    if (!$this->revision) {
      $this->initNewRevision();
    }
    
    $this->uriParams = 'page=' . urlencode($this->page->getName());
    if ($this->revision->getRevision() != $this->page->getLatestRevision()) {
      $this->uriParams .= '&revision=' . urlencode($this->revision->getRevision());
    }
  }
    
  protected function initNewRevision()
  {
    $this->revision = new nahoWikiRevision;
    $this->revision->setnahoWikiPage($this->page);
    $this->revision->initUserName();
    if (!$this->page->isNew()) {
      $this->revision->setRevision($this->page->getLatestRevision()+1);
    }
  }
  
  protected function initNewPage($page_name)
  {
    $this->page = new nahoWikiPage;
    $this->page->setName($page_name);
  }

  protected function forward403Unless($condition)
  {
    if ($condition) {
      return;
    }

    if ($this->getUser()->isAuthenticated()) {
      $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
    } else {
      $this->forward(sfConfig::get('sf_login_module'), sfConfig::get('sf_login_action'));
    }
  }
  
  public function executeIndex()
  {
    $default_page = sfConfig::get('app_nahoWikiPlugin_default_page', 'Index');
    if (!$this->getRequestParameter('page')) {
      $this->getRequest()->setParameter('page', $default_page);
    }
    
    $this->forward('nahoWiki', 'view');
  }
  
  public function executeView()
  {
    $this->setPage();
    
    return sfView::SUCCESS;
  }
  
  public function handleErrorEdit()
  {
    $this->setPage();
    
    return sfView::SUCCESS;
  }
  public function executeEdit()
  {
    $this->setPage();
    
    // Generate the username (this is done by the Revision model)
    $tmp_revision = new nahoWikiRevision;
    $tmp_revision->initUserName();
    $this->userName = $tmp_revision->getUserName();
    
    // Save changes
    if ($this->getRequest()->getMethod() == sfRequest::POST) {
      if (!$this->page->isNew()) {
        $this->revision->archiveContent();
        $this->initNewRevision();
      }
      $this->revision->setContent($this->getRequestParameter('content'));
      $this->revision->setComment($this->getRequestParameter('comment'));
      $this->revision->save();
      $this->page->setLatestRevision($this->revision->getRevision());
      $this->page->save();
      $this->redirect('nahoWiki/view?page=' . $this->page->getName());
    }
    
    return sfView::SUCCESS;
  }

  public function executeHistory()
  {
    $this->setPage();

    return sfView::SUCCESS;
  }

  public function executeDiff()
  {
    $this->setPage(); // $this->revision is revision2

    $this->forward404If($this->page->isNew());
    $this->forward404If($this->revision->isNew());

    // Source revision
    $c = new Criteria;
    $c->add(nahoWikiRevisionPeer::PAGE_ID, $this->page->getId());
    $c->add(nahoWikiRevisionPeer::REVISION, $this->getRequestParameter('oldRevision'));
    $this->revision1 = nahoWikiRevisionPeer::doSelectOne($c);
    $this->forward404Unless($this->revision1);
    
    // Dest revision
    $this->revision2 = $this->revision;

    // Make diff
    $lines1 = explode("\n", $this->revision1->getContent());
    $lines2 = explode("\n", $this->revision2->getContent());
    $diff = new Text_Diff('auto', array($lines1, $lines2));
    switch ($this->getRequestParameter('mode', 'inline')) {
      case 'unified':
        $renderer = new Text_Diff_Renderer_unified;
        break;
      case 'context':
        $renderer = new Text_Diff_Renderer_context;
        break;
      case 'inline':
      default:
        $renderer = new Text_Diff_Renderer_inline;
        break;
    }
    $this->diff = $renderer->render($diff);
    
    // Direct download
    if ($this->getRequestParameter('raw')) {
      $this->getResponse()->setContentType('text/plain');
      $this->renderText($this->diff);
      return sfView::NONE;
    }

    return sfView::SUCCESS;
  }
  
  public function executeUser()
  {
    $this->setPage('User:' . $this->getRequestParameter('name'));
    
    return sfView::SUCCESS;
  }
  
}
