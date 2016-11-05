<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  use OSC\OM\Cache;
  use OSC\OM\DateTime;
  use OSC\OM\HTML;
  use OSC\OM\HTTP;
  use OSC\OM\OSCOM;
  use OSC\OM\Registry;

  class d_latest_news {
    var $code = 'd_latest_news';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function d_latest_news() {
      $this->title = OSCOM::getDef('module_admin_dashboard_latest_news_title');
      $this->description = OSCOM::getDef('module_admin_dashboard_latest_news_description');

      if ( defined('MODULE_ADMIN_DASHBOARD_LATEST_NEWS_STATUS') ) {
        $this->sort_order = MODULE_ADMIN_DASHBOARD_LATEST_NEWS_SORT_ORDER;
        $this->enabled = (MODULE_ADMIN_DASHBOARD_LATEST_NEWS_STATUS == 'True');
      }
    }

    function getOutput() {
      $entries = [];

      $newsCache = new Cache('oscommerce_website-news-latest5');

      if ($newsCache->exists(360)) {
        $entries = $newsCache->get();
      } else {
        $response = HTTP::getResponse(['url' => 'https://www.oscommerce.com/index.php?RPC&GetLatestNews']);

        if (!empty($response)) {
          $response = json_decode($response, true);

          if (is_array($response) && (count($response) === 5)) {
            $entries = $response;
          }
        }

        $newsCache->save($entries);
      }

      $output = '<table class="table table-hover">
                   <thead>
                     <tr class="info">
                       <th>' . OSCOM::getDef('module_admin_dashboard_latest_news_title') . '</th>
                       <th class="text-right">' . OSCOM::getDef('module_admin_dashboard_latest_news_date') . '</th>
                     </tr>
                   </thead>
                   <tbody>';

      if (is_array($entries) && (count($entries) === 5)) {
        foreach ($entries as $item) {
          $output .= '    <tr>
                            <td><a href="' . HTML::outputProtected($item['link']) . '" target="_blank">' . HTML::outputProtected($item['title']) . '</a></td>
                            <td class="text-right" style="white-space: nowrap;">' . HTML::outputProtected(DateTime::toShort($item['date'])) . '</td>
                          </tr>';
        }
      } else {
        $output .= '    <tr>
                          <td colspan="2">' . OSCOM::getDef('module_admin_dashboard_latest_news_feed_error') . '</td>
                        </tr>';
      }

      $output .= '    <tr>
<<<<<<< HEAD
                        <td class="text-right" colspan="2"><a href="http://www.oscommerce.com/Us&News" target="_blank">' . HTML::image(OSCOM::linkImage('icon_oscommerce.png'), OSCOM::getDef('module_admin_dashboard_latest_news_icon_news')) . '</a>&nbsp;<a href="http://www.oscommerce.com/newsletter/subscribe" target="_blank">' . HTML::image(OSCOM::linkImage('icon_newsletter.png'), OSCOM::getDef('module_admin_dashboard_latest_news_icon_newsletter')) . '</a>&nbsp;<a href="http://plus.google.com/+osCommerce" target="_blank">' . HTML::image(OSCOM::linkImage('icon_google_plus.png'), OSCOM::getDef('module_admin_dashboard_latest_news_icon_google_plus')) . '</a>&nbsp;<a href="http://www.facebook.com/pages/osCommerce/33387373079" target="_blank">' . HTML::image(OSCOM::linkImage('icon_facebook.png'), OSCOM::getDef('module_admin_dashboard_latest_news_icon_facebook')) . '</a>&nbsp;<a href="http://twitter.com/osCommerce" target="_blank">' . HTML::image(OSCOM::linkImage('icon_twitter.png'), OSCOM::getDef('module_admin_dashboard_latest_news_icon_twitter')) . '</a>&nbsp;<a href="http://feeds.feedburner.com/osCommerceNewsAndBlogs" target="_blank">' . HTML::image(OSCOM::linkImage('icon_rss.png'), OSCOM::getDef('module_admin_dashboard_latest_news_icon_rss')) . '</a></td>
=======
                        <td class="text-right" colspan="2">
                          <a href="https://www.oscommerce.com/Us&News" target="_blank" title="' . HTML::outputProtected(MODULE_ADMIN_DASHBOARD_LATEST_NEWS_ICON_NEWS) . '"><span class="fa fa-fw fa-home"></span></a>
                          <a href="https://www.oscommerce.com/newsletter/subscribe" target="_blank" title="' . HTML::outputProtected(MODULE_ADMIN_DASHBOARD_LATEST_NEWS_ICON_NEWSLETTER) . '"><span class="fa fa-fw fa-newspaper-o"></span></a>
                          <a href="https://plus.google.com/+osCommerce" target="_blank" title="' . HTML::outputProtected(MODULE_ADMIN_DASHBOARD_LATEST_NEWS_ICON_GOOGLE_PLUS) . '"><span class="fa fa-fw fa-google-plus"></span></a>
                          <a href="https://www.facebook.com/pages/osCommerce/33387373079" target="_blank" title="' . HTML::outputProtected(MODULE_ADMIN_DASHBOARD_LATEST_NEWS_ICON_FACEBOOK) . '"><span class="fa fa-fw fa-facebook"></span></a>
                          <a href="https://twitter.com/osCommerce" target="_blank" title="' . HTML::outputProtected(MODULE_ADMIN_DASHBOARD_LATEST_NEWS_ICON_TWITTER) . '"><span class="fa fa-fw fa-twitter"></span></a>
                        </td>
>>>>>>> master
                      </tr>
                    </tbody>
                  </table>';

      return $output;
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_ADMIN_DASHBOARD_LATEST_NEWS_STATUS');
    }

    function install() {
      $OSCOM_Db = Registry::get('Db');

      $OSCOM_Db->save('configuration', [
        'configuration_title' => 'Enable Latest News Module',
        'configuration_key' => 'MODULE_ADMIN_DASHBOARD_LATEST_NEWS_STATUS',
        'configuration_value' => 'True',
        'configuration_description' => 'Do you want to show the latest osCommerce News on the dashboard?',
        'configuration_group_id' => '6',
        'sort_order' => '1',
        'set_function' => 'tep_cfg_select_option(array(\'True\', \'False\'), ',
        'date_added' => 'now()'
      ]);

      $OSCOM_Db->save('configuration', [
        'configuration_title' => 'Sort Order',
        'configuration_key' => 'MODULE_ADMIN_DASHBOARD_LATEST_NEWS_SORT_ORDER',
        'configuration_value' => '0',
        'configuration_description' => 'Sort order of display. Lowest is displayed first.',
        'configuration_group_id' => '6',
        'sort_order' => '0',
        'date_added' => 'now()'
      ]);
    }

    function remove() {
      return Registry::get('Db')->exec('delete from :table_configuration where configuration_key in ("' . implode('", "', $this->keys()) . '")');
    }

    function keys() {
      return array('MODULE_ADMIN_DASHBOARD_LATEST_NEWS_STATUS', 'MODULE_ADMIN_DASHBOARD_LATEST_NEWS_SORT_ORDER');
    }
  }
?>
