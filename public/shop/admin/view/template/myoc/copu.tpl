<?php echo $header; ?>
<div id="content">
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="heading">
    <h1><img src="view/image/copu.png" alt="" /> <?php echo $common_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <div id="tabs" class="htabs">
      <a href="#tab-customer" onclick="return false;"><?php echo $tab_customer; ?></a>
      <a href="#tab-order" onclick="return false;"><?php echo $tab_order; ?></a>
      <a href="#tab-product" onclick="return false;"><?php echo $tab_product; ?></a>
      <a href="#tab-filetype" onclick="return false;"><?php echo $tab_filetype; ?></a>
    </div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div id="tab-customer">
          <table class="form">
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="customer_status">
                  <option value="1"<?php if ($customer_status) { ?> selected="selected"<?php } ?>><?php echo $text_enabled; ?></option>
                  <option value="0"<?php if (!$customer_status) { ?> selected="selected"<?php } ?>><?php echo $text_disabled; ?></option>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_register; ?></td>
              <td>
                <input type="radio" id="customer_register1" name="customer_register" value="1"<?php if ($customer_register) { ?> checked="checked"<?php } ?> />
                <label for="customer_register1"><?php echo $text_yes; ?></label>
                <input type="radio" id="customer_register0" name="customer_register" value="0"<?php if (!$customer_register) { ?> checked="checked"<?php } ?> />
                <label for="customer_register0"><?php echo $text_no; ?></label>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_limit; ?></td>
              <td><input type="text" name="customer_limit" value="<?php echo $customer_limit; ?>" />
                <?php if ($error_customer_limit) { ?>
                <span class="error"><?php echo $error_customer_limit; ?></div>
                <?php } ?>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_minimum; ?></td>
              <td><input type="text" name="customer_minimum" value="<?php echo $customer_minimum; ?>" />
                <?php if ($error_customer_minimum) { ?>
                <span class="error"><?php echo $error_customer_minimum; ?></div>
                <?php } ?>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_max_filesize; ?></td>
              <td><select name="customer_max_filesize">
                  <option value="512"<?php if(isset($customer_max_filesize) && $customer_max_filesize == 512) { ?> selected="selected"<?php } ?>>500 kb</option>
                  <option value="1024"<?php if(isset($customer_max_filesize) && $customer_max_filesize == 1024) { ?> selected="selected"<?php } ?>>1 MB</option>
                  <option value="2048"<?php if(isset($customer_max_filesize) && $customer_max_filesize == 2048) { ?> selected="selected"<?php } ?>>2 MB</option>
                  <option value="3072"<?php if(isset($customer_max_filesize) && $customer_max_filesize == 3072) { ?> selected="selected"<?php } ?>>3 MB</option>
                  <option value="4096"<?php if(isset($customer_max_filesize) && $customer_max_filesize == 4096) { ?> selected="selected"<?php } ?>>4 MB</option>
                  <option value="5120"<?php if(isset($customer_max_filesize) && $customer_max_filesize == 5120) { ?> selected="selected"<?php } ?>>5 MB</option>
                  <option value="10240"<?php if(isset($customer_max_filesize) && $customer_max_filesize == 10240) { ?> selected="selected"<?php } ?>>10 MB</option>
                  <option value="20480"<?php if(isset($customer_max_filesize) && $customer_max_filesize == 20480) { ?> selected="selected"<?php } ?>>20 MB</option>
                  <option value="51200"<?php if(isset($customer_max_filesize) && $customer_max_filesize == 51200) { ?> selected="selected"<?php } ?>>50 MB</option>
                  <option value="102400"<?php if(isset($customer_max_filesize) && $customer_max_filesize == 102400) { ?> selected="selected"<?php } ?>>100 MB</option>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_message; ?></td>
              <td>
                <div id="customer-languages" class="htabs">
                  <?php foreach ($languages as $language) { ?>
                    <a href="#customer-languages<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
                  <?php } ?>
                </div>
                <?php foreach ($languages as $language) { ?>
                <div id="customer-languages<?php echo $language['language_id']; ?>">
                <textarea name="customer_message[<?php echo $language['language_id']; ?>][message]" id="customer-message<?php echo $language['language_id']; ?>"><?php echo isset($customer_message[$language['language_id']]) ? $customer_message[$language['language_id']]['message'] : ''; ?></textarea></div>
                <?php } ?>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_display; ?></td>
              <td>
                <input type="radio" id="customer_display1" name="customer_display" value="1"<?php if ($customer_display) { ?> checked="checked"<?php } ?> />
                <label for="customer_display1"><?php echo $text_yes; ?></label>
                <input type="radio" id="customer_display0" name="customer_display" value="0"<?php if (!$customer_display) { ?> checked="checked"<?php } ?> />
                <label for="customer_display0"><?php echo $text_no; ?></label>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_filetype; ?></td>
              <td><div id="customer_filetype" class="scrollbox" style="height: 200px;">
                <?php if($filetypes) { ?>
                  <?php $class = 'even'; ?>
                  <?php foreach ($filetypes as $filetype) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>" id="customer_filetype<?php echo $filetype['filetype_id']; ?>">
                    <input type="checkbox" name="customer_filetype[]" value="<?php echo $filetype['filetype_id']; ?>"<?php if (!empty($customer_filetypes) && in_array($filetype['filetype_id'], $customer_filetypes)) { ?> checked="checked"<?php } ?> id="customer_filetype_cbk<?php echo $filetype['filetype_id']; ?>" />
                    <label for="customer_filetype_cbk<?php echo $filetype['filetype_id']; ?>"><?php echo $filetype['ext']; ?></label>
                  </div>
                  <?php } ?>
                <?php } ?>
                </div>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_login; ?></td>
              <td>
                <input type="radio" id="customer_login1" name="customer_login" value="1" checked="checked" />
                <label for="customer_login1"><?php echo $text_yes; ?></label>
                <input type="radio" id="customer_login0" name="customer_login" value="0" disabled="disabled" />
                <label for="customer_login0"><?php echo $text_no; ?></label>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_customer_group; ?></td>
              <td><div class="scrollbox">
                <?php $class = 'even'; ?>
                <?php foreach ($customer_groups as $customer_group) { ?>
                <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                <div class="<?php echo $class; ?>">
                  <input type="checkbox" name="customer_customer_group[]" value="<?php echo $customer_group['customer_group_id']; ?>"<?php if (!empty($customer_customer_groups) && in_array($customer_group['customer_group_id'], $customer_customer_groups)) { ?> checked="checked"<?php } ?> id="customer_customer_group<?php echo $customer_group['customer_group_id']; ?>" />
                  <label for="customer_customer_group<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></label>
                </div>
                <?php } ?>
              </div></td>
            </tr>
            <tr>
              <td><?php echo $entry_store; ?></td>
              <td><div class="scrollbox">
                <?php $class = 'even'; ?>
                <div class="<?php echo $class; ?>">
                  <input type="checkbox" name="customer_store[]" value="0"<?php if (!empty($customer_stores) && in_array('0', $customer_stores)) { ?> checked="checked"<?php } ?> id="customer_store0" />
                  <label for="customer_store0"><?php echo $text_default; ?></label>
                </div>
                <?php foreach ($stores as $store) { ?>
                <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                <div class="<?php echo $class; ?>">
                  <input type="checkbox" name="customer_store[]" value="<?php echo $store['store_id']; ?>"<?php if (!empty($customer_stores) && in_array($store['store_id'], $customer_stores)) { ?> checked="checked"<?php } ?> id="customer_store<?php echo $store['store_id']; ?>" />
                  <label for="customer_store<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
                </div>
                <?php } ?>
              </div></td>
            </tr>
          </table>
      </div>
      <div id="tab-order">
          <table class="form">
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="order_status">
                  <option value="1"<?php if ($order_status) { ?> selected="selected"<?php } ?>><?php echo $text_enabled; ?></option>
                  <option value="0"<?php if (!$order_status) { ?> selected="selected"<?php } ?>><?php echo $text_disabled; ?></option>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_limit; ?></td>
              <td><input type="text" name="order_limit" value="<?php echo $order_limit; ?>" />
                <?php if ($error_order_limit) { ?>
                <span class="error"><?php echo $error_order_limit; ?></div>
                <?php } ?>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_minimum; ?></td>
              <td><input type="text" name="order_minimum" value="<?php echo $order_minimum; ?>" />
                <?php if ($error_order_minimum) { ?>
                <span class="error"><?php echo $error_order_minimum; ?></div>
                <?php } ?>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_max_filesize; ?></td>
              <td><select name="order_max_filesize">
                  <option value="512"<?php if(isset($order_max_filesize) && $order_max_filesize == 512) { ?> selected="selected"<?php } ?>>500 kb</option>
                  <option value="1024"<?php if(isset($order_max_filesize) && $order_max_filesize == 1024) { ?> selected="selected"<?php } ?>>1 MB</option>
                  <option value="2048"<?php if(isset($order_max_filesize) && $order_max_filesize == 2048) { ?> selected="selected"<?php } ?>>2 MB</option>
                  <option value="3072"<?php if(isset($order_max_filesize) && $order_max_filesize == 3072) { ?> selected="selected"<?php } ?>>3 MB</option>
                  <option value="4096"<?php if(isset($order_max_filesize) && $order_max_filesize == 4096) { ?> selected="selected"<?php } ?>>4 MB</option>
                  <option value="5120"<?php if(isset($order_max_filesize) && $order_max_filesize == 5120) { ?> selected="selected"<?php } ?>>5 MB</option>
                  <option value="10240"<?php if(isset($order_max_filesize) && $order_max_filesize == 10240) { ?> selected="selected"<?php } ?>>10 MB</option>
                  <option value="20480"<?php if(isset($order_max_filesize) && $order_max_filesize == 20480) { ?> selected="selected"<?php } ?>>20 MB</option>
                  <option value="51200"<?php if(isset($order_max_filesize) && $order_max_filesize == 51200) { ?> selected="selected"<?php } ?>>50 MB</option>
                  <option value="102400"<?php if(isset($order_max_filesize) && $order_max_filesize == 102400) { ?> selected="selected"<?php } ?>>100 MB</option>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_message; ?></td>
              <td>
                <div id="order-languages" class="htabs">
                  <?php foreach ($languages as $language) { ?>
                    <a href="#order-languages<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
                  <?php } ?>
                </div>
                <?php foreach ($languages as $language) { ?>
                <div id="order-languages<?php echo $language['language_id']; ?>">
                <textarea name="order_message[<?php echo $language['language_id']; ?>][message]" id="order-message<?php echo $language['language_id']; ?>"><?php echo isset($order_message[$language['language_id']]) ? $order_message[$language['language_id']]['message'] : ''; ?></textarea></div>
                <?php } ?>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_display; ?></td>
              <td>
                <input type="radio" id="order_display1" name="order_display" value="1"<?php if ($order_display) { ?> checked="checked"<?php } ?> />
                <label for="order_display1"><?php echo $text_yes; ?></label>
                <input type="radio" id="order_display0" name="order_display" value="0"<?php if (!$order_display) { ?> checked="checked"<?php } ?> />
                <label for="order_display0"><?php echo $text_no; ?></label>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_filetype; ?></td>
              <td><div id="order_filetype" class="scrollbox" style="height: 200px;">
                <?php if($filetypes) { ?>
                  <?php $class = 'even'; ?>
                  <?php foreach ($filetypes as $filetype) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>" id="order_filetype<?php echo $filetype['filetype_id']; ?>">
                    <input type="checkbox" name="order_filetype[]" value="<?php echo $filetype['filetype_id']; ?>"<?php if (!empty($order_filetypes) && in_array($filetype['filetype_id'], $order_filetypes)) { ?> checked="checked"<?php } ?> id="order_filetype_cbk<?php echo $filetype['filetype_id']; ?>" />
                    <label for="order_filetype_cbk<?php echo $filetype['filetype_id']; ?>"><?php echo $filetype['ext']; ?></label>
                  </div>
                  <?php } ?>
                <?php } ?>
                </div>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_login; ?></td>
              <td>
                <input type="radio" id="order_login1" name="order_login" value="1"<?php if ($order_login) { ?> checked="checked"<?php } ?> />
                <label for="order_login1"><?php echo $text_yes; ?></label>
                <input type="radio" id="order_login0" name="order_login" value="0"<?php if (!$order_login) { ?> checked="checked"<?php } ?> />
                <label for="order_login0"><?php echo $text_no; ?></label>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_customer_group; ?></td>
              <td><div class="scrollbox">
                <?php $class = 'even'; ?>
                <?php foreach ($customer_groups as $customer_group) { ?>
                <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                <div class="<?php echo $class; ?>">
                  <input type="checkbox" name="order_customer_group[]" value="<?php echo $customer_group['customer_group_id']; ?>"<?php if (!empty($order_customer_groups) && in_array($customer_group['customer_group_id'], $order_customer_groups)) { ?> checked="checked"<?php } ?> id="order_customer_group<?php echo $customer_group['customer_group_id']; ?>" />
                  <label for="order_customer_group<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></label>
                </div>
                <?php } ?>
              </div></td>
            </tr>
            <tr>
              <td><?php echo $entry_store; ?></td>
              <td><div class="scrollbox">
                <?php $class = 'even'; ?>
                <div class="<?php echo $class; ?>">
                  <input type="checkbox" name="order_store[]" value="0"<?php if (!empty($order_stores) && in_array('0', $order_stores)) { ?> checked="checked"<?php } ?> id="order_store0" />
                  <label for="order_store0"><?php echo $text_default; ?></label>
                </div>
                <?php foreach ($stores as $store) { ?>
                <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                <div class="<?php echo $class; ?>">
                  <input type="checkbox" name="order_store[]" value="<?php echo $store['store_id']; ?>"<?php if (!empty($order_stores) && in_array($store['store_id'], $order_stores)) { ?> checked="checked"<?php } ?> id="order_store<?php echo $store['store_id']; ?>" />
                  <label for="order_store<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
                </div>
                <?php } ?>
              </div></td>
            </tr>
          </table>
      </div>
      <div id="tab-product">
          <table class="form">
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="product_status">
                  <option value="1"<?php if ($product_status) { ?> selected="selected"<?php } ?>><?php echo $text_enabled; ?></option>
                  <option value="0"<?php if (!$product_status) { ?> selected="selected"<?php } ?>><?php echo $text_disabled; ?></option>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_limit; ?></td>
              <td><input type="text" name="product_limit" value="<?php echo $product_limit; ?>" />
                <?php if ($error_product_limit) { ?>
                <span class="error"><?php echo $error_product_limit; ?></span>
                <?php } ?>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_minimum; ?></td>
              <td><input type="text" name="product_minimum" value="<?php echo $product_minimum; ?>" />
                <?php if ($error_product_minimum) { ?>
                <span class="error"><?php echo $error_product_minimum; ?></span>
                <?php } ?>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_force_qty; ?></td>
              <td>
                <input type="radio" id="product_force_qty1" name="product_force_qty" value="1"<?php if ($product_force_qty) { ?> checked="checked"<?php } ?> />
                <label for="product_force_qty1"><?php echo $text_yes; ?></label>
                <input type="radio" id="product_force_qty0" name="product_force_qty" value="0"<?php if (!$product_force_qty) { ?> checked="checked"<?php } ?> />
                <label for="product_force_qty0"><?php echo $text_no; ?></label>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_max_filesize; ?></td>
              <td><select name="product_max_filesize">
                  <option value="512"<?php if(isset($product_max_filesize) && $product_max_filesize == 512) { ?> selected="selected"<?php } ?>>500 kb</option>
                  <option value="1024"<?php if(isset($product_max_filesize) && $product_max_filesize == 1024) { ?> selected="selected"<?php } ?>>1 MB</option>
                  <option value="2048"<?php if(isset($product_max_filesize) && $product_max_filesize == 2048) { ?> selected="selected"<?php } ?>>2 MB</option>
                  <option value="3072"<?php if(isset($product_max_filesize) && $product_max_filesize == 3072) { ?> selected="selected"<?php } ?>>3 MB</option>
                  <option value="4096"<?php if(isset($product_max_filesize) && $product_max_filesize == 4096) { ?> selected="selected"<?php } ?>>4 MB</option>
                  <option value="5120"<?php if(isset($product_max_filesize) && $product_max_filesize == 5120) { ?> selected="selected"<?php } ?>>5 MB</option>
                  <option value="10240"<?php if(isset($product_max_filesize) && $product_max_filesize == 10240) { ?> selected="selected"<?php } ?>>10 MB</option>
                  <option value="20480"<?php if(isset($product_max_filesize) && $product_max_filesize == 20480) { ?> selected="selected"<?php } ?>>20 MB</option>
                  <option value="51200"<?php if(isset($product_max_filesize) && $product_max_filesize == 51200) { ?> selected="selected"<?php } ?>>50 MB</option>
                  <option value="102400"<?php if(isset($product_max_filesize) && $product_max_filesize == 102400) { ?> selected="selected"<?php } ?>>100 MB</option>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_message; ?></td>
              <td>
                <div id="product-languages" class="htabs">
                  <?php foreach ($languages as $language) { ?>
                    <a href="#product-languages<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
                  <?php } ?>
                </div>
                <?php foreach ($languages as $language) { ?>
                <div id="product-languages<?php echo $language['language_id']; ?>">
                <textarea name="product_message[<?php echo $language['language_id']; ?>][message]" id="product-message<?php echo $language['language_id']; ?>"><?php echo isset($product_message[$language['language_id']]) ? $product_message[$language['language_id']]['message'] : ''; ?></textarea></div>
                <?php } ?>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_display; ?></td>
              <td>
                <input type="radio" id="product_display1" name="product_display" value="1"<?php if ($product_display) { ?> checked="checked"<?php } ?> />
                <label for="product_display1"><?php echo $text_yes; ?></label>
                <input type="radio" id="product_display0" name="product_display" value="0"<?php if (!$product_display) { ?> checked="checked"<?php } ?> />
                <label for="product_display0"><?php echo $text_no; ?></label>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_replace; ?></td>
              <td>
                <input type="radio" id="product_replace1" name="product_replace" value="1"<?php if ($product_replace) { ?> checked="checked"<?php } ?> />
                <label for="product_replace1"><?php echo $text_yes; ?></label>
                <input type="radio" id="product_replace0" name="product_replace" value="0"<?php if (!$product_replace) { ?> checked="checked"<?php } ?> />
                <label for="product_replace0"><?php echo $text_no; ?></label>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_filetype; ?></td>
              <td><div id="product_filetype" class="scrollbox" style="height: 200px;">
                <?php if($filetypes) { ?>
                  <?php $class = 'even'; ?>
                  <?php foreach ($filetypes as $filetype) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>" id="product_filetype<?php echo $filetype['filetype_id']; ?>">
                    <input type="checkbox" name="product_filetype[]" value="<?php echo $filetype['filetype_id']; ?>"<?php if (!empty($product_filetypes) && in_array($filetype['filetype_id'], $product_filetypes)) { ?> checked="checked"<?php } ?> id="product_filetype_cbk<?php echo $filetype['filetype_id']; ?>" />
                    <label for="product_filetype_cbk<?php echo $filetype['filetype_id']; ?>"><?php echo $filetype['ext']; ?></label>
                  </div>
                  <?php } ?>
                <?php } ?>
                </div>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_upload_option; ?></td>
              <td><div class="scrollbox">
                <?php $class = 'even'; ?>
                <?php foreach ($upload_options as $upload_option) { ?>
                <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                <div class="<?php echo $class; ?>">
                  <input type="checkbox" name="product_option[]" value="<?php echo $upload_option['option_id']; ?>"<?php if (!empty($product_options) && in_array($upload_option['option_id'], $product_options)) { ?> checked="checked"<?php } ?> id="product_option<?php echo $upload_option['option_id']; ?>" />
                  <label for="product_option<?php echo $upload_option['option_id']; ?>"><?php echo $upload_option['name']; ?></label>
                </div>
                <?php } ?>
              </div></td>
            </tr>
            <tr>
              <td><?php echo $entry_login; ?></td>
              <td>
                <input type="radio" id="product_login1" name="product_login" value="1"<?php if ($product_login) { ?> checked="checked"<?php } ?> />
                <label for="product_login1"><?php echo $text_yes; ?></label>
                <input type="radio" id="product_login0" name="product_login" value="0"<?php if (!$product_login) { ?> checked="checked"<?php } ?> />
                <label for="product_login0"><?php echo $text_no; ?></label>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_customer_group; ?></td>
              <td><div class="scrollbox">
                <?php $class = 'even'; ?>
                <?php foreach ($customer_groups as $customer_group) { ?>
                <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                <div class="<?php echo $class; ?>">
                  <input type="checkbox" name="product_customer_group[]" value="<?php echo $customer_group['customer_group_id']; ?>"<?php if (!empty($product_customer_groups) && in_array($customer_group['customer_group_id'], $product_customer_groups)) { ?> checked="checked"<?php } ?> id="product_customer_group<?php echo $customer_group['customer_group_id']; ?>" />
                  <label for="product_customer_group<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></label>
                </div>
                <?php } ?>
              </div></td>
            </tr>
            <tr>
              <td><?php echo $entry_store; ?></td>
              <td><div class="scrollbox">
                <?php $class = 'even'; ?>
                <div class="<?php echo $class; ?>">
                  <input type="checkbox" name="product_store[]" value="0"<?php if (!empty($product_stores) && in_array('0', $product_stores)) { ?> checked="checked"<?php } ?> id="product_store0" />
                  <label for="product_store0"><?php echo $text_default; ?></label>
                </div>
                <?php foreach ($stores as $store) { ?>
                <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                <div class="<?php echo $class; ?>">
                  <input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>"<?php if (!empty($product_stores) && in_array($store['store_id'], $product_stores)) { ?> checked="checked"<?php } ?> id="product_store<?php echo $store['store_id']; ?>" />
                  <label for="product_store<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
                </div>
                <?php } ?>
              </div></td>
            </tr>
          </table>
      </div>
      <div id="tab-filetype">
        <table class="list" id="filetype-tbl">
          <thead>
            <tr>
              <td class="left"><?php echo $column_ext; ?></td>
              <td class="left"><?php echo $column_mime; ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($filetypes) { ?>
            <?php foreach ($filetypes as $filetype) { ?>
            <tr id="filetype<?php echo $filetype['filetype_id']; ?>">
              <td class="left ext"><?php echo $filetype['ext']; ?></td>
              <td class="left"><?php echo $filetype['mime']; ?></td>
              <td class="right">[ <a href="<?php echo $filetype['delete']; ?>" class="delete-filetype-btn" id="<?php echo $filetype['filetype_id']; ?>"><?php echo $text_delete; ?></a> ]</td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="3"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
            <tr>
              <td class="left"><?php echo $entry_ext; ?><input type="text" size="3" id="new-ext" value="" /></td>
              <td class="left"><?php echo $entry_mime; ?><input type="text" size="130" id="new-mime" value="" /></td>
              <td class="right"><a class="button" id="add-filetype-btn"><span><?php echo $button_add_filetype; ?></span></a></td>
            </tr>
          </tbody>
        </table>
      </div>
    </form>
    <div style="font-size:11px;color:#666;"><?php echo $myoc_copyright; ?></div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
$('#tabs a').tabs();
$('#customer-languages a').tabs();
$('#order-languages a').tabs();
$('#product-languages a').tabs();

<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('customer-message<?php echo $language['language_id']; ?>', {
  filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
  filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
  filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
  filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
  filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
  filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
CKEDITOR.replace('order-message<?php echo $language['language_id']; ?>', {
  filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
  filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
  filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
  filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
  filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
  filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
CKEDITOR.replace('product-message<?php echo $language['language_id']; ?>', {
  filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
  filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
  filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
  filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
  filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
  filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } ?>

$('.delete-filetype-btn').live('click', function() {
  var _thisBtn = $(this);
  var _filetype_id = _thisBtn.attr('id');
  var _url = _thisBtn.attr('href');
  if(confirm('<?php echo $text_confirm_delete; ?> \'' + $('#filetype' + _filetype_id + ' td.ext').html() + '\'?'))
  {
    $.ajax({
      url: _url,
      dataType: 'json',
      beforeSend: function() {
        _thisBtn.before('<span class="wait"><img src="view/image/loading.gif" alt="" />&nbsp;&nbsp;</span>');
        $('.warning').remove();
      },
      complete: function() {
        $('.wait').remove();
      },      
      success: function(json) {
        if(json['success']) {
          $('#filetype' + _filetype_id).fadeOut(function() { $(this).remove(); });
          $('#customer_filetype' + _filetype_id).remove();
          $('#order_filetype' + _filetype_id).remove();
          $('#product_filetype' + _filetype_id).remove();
        }
        if(json['error']) {
          $('.box').before('<div class="warning" style="display: none;">' + json['error'] + '</div>');
          $('.warning').fadeIn('slow');
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  }
  return false;
});
$('#add-filetype-btn').click(function() {
  _thisBtn = $(this);
  var ext = $("#new-ext").val().replace(/ /g, '');
  var mime = $("#new-mime").val().replace(/ /g, '');
  if(ext == "") {
    return false;
  } else {
    $.ajax({
      url: 'index.php?route=module/myoccopu/insert&token=<?php echo $token; ?>&ext=' + ext + '&mime=' + mime,
      dataType: 'json',
      beforeSend: function() {
        _thisBtn.before('<span class="wait"><img src="view/image/loading.gif" alt="" />&nbsp;&nbsp;</span>');
        $('.warning').remove();
      },
      complete: function() {
        $('.wait').remove();
      },      
      success: function(json) {
        if(json['success']) {
          var newFiletypeRow = '<tr id="filetype' + json['filetype']['filetype_id'] + '"><td class="left ext">' + json['filetype']['ext'] + '</td><td class="left">' + json['filetype']['mime'] + '</td><td class="right">[ <a href="index.php?route=module/myoccopu/delete&filetype_id=' + json['filetype']['filetype_id'] + '&token=<?php echo $token; ?>" class="delete-filetype-btn" id="' + json['filetype']['filetype_id'] + '"><?php echo $text_delete; ?></a> ]</td></tr>';
          $('#filetype-tbl tbody > tr:last').before(newFiletypeRow);
          var _class = ($('#customer_filetype div:last').attr('class') == 'even') ? 'odd' : 'even';
          $('#customer_filetype').append('<div class="' + _class + '" id="customer_filetype' + json['filetype']['filetype_id'] + '"><input type="checkbox" name="customer_filetype[]" value="' + json['filetype']['filetype_id'] + '" /> ' + json['filetype']['ext'] + '</div>');
          var _class = ($('#order_filetype div:last').attr('class') == 'even') ? 'odd' : 'even';
          $('#order_filetype').append('<div class="' + _class + '" id="order_filetype' + json['filetype']['filetype_id'] + '"><input type="checkbox" name="order_filetype[]" value="' + json['filetype']['filetype_id'] + '" /> ' + json['filetype']['ext'] + '</div>');
          var _class = ($('#product_filetype div:last').attr('class') == 'even') ? 'odd' : 'even';
          $('#product_filetype').append('<div class="' + _class + '" id="product_filetype' + json['filetype']['filetype_id'] + '"><input type="checkbox" name="product_filetype[]" value="' + json['filetype']['filetype_id'] + '" /> ' + json['filetype']['ext'] + '</div>');
        }
        if(json['error']) {
          $('.box').before('<div class="warning" style="display: none;">' + json['error'] + '</div>');
          $('.warning').fadeIn('slow');
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  }
});
//--></script>
<?php echo $footer; ?>