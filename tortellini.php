<?php
/*
Plugin Name: Tortellini
Description: Fetches Tor bridges and displays them in different ways with a shortcode
Version: 0.1.0
Author: Ulf Härnhammar
Author URI: http://thcxthcx.net/
License: GPL2+
*/

/*
Copyright (C) 2011  Ulf Härnhammar

This program is free software and open source software; you can redistribute
it and/or modify it under the terms of the GNU General Public License as
published by the Free Software Foundation; either version 2 of the License,
or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA  or visit:
http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/


define('TOR_BRIDGES_URL', 'https://bridges.torproject.org/');


function tortellini_shortcode($att)
{
  if (!class_exists('WP_Http'))
    require_once(ABSPATH . WPINC . '/class-http.php');


  # fetch bridge data from Tor


  $req = new WP_Http;
  $res = $req->request(TOR_BRIDGES_URL);

  if (isset($res->errors))
    return "[tortellini: can't fetch data from url]";

  $data = $res['body'];

  if (!preg_match('%<p>Here are your bridge relays:%s', $data))
    return '[tortellini: tor bridge parse error 1]';

  $data = preg_replace('%^.*<pre id="bridges">\s*%s', '', $data);
  $data = preg_replace('%\s*</pre>.*$%s', '', $data);
  $data = preg_replace('%bridge\s+%s', '', $data);
  $data = htmlspecialchars($data);

  $dataarr = preg_split('%\s+%', $data);

  if (count($dataarr) < 2)
    return '[tortellini: tor bridge parse error 2]';


  # is it a list?


  if ( (isset($att['list'])) and ($att['list'] === 'y') )
    return join("<br />\n", $dataarr);


  # is it a comment?


  if ( (isset($att['comment'])) and ($att['comment'] === 'y') )
    return '<!-- ' . join(' , ', $dataarr) . ' -->';

  if (!isset($att['text']))
    return '[tortellini: syntax error]';


  # it is a text, so parse it


  $text = trim($att['text']);

  $textarr = $textpoint = array();
  # $textarr stores the result of the tokenizing, while $textpoint stores the indices to text parts

  while ($text !== '')
  {
    if (preg_match('%^\\\\n%s', $text)) # found "\n"
    {
      $text = preg_replace('%^\\\\n%s', '', $text);
      $textarr[] = array('va' => "<br />\n", 'ty' => 'linebreak');
    }
    elseif (preg_match('%^(\s+)%s', $text, $matches)) # found whitespace
    {
      $text = preg_replace('%^\s+%s', '', $text);
      $textarr[] = array('va' => $matches[1], 'ty' => 'whitespace');
    }
    elseif (preg_match('%^([^\\\\\s]+)%s', $text, $matches)) # found text (without backslashes)
    {
      $text = preg_replace('%^[^\\\\\s]+%s', '', $text);
      $textarr[] = array('va' => $matches[1], 'ty' => 'text');
      $textpoint[] = count($textarr) - 1;
    }
    elseif (preg_match('%^(\\\\([^n]|$))%s', $text, $matches)) # found "\" + not "n"
    {
      $text = preg_replace('%^\\\\([^n]|$)%s', '', $text);
      $textarr[] = array('va' => $matches[1], 'ty' => 'backslash construct');
    }
    else
      return '[tortellini: text parsing error 1]';
  } # while

  if (count($textpoint) < count($dataarr))
    return "[tortellini: text isn't long enough - type in some more]";

  shuffle($textpoint); # randomize what words the bridges will use as links


  # construct the whole output text with links to the bridges


  for ($i = 0; $i < count($dataarr); $i++)
  {
    if ($textarr[ $textpoint[$i] ]['ty'] !== 'text')
      return '[tortellini: text parsing error 2]';

    $textarr[ $textpoint[$i] ]['va'] = '<a href="https://' . $dataarr[$i] . '/" rel="nofollow">' . $textarr[ $textpoint[$i] ]['va'] . '</a>';
  }

  $finaltext = '';
  for ($i = 0; $i < count($textarr); $i++)
    $finaltext .= $textarr[$i]['va'];

  return $finaltext;
} # function tortellini_shortcode


# main program


if (function_exists('add_shortcode'))
  add_shortcode('tortellini', 'tortellini_shortcode');

?>
