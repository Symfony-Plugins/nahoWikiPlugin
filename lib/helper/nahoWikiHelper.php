<?php

function link_to_diff($text, $page_name, $rev1, $rev2, $mode = 'inline')
{
  return link_to($text, 'nahoWiki/diff?page=' . urlencode($page_name) . '&oldRevision=' . $rev1 . '&revision=' . $rev2 . '&mode=' . urlencode($mode));
}

function link_to_raw_diff($text, $page_name, $rev1, $rev2, $mode = 'unified')
{
  return link_to($text, 'nahoWiki/diff?page=' . urlencode($page_name) . '&oldRevision=' . $rev1 . '&revision=' . $rev2 . '&mode=' . urlencode($mode) . '&raw=1');
}

function url_for_wiki($page_name, $revision = null)
{
  return url_for('nahoWiki/view?page=' . urlencode($page_name) . '&revision=' . $revision);
}

function link_to_wiki($page_name, $revision = null)
{
  $text = htmlspecialchars($page_name);
  if (!is_null($revision)) {
    $text .= ' rev. ' . $revision;
  }

  return link_to($text, 'nahoWiki/view?page=' . urlencode($page_name) . '&revision=' . $revision);
}
