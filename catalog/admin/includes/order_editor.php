<?php
  /*
  order_editor.php v2.6.4 2006/06/15 16:11:52 ams Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
    
*/
  
  
  
  ?>
  
  <script language="javascript"><!--

<?php echo "DISPLAY_PAYMENT_METHOD_DROPDOWN = '".DISPLAY_PAYMENT_METHOD_DROPDOWN."';"; ?>

<?php $countryid = tep_get_country_id($order->delivery["country"]);
	$zoneid = tep_get_zone_id($countryid, $order->delivery["state"]);
	$default_tax_name  = tep_get_tax_description($default_tax_class, $countryid, $zoneid);
	//default tax class is set at the top of the file
    echo "defaultTaxName = '" . $default_tax_name . "';"; ?>

addLoadListener(init);

function init()
{
  var optional = document.getElementById("optional");
  optional.className = "hidden";
  //START dropdown option for payment method by quick_fixer
  //new browsers support W3C - DOM Level 2
	if (document.getElementById) {
		//for payment dropdown menu use this
  		if (DISPLAY_PAYMENT_METHOD_DROPDOWN == 'true') { 
			var selObj = document.getElementById('update_info_payment_method');
			var selIndex = selObj.selectedIndex;
		
			//optional FOR TESTING WITH DROPDOWN input fields named txtIndex, txtValue and, txtText which outputs the index value***
			//0,1,2, based on position; the optional value (which may be different than the text value displayed); and the text value displayed in***
			//the dropdown menu
			//var txtIndexObj = document.getElementById('txtIndex');
			//var txtValueObj = document.getElementById('txtValue');
			//var txtTextObj = document.getElementById('txtText');
			//optional input fields***
			//OUTPUT optional input fields***
			//txtIndexObj.value = selIndex;
			//txtValueObj.value = selObj.options[selIndex].value;
			//txtTextObj.value = selObj.options[selIndex].text;
			//OUTPUT optional input fields***
			//text in lieu of value supported by firefox and mozilla but not others SO MAKE SURE text and optional value are the same (in the payment dropdown they are)
			if (selObj.options[selIndex].text) {
				var paymentMethod = selObj.options[selIndex].text;
			}
			else {
				var paymentMethod = selObj.options[selIndex].value;
			}
		}
		else {
			//if you only use an input field to display payment method use this
			var selObj = document.getElementById('update_info_payment_method');
			var paymentMethod = selObj.value;
		}
			              
	}
	//old browsers that don't support W3C - DOM Level 2
	else {
		//for payment dropdown menu use this
  		if (DISPLAY_PAYMENT_METHOD_DROPDOWN == 'true') { 
			var selObj = document.edit_order.update_info_payment_method;
			var selIndex = selObj.selectedIndex;
		
			//optional FOR TESTING WITH DROPDOWN input fields named txtIndex, txtValue and, txtText which outputs the index value***
			//0,1,2, based on position; the optional value (which may be different than the text value displayed); and the text value displayed in***
			//the dropdown menu
			//var txtIndexObj = document.forms.edit_order["txtIndex"].value;
			//var txtValueObj = document.forms.edit_order["txtValue"].value;
			//var txtTextObj = document.forms.edit_order["txtText"].value;
			//optional input fields***
			//OUTPUT optional input fields***
			//txtIndexObj.value = selIndex;
			//txtValueObj.value = selObj.options[selIndex].value;
			//txtTextObj.value = selObj.options[selIndex].text;
			//OUTPUT optional input fields***
			//text in lieu of value supported by firefox and mozilla but not others SO MAKE SURE text and optional value are the same (in the payment dropdown they are)
			if (selObj.options[selIndex].text) {
				var paymentMethod = selObj.options[selIndex].text;
			}
			else {
				var paymentMethod = selObj.options[selIndex].value;
			}
		}
		else {
			//if you only use an input field to display payment method use this
			var paymentMethod = document.forms.edit_order["update_info_payment_method"].value;
		}
	}
//END dropdown option for payment method by quick_fixer

  if (paymentMethod == "<?php echo ENTRY_CREDIT_CARD ?>") {
  optional.className = "";
  return true;
  } else {
  optional.className = "hidden";
  return true;
  }
  
 } // end function init()

  function addLoadListener(fn)
{
  if (typeof window.addEventListener != 'undefined')
  {
    window.addEventListener('load', fn, false);
  }
  else if (typeof document.addEventListener != 'undefined')
  {
    document.addEventListener('load', fn, false);
  }
  else if (typeof window.attachEvent != 'undefined')
  {
    window.attachEvent('onload', fn);
  }
  else
  {
    var oldfn = window.onload;
    if (typeof window.onload != 'function')
    {
      window.onload = fn;
    }
    else
    {
      window.onload = function()
      {
        oldfn();
        fn();
      };
    }
  }
} // end function addLoadListener(fn)
  
  function doRound(x, places) {  //we only have so much space
    return Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
 }
 
 function doFormat(x, places)  //keeps all calculated values the same length
{
var a = doRound(x, places);
var s = a.toString();

var decimalIndex = s.indexOf(".");
if (places > 0 && decimalIndex < 0)
{
decimalIndex = s.length;
s += '.';
}
while (decimalIndex + places + 1 > s.length)
{
s += '0';
}
return s;
} // end function doFormat

 function getAttributesPrices(pid){ //get any attributes prices that may exist 
var sum =0;
var el=document.getElementsByTagName('input');//all the input elements
for(var i=0;i<el.length;i++){
if(el[i].id.indexOf(pid)>-1){
var aid=el[i].id.replace(pid,'').replace('a', '');//extract the attribute id
var p=el[i].id.replace(pid,'').replace(/\d/g,'');
if((p=='a') && (document.getElementById(pid + '_' + aid + '_prefix').value) == '-') {
sum-=Number(el[i].value);
}
if((p=='a') && (document.getElementById(pid + '_' + aid + '_prefix').value) == '+') {
sum+=Number(el[i].value);
}
}
}
return sum
} //end function getAttributePrices(pid)

function getTaxTotals(action, taxdescription){ //find the right place to put the tax totals
var sum =0;
var taxRate = document.getElementById("shipping_tax_rate").value;
var el=document.getElementsByTagName('input');//all the input elements

for(var i=0;i<el.length;i++){
var p=el[i].id.replace(taxdescription,'').replace(/\d/g,'');

if(p=='p'){
sum+=Number(el[i].value);
}//end p = p

//shipping tax is added to the default tax class
  if (p == 'ot_shipping') {
  <?php
  if (DISPLAY_PRICE_WITH_TAX == 'true') { ?>//shipping tax is part of the shipping charge
  sum += Number(el[i].value) * taxRate / (Number(taxRate) + 100); 
  <?php } else { ?>//shipping tax is in addition to the shipping charge
  sum += Number(el[i].value) * taxRate / 100; 
  <?php } ?>
  }//end if p == ot_shipping
 
} //end for(var i=0;i<el.length;i++){
return sum
} //end function getTaxTotals

  function updateTotals(action, taxdescription){ //do the totals
  var sum =0;
  var el=document.getElementsByTagName('input');//all the input elements
  for(var i=0;i<el.length;i++){
  var pid=el[i].id.replace(/\d/g,'');//removes the numbers from id
 
  if (action == 'subtotal') {
  <?php if (DISPLAY_PRICE_WITH_TAX == 'true') { ?>
  if(pid == 'p-total_incl')// display price with tax => total including tax
  <?php } else { ?>
  if(pid == 'p-total_excl')// display price without tax => total excluding tax
  <?php } ?>
  {
  sum+=Number(el[i].value);
  }
  } 

  if ((action == 'weight') && (pid == 'p-total_weight')) {
  sum += Number(el[i].value);
  }

if (action =='total'){
//I cheat here- the grand total always includes the value of the various totals including tax of 
//each item, regardless of individual shop settings.  So I take the various Total incls, all the
//ot_customs, ot_loworderfees, and any ot_shipping value, and voila
if ((pid ==  'ot_custom') || (pid == 'ot_shipping') || (pid == 'p-total_incl') || (pid == 'ot_loworderfee')) {
sum += Number(el[i].value);
  }
  
 <?php
 if (DISPLAY_PRICE_WITH_TAX != 'true') //when set to true, shipping charge already includes tax
 {  ?>
 //calculates the shipping tax 
 //This has to be done independently since the grand total doesn't count the various tax totals
  if (pid == 'ot_shipping') {
  var taxRate = document.getElementById("shipping_tax_rate").value; 
  sum += Number(el[i].value) * taxRate / 100; 
    }//end if pid == defaultTaxName + 'ot_shipping'
   <?php } ?>
   
    } //end if action == total

  } //end for(var i=0;i<el.length;i++)
  
  return sum
  }//end function updateTotals()
  
  function updatePrices(action, pid, taxdescription) { 
  //calculates all the different values as new entries are typed
    var qty = document.getElementById(pid + "-qty").value;
	var taxRate = document.getElementById(pid + "-tax").value;
	var weight = document.getElementById(pid + "-weight").value;
	var attValue = getAttributesPrices(pid);
			
	if ((action == 'qty') || (action == 'tax') || (action == 'att_price') || (action == 'price')) {
	
	var finalPriceValue = document.getElementById(pid + "-price").value;
	var priceInclValue = document.getElementById(pid + "-price").value;
	var totalInclValue = document.getElementById(pid + "-price").value;
	var totalExclValue = document.getElementById(pid + "-price").value;
	var totalWeight = document.getElementById(pid + "-weight").value;
			
	finalPriceValue = Number(attValue) + Number(finalPriceValue);
	priceInclValue = ( Number(attValue) + Number(priceInclValue) ) * ((taxRate / 100) + 1);
	totalInclValue = ( Number(attValue) + Number(totalInclValue) ) * ((taxRate / 100) + 1) * qty;
	totalExclValue = ( Number(attValue) + Number(totalExclValue) ) * qty;
	totalWeight = totalWeight * qty;
	taxValue = taxRate * finalPriceValue / 100 * qty;
	
	}
	
	if (action == 'final_price') {
	
	var priceValue = document.getElementById(pid + "-final_price").value;
	var priceInclValue = document.getElementById(pid + "-final_price").value;
	var totalInclValue = document.getElementById(pid + "-final_price").value;
	var totalExclValue = document.getElementById(pid + "-final_price").value;
	var taxValue = document.getElementById(pid + "-final_price").value;
		
	priceValue = Number(priceValue) - Number(attValue);
	priceInclValue = priceInclValue * ((taxRate / 100) + 1);
	totalInclValue = totalInclValue * ((taxRate / 100) + 1) * qty;
	totalExclValue = totalExclValue * qty;
	taxValue = taxRate * taxValue / 100 * qty;
		
	} //end if ((action == 'qty') || (action == 'tax') || (action == 'final_price')) 
	
	if (action == 'price_incl') {
	
	var priceValue = document.getElementById(pid + "-price_incl").value;
	var finalPriceValue = document.getElementById(pid + "-price_incl").value;
	var totalInclValue = document.getElementById(pid + "-price_incl").value;
	var totalExclValue = document.getElementById(pid + "-price_incl").value;
		
	priceValue = Number(finalPriceValue / ((taxRate / 100) + 1)) - Number(attValue);
	finalPriceValue = finalPriceValue / ((taxRate / 100) + 1);
	totalInclValue = totalInclValue * qty;
	totalExclValue = totalExclValue * qty / ((taxRate / 100) + 1);
	taxValue = taxRate * finalPriceValue / 100 * qty;
	
	} //end of if (action == 'price_incl')
	
	if (action == 'total_excl') {
	
	var priceValue = document.getElementById(pid + "-total_excl").value;
	var finalPriceValue = document.getElementById(pid + "-total_excl").value;
	var priceInclValue = document.getElementById(pid + "-total_excl").value;
	var totalInclValue = document.getElementById(pid + "-total_excl").value;
			
	priceValue = ( Number (finalPriceValue / qty) ) - Number (attValue);
	finalPriceValue = finalPriceValue / qty;
	priceInclValue = priceInclValue * ((taxRate / 100) + 1) / qty;
	totalInclValue = totalInclValue * ((taxRate / 100) + 1);
	taxValue = taxRate * finalPriceValue / 100 * qty;
	
	} //end of if (action == 'total_excl')
	
	if (action == 'total_incl') {
	
	var priceValue = document.getElementById(pid + "-total_incl").value;
	var finalPriceValue = document.getElementById(pid + "-total_incl").value;
	var priceInclValue = document.getElementById(pid + "-total_incl").value;
	var totalExclValue = document.getElementById(pid + "-total_incl").value;
		
	priceValue = Number (finalPriceValue / ((taxRate / 100) + 1) / qty) - Number(attValue)
	finalPriceValue = finalPriceValue / ((taxRate / 100) + 1) / qty;
	priceInclValue = priceInclValue / qty;
	totalExclValue = totalExclValue / ((taxRate / 100) + 1);
	taxValue = taxRate * finalPriceValue / 100 * qty;
	
	} //end of if (action == 'total_incl')
	
	if (action == 'qty') {
	document.getElementById(pid + "-total_weight").value = doFormat(totalWeight, 2);
	var totalOrderWeight = updateTotals('weight');//performed after formatting weight above
	document.getElementById("total_order_weight").value = doFormat(totalOrderWeight, 2);
	}
	
	if ((action != 'qty') && (action != 'tax') && (action != 'att_price') && (action != 'price')) {
	document.getElementById(pid + "-price").value = doFormat(priceValue, 4);
	}
	
	if (action != 'final_price') {
	document.getElementById(pid + "-final_price").value = doFormat(finalPriceValue, 4);
	}
	
	if ((action != 'qty') && (action != 'price_incl')) {
	document.getElementById(pid + "-price_incl").value = doFormat(priceInclValue, 4);
	}
	
	if ((action != 'tax') && (action != 'total_excl')) {
	document.getElementById(pid + "-total_excl").value = doFormat(totalExclValue, 4);
	}
	
	if (action != 'total_incl') {
	document.getElementById(pid + "-total_incl").value = doFormat(totalInclValue, 4);
	}
	
	document.getElementById(taxdescription + pid).value = doFormat(taxValue, 4);
	
	var subTotal = updateTotals('subtotal', taxdescription);
	document.getElementById("ot_subtotal").value = doFormat(subTotal, 4);
	
	var taxTotal = getTaxTotals('tax', taxdescription);
	var field = document.getElementById(taxdescription + "-total");
    if (field) field.value = doFormat(taxTotal, 4);//tax fields won't necessarily exist
	
	var preTotal = updateTotals('total', taxdescription);
	//var total = Number(preTotal) + Number(subTotal);
	document.getElementById("ot_total").value = doFormat(preTotal, 4);
	
	} //end function updatePrices(action, pid)
	
    function getTotals(action, taxdescription) { 
	//called when updating editable total components such as shipping
	var subTotal = updateTotals('subtotal', taxdescription);
	document.getElementById("ot_subtotal").value = doFormat(subTotal, 4);
	
	//need to perform special step if shipping charge is changed
	if (action == 'shipping') {
	var taxTotal = getTaxTotals('tax', taxdescription);
	var field = document.getElementById(taxdescription + "-total");
    if (field) field.value = doFormat(taxTotal, 4);//tax fields won't necessarily exist
	}//end if action == shipping
	
	var preTotal = updateTotals('total', taxdescription);
	//var total = Number(preTotal) + Number(subTotal);
	document.getElementById("ot_total").value = doFormat(preTotal, 4);
	} //end function getTotals

 
		function currency() {
		    var selObj = document.getElementById("update_info_payment_currency");
			var selIndex = selObj.selectedIndex;
			<?php
			 $currency_query_raw = "select code, value from " . TABLE_CURRENCIES . "";
             $currency_query = tep_db_query($currency_query_raw);
             while ($currency = tep_db_fetch_array($currency_query)) {
			echo 'if (selObj.options[selIndex].value == \'' . $currency['code'] . '\') {' . "\n";
			echo 'document.getElementById("update_info_payment_currency_value").value = \'' . $currency['value'] . '\';' . "\n";
			echo '}' . "\n";
			echo "\n";
			}
            ?>
        }
		
 //--></script>
 
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
		<tr class="dataTableHeadingRow">
	  <td class="dataTableHeadingContent"><?php  echo TABLE_HEADING_DELETE; ?></td>
	  <td class="dataTableHeadingContent"><?php  echo TABLE_HEADING_QUANTITY; ?></td>
	  <td class="dataTableHeadingContent"><?php  echo TABLE_HEADING_PRODUCTS; ?></td>
	  <td class="dataTableHeadingContent"><?php  echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
	  <td class="dataTableHeadingContent"><?php  echo TABLE_HEADING_TAX; ?></td>
	  <td class="dataTableHeadingContent" onMouseover="ddrivetip('<?php echo HINT_BASE_PRICE; ?>')"; onMouseout="hideddrivetip()"><?php  echo TABLE_HEADING_BASE_PRICE; ?> <img src="images/icon_info.png" border= "0" width="13" height="13" /></td>
	  <td class="dataTableHeadingContent" onMouseover="ddrivetip('<?php echo HINT_PRICE_EXCL; ?>')"; onMouseout="hideddrivetip()"><?php  echo TABLE_HEADING_UNIT_PRICE; ?> <img src="images/icon_info.png" border= "0" width="13" height="13" /></td>
	  <td class="dataTableHeadingContent" onMouseover="ddrivetip('<?php echo HINT_PRICE_INCL; ?>')"; onMouseout="hideddrivetip()"><?php  echo TABLE_HEADING_UNIT_PRICE_TAXED; ?> <img src="images/icon_info.png" border= "0" width="13" height="13" /></td>
	  <td class="dataTableHeadingContent" onMouseover="ddrivetip('<?php echo HINT_TOTAL_EXCL; ?>')"; onMouseout="hideddrivetip()"><?php  echo TABLE_HEADING_TOTAL_PRICE; ?> <img src="images/icon_info.png" border= "0" width="13" height="13" /></td>
	  <td class="dataTableHeadingContent" onMouseover="ddrivetip('<?php echo HINT_TOTAL_INCL; ?>')"; onMouseout="hideddrivetip()"><?php  echo TABLE_HEADING_TOTAL_PRICE_TAXED; ?> <img src="images/icon_info.png" border= "0" width="13" height="13" /></td>
	  <td class="dataTableHeadingContent"><?php  echo TABLE_HEADING_PRODUCTS_WEIGHT; ?></td>
	</tr>
	<?php

	for ($i=0; $i<sizeof($order->products); $i++) {
	//calculate total weight
	$products_weight = array($order->products[$i]['weight'] * $order->products[$i]['qty']);
    foreach ($products_weight as $key => $value);
    $total_weight += $value;
    //end total weight
	$orders_products_id = $order->products[$i]['orders_products_id'];
		$RowStyle = "dataTableContent";
		echo '	  <tr class="dataTableRow">' . "\n" .
		     '	    <td class="' . $RowStyle . '" valign="top"><div align="center">' . "<input name='update_products[$orders_products_id][delete]' type='checkbox' /></div></td>\n" . 
			 '	    <td class="' . $RowStyle . '" align="right" valign="top"><div align="center">' . "<input name='update_products[$orders_products_id][qty]' size='2' value='" . $order->products[$i]['qty'] . "' onKeyUp=\"updatePrices('qty', 'p" . $orders_products_id . "', '" . $order->products[$i]['tax_description'] . "')\" id='p" . $orders_products_id . "-qty' /></div></td>\n" . 
 		     '	    <td class="' . $RowStyle . '" valign="top">' . "<input name='update_products[$orders_products_id][name]' size='35' value='" . tep_html_quotes($order->products[$i]['name']) . "'>";
		
		// Has Attributes? 
		if (sizeof($order->products[$i]['attributes']) > 0) {
			for ($j=0; $j<sizeof($order->products[$i]['attributes']); $j++) {
				$orders_products_attributes_id = $order->products[$i]['attributes'][$j]['orders_products_attributes_id'];
				echo '<br /><nobr><small>&nbsp;<i> - ' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][option]' size='6' value='" . $order->products[$i]['attributes'][$j]['option'] . "'>" . ': ' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][value]' size='10' value='" . $order->products[$i]['attributes'][$j]['value'] . "'>" . ': ' . "</i><input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][prefix]' size='1' id='p" . $orders_products_id . "_" . $orders_products_attributes_id . "_prefix' value='" . $order->products[$i]['attributes'][$j]['prefix'] . "' onKeyUp=\"updatePrices('att_price', 'p" . $orders_products_id . "', '" . $order->products[$i]['tax_description'] . "', '" . $orders_products_attributes_id . "')\">" . ': ' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][price]' size='7' value='" . $order->products[$i]['attributes'][$j]['price'] . "' onKeyUp=\"updatePrices('att_price', 'p" . $orders_products_id . "', '" . $order->products[$i]['tax_description'] . "', '" . $orders_products_attributes_id . "')\" id='p". $orders_products_id . "a" . $orders_products_attributes_id . "'>";
				echo '</small></nobr>';
			}  //end for ($j=0; $j<sizeof($order->products[$i]['attributes']); $j++) {
			
			 //Has downloads?
  /*The way a stock CartStore install handles downloads is somewhat bizarre.  Although you can only
  set a product to be downloadable via the products attributes interface, when the info is stored
  once a customer places an order, the orders_products_attributes_id is not stored in the
  orders_products_download table, just the orders_id and orders_products_id.  So if for some reason
  you had a product with two different attribute sets, and one option from each set was a download 
  version (for instance a software download that also had an expansion pack where you could
  purchase both downloads as one product, but you'd still have to download both), there's no clear 
  way of telling on the backend which product attribute each download is associated with, just 
  which product it goes with.  In my opinion that's a little strange, but it is what it is.  Anyway
  I hope the way I've handled this for Order Editor makes sense to most people. */
  
 
    if (DOWNLOAD_ENABLED == 'true') {
   $downloads_count = 1;
   $d_index = 0;
   $download_query_raw ="SELECT *
                         FROM " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . "                               
						 WHERE orders_products_id='" . $orders_products_id . "'
						 AND orders_id='" . (int)$oID . "'
						 ORDER BY orders_products_download_id";
  
		$download_query = tep_db_query($download_query_raw);
		
		//
		if (isset($downloads->products)) unset($downloads->products);
		//
		
		if (tep_db_num_rows($download_query) > 0) {
        while ($download = tep_db_fetch_array($download_query)) {
		
 		$downloads->products[$d_index] = array(
		            'id' => $download['orders_products_download_id'],
		            'filename' => $download['orders_products_filename'],
                    'maxdays' => $download['download_maxdays'],
                    'maxcount' => $download['download_count']);
		
		$d_index++; 
		
		} 
       } 
        
  for ($mm=0; $mm<sizeof($downloads->products); $mm++) {  
  $id =  $downloads->products[$mm]['id'];
  echo '<br /><small>';
  echo '<nobr>' . ENTRY_DOWNLOAD_COUNT . $downloads_count . "";
  echo ' </nobr><br />' . "\n";
  echo '<nobr>&nbsp;- ' . ENTRY_DOWNLOAD_FILENAME . ": <input name='update_downloads[$id][filename]' size='12' value='" . $downloads->products[$mm]['filename'] . "'>";
  echo ' </nobr><br />' . "\n";
  echo '<nobr>&nbsp;- ' . ENTRY_DOWNLOAD_MAXDAYS . ": <input name='update_downloads[$id][maxdays]' size='6' value='" . $downloads->products[$mm]['maxdays'] . "'>";
  echo ' </nobr><br />' . "\n";
  echo '<nobr>&nbsp;- ' . ENTRY_DOWNLOAD_MAXCOUNT . ": <input name='update_downloads[$id][maxcount]' size='6' value='" . $downloads->products[$mm]['maxcount'] . "'>";
  echo ' </nobr>' . "\n";
  echo '<hr /></small>';
  $downloads_count++;
    } //end  for ($mm=0; $mm<sizeof($download_query); $mm++) {
	} //end download
	
		} //end if (sizeof($order->products[$i]['attributes']) > 0) {
		
		echo '	    </td>' . "\n" .
		     '	    <td class="' . $RowStyle . '" valign="top">' . "<input name='update_products[$orders_products_id][model]' size='12' value='" . $order->products[$i]['model'] . "'>" . '</td>' . "\n" .
		     '	    <td class="' . $RowStyle . '" valign="top">' . "<input name='update_products[$orders_products_id][tax]' size='6' value='" . tep_display_tax_value($order->products[$i]['tax']) . "' onKeyUp=\"updatePrices('tax', 'p" . $orders_products_id . "', '" . $order->products[$i]['tax_description'] . "')\" id='p" . $orders_products_id . "-tax' />" . 
			 "<input type='hidden' name='update_products[$orders_products_id][tax_description]' value='".$order->products[$i]['tax_description']."'>" . 
			 "<input type='hidden' name='" . $order->products[$i]['tax_description'] . 'p' . $orders_products_id . "' id='" . $order->products[$i]['tax_description'] . 'p' . $orders_products_id . "' value='" . number_format(($order->products[$i]['tax'] * $order->products[$i]['final_price'] / 100 * $order->products[$i]['qty']), 4, '.', '') . "'>" . 
			 '</td>' . "\n" .
		     '	    <td class="' . $RowStyle . '" align="right" valign="top">' . "<input name='update_products[$orders_products_id][price]' size='7' value='" . number_format($order->products[$i]['price'], 4, '.', '') . "' onKeyUp=\"updatePrices('price', 'p" . $orders_products_id . "', '" . $order->products[$i]['tax_description'] . "')\" id='p" . $orders_products_id . "-price' />" . '</td>' . "\n" .
			 '	    <td class="' . $RowStyle . '" align="right" valign="top">' . "<input name='update_products[$orders_products_id][final_price]' size='7' value='" . number_format($order->products[$i]['final_price'], 4, '.', '') . "' onKeyUp=\"updatePrices('final_price', 'p" . $orders_products_id . "', '" . $order->products[$i]['tax_description'] . "')\" id='p" . $orders_products_id . "-final_price' />" . '</td>' . "\n" . 
		     '	    <td class="' . $RowStyle . '" align="right" valign="top">' . "<input name='update_products[$orders_products_id][price_incl]' size='7' value='" . number_format(($order->products[$i]['final_price'] * (($order->products[$i]['tax']/100) + 1)), 4, '.', '') . "' onKeyUp=\"updatePrices('price_incl', 'p" . $orders_products_id . "', '" . $order->products[$i]['tax_description'] . "')\" id='p" . $orders_products_id . "-price_incl' />" . '</td>' . "\n" . 
		     '	    <td class="' . $RowStyle . '" align="right" valign="top">' . "<input name='update_products[$orders_products_id][total_excl]' size='7' value='" . number_format($order->products[$i]['final_price'] * $order->products[$i]['qty'], 4, '.', '') . "' onKeyUp=\"updatePrices('total_excl', 'p" . $orders_products_id . "', '" . $order->products[$i]['tax_description'] . "')\" id='p" . $orders_products_id . "-total_excl' />" . '</td>' . "\n" . 
		     '	    <td class="' . $RowStyle . '" align="right" valign="top">' . "<input name='update_products[$orders_products_id][total_incl]' size='7' value='" . number_format((($order->products[$i]['final_price'] * (($order->products[$i]['tax']/100) + 1))) * $order->products[$i]['qty'], 4, '.', '') . "' onKeyUp=\"updatePrices('total_incl', 'p" . $orders_products_id . "', '" . $order->products[$i]['tax_description'] . "')\" id='p" . $orders_products_id . "-total_incl' />" . '</td>' . "\n" .
			 '	    <td class="' . $RowStyle . '" align="right" valign="top">'  . 
		"<input name='update_products[$orders_products_id][total_weight]' size='6' value='" . number_format(($order->products[$i]['weight'] * $order->products[$i]['qty']), 2, '.', '') . "' id='p" . $orders_products_id . "-total_weight' readonly='readonly'>" . "<input type='hidden' name='update_products[$orders_products_id][weight]' value='" . $order->products[$i]['weight'] . "' id='p" . $orders_products_id . "-weight'>"  . '</td>' . "\n" .
			 '	  </tr>' . "\n" .
			 '     <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>' . "\n";
	}
?>
</table> 
        </td>
      <tr>
	      <td>
		<table width="100%" cellpadding="0" cellspacing="0">
					  <tr>
  <td align="right"><?php echo '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=add_product&step=1') . '">' . tep_image_button('button_add_article.png', ADDING_TITLE) . '</a>'; ?></td>
						</tr>
					</table>
			  </td>
      </tr>
      <tr>
	      <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
      </tr>
			
	<!-- End Products Listings Block -->

	<!-- Begin Update Block -->
      <tr>
	      <td>
          <table width="100%" border="0" cellpadding="2" cellspacing="1">
            <tr>
              <td class="update1"><?php echo HINT_PRESS_UPDATE; ?></td>
              <td class="update2" width="10">&nbsp;</td>
              <td class="update3" width="10">&nbsp;</td>
              <td class="update4" width="10">&nbsp;</td>
              <td class="update5" width="120" align="center"><?php echo tep_image_submit('button_update.png', IMAGE_UPDATE); ?></td>
	          </tr>
          </table>
				</td>
      </tr>
      <tr>
	      <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
      </tr>   
	<!-- End of Update Block -->

	<!-- Begin Order Total Block -->
      <tr>
	      <td class="SubTitle" onMouseover="ddrivetip('<?php echo HINT_TOTALS; ?>')"; onMouseout="hideddrivetip()"><?php echo MENUE_TITLE_TOTAL; ?> <img src="images/icon_info.png" border= "0" width="13" height="13" /></td>
			</tr>
      <tr>
	      <td><?php echo tep_draw_separator('pixel_trans.png', '1', '1'); ?></td>
      </tr>   
      <tr>
	      <td>
	<table border="0" cellspacing="0" cellpadding="2" class="dataTableRow">
      <tr class="dataTableHeadingRow">
	  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TOTAL_MODULE; ?></td>
	  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_WEIGHT; ?></td>
	  <td class="dataTableHeadingContent"width="1"><?php echo tep_draw_separator('pixel_trans.png', '1', '1'); ?></td>
	  </tr>
	  <tr>
	  <td class="smallText" align="right"><b><?php echo TABLE_HEADING_TOTAL_WEIGHT; ?></b></td>
	  <td class="smallText" align="right"><?php  echo '<input name="total_order_weight" id="total_order_weight" size="10" value="' . number_format($total_weight, 2, '.', '') . '" readonly="readonly" />'; ?></td>
	  <td></td>
	   </tr>
	<tr class="dataTableHeadingRow">
	  <td class="dataTableHeadingContent"></td>
	  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TAX; ?></td>
	  <td class="dataTableHeadingContent"width="1"><?php echo tep_draw_separator('pixel_trans.png', '1', '1'); ?></td>
	  </tr>
	  <tr>
	  <td class="smallText" align="right"><b><?php echo TABLE_HEADING_SHIPPING_TAX; ?></b></td>
	  <td class="smallText" align="right">
	  <input name="update_shipping_tax" size="10" onKeyUp="getTotals('shipping', '<?php 
	$countryid = tep_get_country_id($order->delivery["country"]);
	$zoneid = tep_get_zone_id($countryid, $order->delivery["state"]);
	$default_tax_name  = tep_get_tax_description($default_tax_class, $countryid, $zoneid);
	//default tax class is set at the top of the file
	echo $default_tax_name; ?>')" value="<?php echo tep_display_tax_value($order->info['shipping_tax']); ?>" id="shipping_tax_rate" />
	</td>
	  <td></td>
	   </tr>
		<tr class="dataTableHeadingRow">
	  <td class="dataTableHeadingContent"></td>
	  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TOTAL_AMOUNT; ?></td>
	  <td class="dataTableHeadingContent"width="1"><?php echo tep_draw_separator('pixel_trans.png', '1', '1'); ?></td>
	</tr>
<?php
// START OF MAKING ALL INPUT FIELDS THE SAME LENGTH 
	$max_length = 0;
	$TotalsLengthArray = array();
	for ($i=0; $i<sizeof($order->totals); $i++) {
		$TotalsLengthArray[] = array("Name" => $order->totals[$i]['title']);
	}
	reset($TotalsLengthArray);
	foreach($TotalsLengthArray as $TotalIndex => $TotalDetails) {
		if (strlen($TotalDetails["Name"]) > $max_length) {
			$max_length = strlen($TotalDetails["Name"]);
		}
	}
// END OF MAKING ALL INPUT FIELDS THE SAME LENGTH

	$TotalsArray = array();
		for ($i=0; $i<sizeof($order->totals); $i++) {
		$TotalsArray[] = array(
		"Name" => $order->totals[$i]['title'], 
		"Price" => number_format($order->totals[$i]['value'], 2, '.', ''), 
		"Class" => $order->totals[$i]['class'], 
		"TotalID" => $order->totals[$i]['orders_total_id']);
		
		$TotalsArray[] = array(
		"Name" => "", 
		"Price" => "", 
		"Class" => "ot_custom", 
		"TotalID" => "0");
	}
	
	array_pop($TotalsArray);
	foreach($TotalsArray as $TotalIndex => $TotalDetails)
	{
		$TotalStyle = "smallText";
		
		if ($TotalDetails["Class"] == "ot_total" || $TotalDetails["Class"] == "ot_subtotal") {
			$id = $TotalDetails["Class"];//subtotal and total should each only exist once
			
			} elseif ($TotalDetails["Class"] == "ot_tax") {
			$id = preg_replace("/:$/", "", $TotalDetails["Name"]) . '-total';
			
			} elseif ($TotalDetails["Class"] == "ot_shipping") {
			$id = $TotalDetails["Class"] . $TotalIndex;
			
			} else {
			$id = $TotalDetails["Class"] . $TotalIndex;
			}
			
		if(//tax, subtotal, and total are not editable, but have all the same format
		$TotalDetails["Class"] == "ot_total" || 
		$TotalDetails["Class"] == "ot_subtotal" || 
		$TotalDetails["Class"] == "ot_tax")
		{
					
			echo '	<tr>' . "\n" .
				   '		<td align="right" class="' . $TotalStyle . '"><b>' . $TotalDetails["Name"] . '</b></td>' .
				   '		<td align="right" class="' . $TotalStyle . '">' . 
				            "<input name='" . $TotalDetails["Name"] . "' size='10' value='" . $TotalDetails["Price"] . "' id='" . $id . "' readonly='readonly' />" . 
						    "<input name='update_totals[$TotalIndex][title]' type='hidden' value='" . trim($TotalDetails["Name"]) . "'>" . 
						    "<input name='update_totals[$TotalIndex][value]' type='hidden' value='" . $TotalDetails["Price"] . "'>" . 
						    "<input name='update_totals[$TotalIndex][class]' type='hidden' value='" . $TotalDetails["Class"] . "'>\n" . 
						    "<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" . '</td>' . 
				   '		<td align="right" class="' . $TotalStyle . '"><b>' . tep_draw_separator('pixel_trans.png', '1', '17') . '</b>' . 
				   '	</tr>' . "\n";
		} else { //the other total components are editable
			echo '	<tr>' . "\n" .
				   '		<td align="right" class="' . $TotalStyle . '">' . "<input name='update_totals[$TotalIndex][title]' size='" . $max_length . "' value='" . tep_html_quotes($TotalDetails["Name"]) . "'>" . '</td>' . "\n" .
				   '		<td align="right" class="' . $TotalStyle . '">' . "<input name='update_totals[$TotalIndex][value]' size='10' value='" . $TotalDetails["Price"] . "' id='" . $id . "' onKeyUp=\"getTotals('shipping', '" . $default_tax_name . "')\">" . 
						    "<input type='hidden' name='update_totals[$TotalIndex][class]' value='" . $TotalDetails["Class"] . "'>" . 
						    "<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" . 
				   '		<td align="right" class="' . $TotalStyle . '"><b>' . tep_draw_separator('pixel_trans.png', '1', '17') . '</b>' . 
					 '   </td>' . "\n" .
				   '	</tr>' . "\n";
		}
	}
	
		?>
</table>