<?php
/*
  $Id: languages.php,v 1.35 2003/06/29 22:50:52 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
        $name = tep_db_prepare_input($_POST['name']);
        $code = tep_db_prepare_input($_POST['code']);
        $image = tep_db_prepare_input($_POST['image']);
        $directory = tep_db_prepare_input($_POST['directory']);
        $sort_order = tep_db_prepare_input($_POST['sort_order']);

        tep_db_query("insert into " . TABLE_LANGUAGES . " (name, code, image, directory, sort_order) values ('" . tep_db_input($name) . "', '" . tep_db_input($code) . "', '" . tep_db_input($image) . "', '" . tep_db_input($directory) . "', '" . tep_db_input($sort_order) . "')");
        $insert_id = tep_db_insert_id();

// create additional categories_description records
        $categories_query = tep_db_query("select c.categories_id, cd.categories_name from " . TABLE_CATEGORIES . " c left join " . TABLE_CATEGORIES_DESCRIPTION . " cd on c.categories_id = cd.categories_id where cd.language_id = '" . (int)$languages_id . "'");
        while ($categories = tep_db_fetch_array($categories_query)) {
          tep_db_query("insert into " . TABLE_CATEGORIES_DESCRIPTION . " (categories_id, language_id, categories_name) values ('" . (int)$categories['categories_id'] . "', '" . (int)$insert_id . "', '" . tep_db_input($categories['categories_name']) . "')");
        }

// create additional products_description records
        $products_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, pd.products_url from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id where pd.language_id = '" . (int)$languages_id . "'");
        while ($products = tep_db_fetch_array($products_query)) {
          tep_db_query("insert into " . TABLE_PRODUCTS_DESCRIPTION . " (products_id, language_id, products_name, products_description, products_url) values ('" . (int)$products['products_id'] . "', '" . (int)$insert_id . "', '" . tep_db_input($products['products_name']) . "', '" . tep_db_input($products['products_description']) . "', '" . tep_db_input($products['products_url']) . "')");
        }

// create additional products_options records
        $products_options_query = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . (int)$languages_id . "'");
        while ($products_options = tep_db_fetch_array($products_options_query)) {
          tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS . " (products_options_id, language_id, products_options_name) values ('" . (int)$products_options['products_options_id'] . "', '" . (int)$insert_id . "', '" . tep_db_input($products_options['products_options_name']) . "')");
        }

// create additional products_options_values records
        $products_options_values_query = tep_db_query("select products_options_values_id, products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id = '" . (int)$languages_id . "'");
        while ($products_options_values = tep_db_fetch_array($products_options_values_query)) {
          tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " (products_options_values_id, language_id, products_options_values_name) values ('" . (int)$products_options_values['products_options_values_id'] . "', '" . (int)$insert_id . "', '" . tep_db_input($products_options_values['products_options_values_name']) . "')");
        }

// create additional manufacturers_info records
        $manufacturers_query = tep_db_query("select m.manufacturers_id, mi.manufacturers_url from " . TABLE_MANUFACTURERS . " m left join " . TABLE_MANUFACTURERS_INFO . " mi on m.manufacturers_id = mi.manufacturers_id where mi.languages_id = '" . (int)$languages_id . "'");
        while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
          tep_db_query("insert into " . TABLE_MANUFACTURERS_INFO . " (manufacturers_id, languages_id, manufacturers_url) values ('" . $manufacturers['manufacturers_id'] . "', '" . (int)$insert_id . "', '" . tep_db_input($manufacturers['manufacturers_url']) . "')");
        }

// create additional orders_status records
        $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "'");
        while ($orders_status = tep_db_fetch_array($orders_status_query)) {
          tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$orders_status['orders_status_id'] . "', '" . (int)$insert_id . "', '" . tep_db_input($orders_status['orders_status_name']) . "')");
        }

        if (isset($_POST['default']) && ($_POST['default'] == 'on')) {
          tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($code) . "' where configuration_key = 'DEFAULT_LANGUAGE'");
        }

        tep_redirect(tep_href_link(FILENAME_LANGUAGES, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'lID=' . $insert_id));
        break;
      case 'save':
        $lID = tep_db_prepare_input($_GET['lID']);
        $name = tep_db_prepare_input($_POST['name']);
        $code = tep_db_prepare_input($_POST['code']);
        $image = tep_db_prepare_input($_POST['image']);
        $directory = tep_db_prepare_input($_POST['directory']);
        $sort_order = tep_db_prepare_input($_POST['sort_order']);

        tep_db_query("update " . TABLE_LANGUAGES . " set name = '" . tep_db_input($name) . "', code = '" . tep_db_input($code) . "', image = '" . tep_db_input($image) . "', directory = '" . tep_db_input($directory) . "', sort_order = '" . tep_db_input($sort_order) . "' where languages_id = '" . (int)$lID . "'");

        if ($_POST['default'] == 'on') {
          tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($code) . "' where configuration_key = 'DEFAULT_LANGUAGE'");
        }

        tep_redirect(tep_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $_GET['lID']));
        break;
      case 'deleteconfirm':
        $lID = tep_db_prepare_input($_GET['lID']);

        $lng_query = tep_db_query("select languages_id from " . TABLE_LANGUAGES . " where code = '" . DEFAULT_CURRENCY . "'");
        $lng = tep_db_fetch_array($lng_query);
        if ($lng['languages_id'] == $lID) {
          tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '' where configuration_key = 'DEFAULT_CURRENCY'");
        }

        tep_db_query("delete from " . TABLE_CATEGORIES_DESCRIPTION . " where language_id = '" . (int)$lID . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_DESCRIPTION . " where language_id = '" . (int)$lID . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . (int)$lID . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id = '" . (int)$lID . "'");
        tep_db_query("delete from " . TABLE_MANUFACTURERS_INFO . " where languages_id = '" . (int)$lID . "'");
        tep_db_query("delete from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$lID . "'");
        tep_db_query("delete from " . TABLE_LANGUAGES . " where languages_id = '" . (int)$lID . "'");

        tep_redirect(tep_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page']));
        break;
      case 'delete':
        $lID = tep_db_prepare_input($_GET['lID']);

        $lng_query = tep_db_query("select code from " . TABLE_LANGUAGES . " where languages_id = '" . (int)$lID . "'");
        $lng = tep_db_fetch_array($lng_query);

        $remove_language = true;
        if ($lng['code'] == DEFAULT_LANGUAGE) {
          $remove_language = false;
          $messageStack->add(ERROR_REMOVE_DEFAULT_LANGUAGE, 'error');
        }
        break;
    }
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo TITLE; ?></title>

<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><h3><?php echo HEADING_TITLE; ?></h3></td>
            <td class="pageHeading2" align="right"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LANGUAGE_NAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LANGUAGE_CODE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $languages_query_raw = "select languages_id, name, code, image, directory, sort_order from " . TABLE_LANGUAGES . " order by sort_order";
  $languages_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $languages_query_raw, $languages_query_numrows);
  $languages_query = tep_db_query($languages_query_raw);

  while ($languages = tep_db_fetch_array($languages_query)) {
    if ((!isset($_GET['lID']) || (isset($_GET['lID']) && ($_GET['lID'] == $languages['languages_id']))) && !isset($lInfo) && (substr($action, 0, 3) != 'new')) {
      $lInfo = new objectInfo($languages);
    }

    if (isset($lInfo) && is_object($lInfo) && ($languages['languages_id'] == $lInfo->languages_id) ) {
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $languages['languages_id']) . '\'">' . "\n";
    }

    if (DEFAULT_LANGUAGE == $languages['code']) {
      echo '                <td class="dataTableContent"><b>' . $languages['name'] . ' (' . TEXT_DEFAULT . ')</b></td>' . "\n";
    } else {
      echo '                <td class="dataTableContent">' . $languages['name'] . '</td>' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $languages['code']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($lInfo) && is_object($lInfo) && ($languages['languages_id'] == $lInfo->languages_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.png'); } else { echo '<a href="' . tep_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $languages['languages_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $languages_split->display_count($languages_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_LANGUAGES); ?></td>
                    <td class="smallText" align="right"><?php echo $languages_split->display_links($languages_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id . '&action=new') . '">' . tep_image_button('button_new_language.png', IMAGE_NEW_LANGUAGE) . '</a>'; ?></td>
                  </tr>
<?php
  }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_LANGUAGE . '</b>');

      $contents = array('form' => tep_draw_form('languages', FILENAME_LANGUAGES, 'action=insert'));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_NAME . '<br>' . tep_draw_input_field('name'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_CODE . '<br>' . tep_draw_input_field('code'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_IMAGE . '<br>' . tep_draw_input_field('image', 'icon.png'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_DIRECTORY . '<br>' . tep_draw_input_field('directory'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order'));
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_insert.png', IMAGE_INSERT) . ' <a href="' . tep_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $_GET['lID']) . '">' . tep_image_button('button_cancel.png', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_LANGUAGE . '</b>');

      $contents = array('form' => tep_draw_form('languages', FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id . '&action=save'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_NAME . '<br>' . tep_draw_input_field('name', $lInfo->name));
      $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_CODE . '<br>' . tep_draw_input_field('code', $lInfo->code));
      $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_IMAGE . '<br>' . tep_draw_input_field('image', $lInfo->image));
      $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_DIRECTORY . '<br>' . tep_draw_input_field('directory', $lInfo->directory));
      $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', $lInfo->sort_order));
      if (DEFAULT_LANGUAGE != $lInfo->code) $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.png', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id) . '">' . tep_image_button('button_cancel.png', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_LANGUAGE . '</b>');

      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $lInfo->name . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . (($remove_language) ? '<a href="' . tep_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id . '&action=deleteconfirm') . '">' . tep_image_button('button_delete.png', IMAGE_DELETE) . '</a>' : '') . ' <a href="' . tep_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id) . '">' . tep_image_button('button_cancel.png', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($lInfo)) {
        $heading[] = array('text' => '<b>' . $lInfo->name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id . '&action=edit') . '">' . tep_image_button('button_edit.png', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id . '&action=delete') . '">' . tep_image_button('button_delete.png', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_DEFINE_LANGUAGE, 'lngdir=' . $lInfo->directory) . '">' . tep_image_button('button_details.png', IMAGE_DETAILS) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_NAME . ' ' . $lInfo->name);
        $contents[] = array('text' => TEXT_INFO_LANGUAGE_CODE . ' ' . $lInfo->code);
        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $lInfo->directory . '/images/' . $lInfo->image, $lInfo->name));
        $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_DIRECTORY . '<br>' . DIR_WS_CATALOG_LANGUAGES . '<b>' . $lInfo->directory . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_SORT_ORDER . ' ' . $lInfo->sort_order);
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td valign="top"  width="220px">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
