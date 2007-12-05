<?php

$page_path = '[A-Z][a-zA-Z0-9:]+(@\d+)?';

if (sfConfig::get('app_nahoWikiPlugin_routes_register', true) && in_array('nahoWiki', sfConfig::get('sf_enabled_modules', array())))
{
  $r = sfRouting::getInstance();

  // preprend our routes
  /*$r->prependRoute('sf_wiki_view',
                      '/wiki/:page',
                      array('module' => 'nahoWiki', 'action' => 'view'),
                      array('page' => $page_path));
  $r->prependRoute('sf_wiki_view_rev',
                      '/wiki/page/:page/revision/:revision',
                      array('module' => 'nahoWiki', 'action' => 'view'),
                      array('page' => $page_path));
  $r->prependRoute('sf_wiki_history',
                      '/wiki/:page/history',
                      array('module' => 'nahoWiki', 'action' => 'history'),
                      array('page' => $page_path));
  $r->prependRoute('sf_wiki_edit',
                      '/wiki/:page/edit',
                      array('module' => 'nahoWiki', 'action' => 'edit'),
                      array('page' => $page_path));
  $r->prependRoute('sf_wiki_start',
                      '/wiki/start',
                      array('module' => 'nahoWiki', 'action' => 'index'));*/
}
