<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003 The zen-cart developers                           |
// |                                                                      |
// | http://www.zen-cart.com/index.php                                    |
// |                                                                      |
// | Portions Copyright (c) 2003 osCommerce                               |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.zen-cart.com/license/2_0.txt.                             |
// | If you did not receive a copy of the zen-cart license and are unable |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@zen-cart.com so we can mail you a copy immediately.          |
// +----------------------------------------------------------------------+
//  $Id: stats_inventory_report.php 13 2007-03-14 00:53:35Z damonp $
//

/*
** $Id: stats_inventory_report.php 13 2007-03-14 00:53:35Z damonp $
**
** ZenCart Inventory Report Copyright (c) 2007 damonparker.org <damonp@damonparker.org>
**                                                                        
*/

/*
	History:
	03/13/07 15:28
	switched to master_categories_id for category filter
	added master category column
	added url parameters to paginate link

	03/08/07 17:55
	Initial release

*/

	require('includes/application_top.php');
	require(DIR_WS_CLASSES . 'currencies.php');
	$currencies = new currencies();  
	
	$cat	= $_GET['cat'];
	$dir 	= $_GET['dir'];
	$sort 	= $_GET['sort'];
	
	$cat = $cat == '0' ? '':$cat;
	
	if($cat != '')	{
		$db_category_where = " WHERE master_categories_id = '$cat' ";
	}
	
	$dir 		= $dir == '' ? 'ASC':$dir;
	$op_dir	= $dir == 'ASC' ? 'DESC':'ASC';
	$sort 		= $sort == '' ? 'products_name':$sort;
	
	$dir_id = $dir_name = $dir_price = $dir_quantity = $dir_total = 'DESC';
	
	switch($sort)	{
	case('p.products_id'):
		$dir_id = $op_dir;
		break;
	case('products_name'):
		$dir_name = $op_dir;
		break;
	case('products_price'):
		$dir_price = $op_dir;
		break;
	case('products_quantity'):
		$dir_quantity = $op_dir;
		break; 		
	case('total'):
		$dir_total = $op_dir;
		break; 	 		
	}
 
 
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript">
  <!--
  function init()
  {
    cssjsmenu('navbar');
    if (document.getElementById)
    {
      var kill = document.getElementById('hoverJS');
      kill.disabled = true;
    }
  }
  // -->
</script>
</head>
<body onload="init()">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo zen_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><a href="./stats_inventory_report.php?page=all&sort=<?php echo $sort; ?>&dir=<?php echo $dir; ?>&cat=<?php echo $cat; ?>">Show All</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="./stats_inventory_report.php?sort=<?php echo $sort; ?>&dir=<?php echo $dir; ?>&cat=<?php echo $cat; ?>">Paginate</a></td>
        <td align="right"><form method="get" action="./stats_inventory_report.php?page=<?php echo $_GET['page']; ?>&sort=<?php echo $sort; ?>&dir=<?php echo $dir; ?>&cat=<?php echo $cat; ?>"><?php echo zen_draw_pull_down_menu('cat', zen_get_category_tree(), $cat, 'onChange="this.form.submit();"'); ?></form></td>
      </tr>      
      <tr>
        <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" align="center"><a href="./stats_inventory_report.php?page=<?php echo $_GET['page']; ?>&sort=p.products_id&dir=<?php echo $dir_id; ?>&cat=<?php echo $cat; ?>"><b><?php echo TABLE_HEADING_NUMBER; ?></b></a></td>
                <td class="dataTableHeadingContent"><a href="./stats_inventory_report.php?page=<?php echo $_GET['page']; ?>&sort=products_name&dir=<?php echo $dir_name; ?>&cat=<?php echo $cat; ?>"><b><?php echo TABLE_HEADING_PRODUCTS; ?></b></a></td>
                <td class="dataTableHeadingContent" align="center"><a href="./stats_inventory_report.php?page=<?php echo $_GET['page']; ?>&sort=cd.categories_name&dir=<?php echo $dir_name; ?>&cat=<?php echo $cat; ?>"><b>Master Category</b></a></td>                
                <td class="dataTableHeadingContent" align="center"><a href="./stats_inventory_report.php?page=<?php echo $_GET['page']; ?>&sort=products_quantity&dir=<?php echo $dir_quantity; ?>&cat=<?php echo $cat; ?>"><b><?php echo TABLE_HEADING_QUANTITY; ?></b></a></td>
                <td class="dataTableHeadingContent" align="right"><a href="./stats_inventory_report.php?page=<?php echo $_GET['page']; ?>&sort=products_price&dir=<?php echo $dir_price; ?>&cat=<?php echo $cat; ?>"><b><?php echo TABLE_HEADING_PRICE; ?></b></a></td>
                <td class="dataTableHeadingContent" align="right"><a href="./stats_inventory_report.php?page=<?php echo $_GET['page']; ?>&sort=total&dir=<?php echo $dir_total; ?>&cat=<?php echo $cat; ?>"><b><?php echo TABLE_HEADING_TOTAL; ?></b></a></td>
              </tr>
<?php
  if (isset($_GET['page']) && ($_GET['page'] > 1)) $rows = $_GET['page'] * MAX_DISPLAY_SEARCH_RESULTS_REPORTS - MAX_DISPLAY_SEARCH_RESULTS_REPORTS;
  $rows = 0;
  $lang_id = $_SESSION['languages_id'] != '' ? intval($_SESSION['languages_id']):1;

//  This query can pull specials prices
//  $products_query_raw = "select p.products_id, products_quantity, pd.products_name, p.products_price, (products_quantity * p.products_price) as total, specials_new_products_price, (products_quantity * specials_new_products_price) as total_special, categories_name FROM ".TABLE_PRODUCTS." p LEFT JOIN ".TABLE_PRODUCTS_DESCRIPTION." pd using(products_id) LEFT JOIN ".TABLE_SPECIALS." s using(products_id) $db_category_join LEFT JOIN ".TABLE_CATEGORIES_DESCRIPTION." cd ON(cd.categories_id = p.master_categories_id AND cd.language_id = '1') $db_category_where group by p.products_id order by $sort $dir";

  $products_query_raw = "select p.products_id, products_quantity, pd.products_name, p.products_price, (products_quantity * p.products_price) as total, categories_name from ".TABLE_PRODUCTS." p left join ".TABLE_PRODUCTS_DESCRIPTION." pd using(products_id) LEFT JOIN ".TABLE_CATEGORIES_DESCRIPTION." cd ON(cd.categories_id = p.master_categories_id AND cd.language_id = '$lang_id') $db_category_where group by p.products_id order by $sort $dir";

  if($_GET['page'] != 'all')	$products_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_REPORTS, $products_query_raw, $products_query_numrows);
  $products = $db->Execute($products_query_raw);
  while (!$products->EOF) {

// only show low stock on products that can be added to the cart
    if ($zc_products->get_allow_add_to_cart($products->fields['products_id']) == 'Y') {
      $rows++;

      if (strlen($rows) < 2) {
        $rows = '0' . $rows;
      }
      $cPath = zen_get_product_path($products->fields['products_id']);
      $total += $products->fields['total']
?>
              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href='<?php echo zen_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products->fields['products_id']); ?>'">
              	<td class="dataTableContent" align="center"><?php echo $products->fields['products_id']; ?>&nbsp;</td>
                <td class="dataTableContent"><?php echo '<a href="' . zen_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products->fields['products_id']) . '">' . $products->fields['products_name'] . '</a> '; ?></td>
                <td class="dataTableContent" align="center"><?php echo $products->fields['categories_name']; ?>&nbsp;</td>
                <td class="dataTableContent" align="center"><?php echo $products->fields['products_quantity']; ?>&nbsp;</td>
                <td class="dataTableContent" align="right"><?php echo $currencies->format($products->fields['products_price']); ?>&nbsp;</td>
                <td class="dataTableContent" align="right"><?php echo $currencies->format($products->fields['total']); ?>&nbsp;</td>
              </tr>
<?php
    }
    $products->MoveNext();
  }
?>
              <tr class="dataTableHeadingRow">
                <td colspan="5">&nbsp;</td>
                <td class="dataTableHeadingContent" align="right"><?php echo $currencies->format($total); ?></td>            
              </tr> 
            </table></td>
          </tr>
<?php if(is_object($products_split))	{?>          
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_REPORTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
                <td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_REPORTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], "sort=$sort&dir=$dir&cat=$cat"); ?></td>
              </tr>
            </table></td>
          </tr>
<?php }	?>                   
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
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>