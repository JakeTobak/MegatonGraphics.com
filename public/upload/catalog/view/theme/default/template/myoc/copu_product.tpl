<?php if($upload_message) { ?><div><?php echo $upload_message; ?></div><?php } ?>
<table id="copu-tbl-<?php echo $product_option_id; ?>" class="form copu-tbl" style="margin-bottom:1px;">
  <thead>
    <tr>
      <?php if($upload_display) { ?><td class="image" style="width:<?php echo $column_image_width; ?>px;"><?php echo $column_image; ?></td><?php } ?>
      <td class="name"><?php echo $column_name; ?></td>
      <td class="action"></td>
    </tr>
  </thead>
  <tbody><?php if ($uploads) { ?>
    <?php foreach ($uploads as $upload) { ?>
    <tr id="upload<?php echo $upload['upload_id']; ?>">
      <?php if ($upload['image']) { ?><td class="image" style="width:<?php echo $column_image_width; ?>px;">
        <?php if ($upload['popup']) { ?><a href="<?php echo $upload['popup']; ?>" class="colorbox" rel="copubox"><?php } ?><img src="<?php echo $upload['image']; ?>" alt="<?php echo $upload['name']; ?>" title="<?php echo $text_popup; ?>" /><?php if ($upload['popup']) { ?></a><?php } ?>
        </td><?php } ?>
      <td class="name"><a href="<?php echo $upload['href']; ?>" title="<?php echo $text_download; ?>"><?php echo $upload['name']; ?></a><br /><b><?php echo $column_size; ?>:</b> <?php echo $upload['size']; ?></td>     
      <td class="action"><a href="<?php echo $upload['delete']; ?>" class="delete-btn" id="<?php echo $upload['upload_id']; ?>"><img src="catalog/view/theme/default/image/remove.png" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" /></a></td>
    </tr>
    <?php } ?>
  <?php } else { ?>
    <tr class="empty"><td colspan="<?php echo $colspan; ?>"><?php echo $text_empty; ?></td></tr>
  <?php } ?>
    <tr>
      <td colspan="<?php echo $colspan; ?>" class="action"><a id="button-upload-<?php echo $product_option_id; ?>" class="button"><span><?php echo $button_upload; ?></span></a></td>
    </tr>
  </tbody>
</table>
</div><br /><!-- close default option <div> -->
<script type="text/javascript"><!--
<?php if(isset($upload['replace']) && $upload['replace']) { ?>
$('#image').parent().attr('href', '<?php echo $upload['popup']; ?>');
$('#image').attr('src', '<?php echo $upload['replace']; ?>');
<?php } ?>
<?php if($force_qty) { ?>
$(document).ready(function() {
  $('input[name=quantity]').val($('.delete-btn').length > 0 ? $('.delete-btn').length : parseInt($('input[name=quantity]').val())); 
});
<?php } ?>
if($.isFunction($.fn.fancybox)) {
  (function($) {
      $.fn.colorbox = function() {
          return $.fn.fancybox.apply(this, arguments);
      };
  })(jQuery);
}
$('.colorbox').colorbox({
  overlayClose: true,
  opacity: 0.5
});
$('.delete-btn').die('click.deleteBtn');
$('.delete-btn').live("click.deleteBtn", function() {
  var _thisBtn = $(this);
  var _upload_id = _thisBtn.attr('id');
  var _url = _thisBtn.attr('href');
  if(confirm('<?php echo $text_confirm_delete; ?>'))
  {
    $.ajax({
      url: _url,
      dataType: 'json',
      beforeSend: function() {
        _thisBtn.before('<span class="wait"><img src="catalog/view/theme/default/image/loading.gif" alt="" />&nbsp;&nbsp;</span>');
      },
      complete: function() {
        $('.wait').remove();
      },      
      success: function(json) {
        if(json['success']) {
          _thisBtn.remove();
          $('#upload' + _upload_id).fadeOut(function() { $(this).remove(); });
        }
        <?php if($force_qty) { ?>
        $('input[name=quantity]').val(parseInt($('input[name=quantity]').val()) > 1 ? $('.delete-btn').length : 1);
        <?php } ?>
        if(json['error']) {
          alert(json['error']);
          $('#upload' + _upload_id).fadeOut(function() { $(this).remove(); });
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  }
  return false;
});
var empty_row = '<tr class="empty"><td colspan="<?php echo $colspan; ?>"><?php echo $text_empty; ?></td></tr>';
new AjaxUpload('#button-upload-<?php echo $product_option_id; ?>', {
  action: 'index.php?route=myoc/copu/upload&type=product&type_id=<?php echo $product_id; ?>&product_option_id=<?php echo $product_option_id; ?>&filename_length=20',
  name: 'file',
  autoSubmit: true,
  responseType: 'json',
  onSubmit: function(file, extension) {
    $('.warning').remove();

    $('#button-upload-<?php echo $product_option_id; ?>').before('<img src="catalog/view/theme/default/image/loading.gif" class="loading" style="margin-right: 5px;" />');
  },
  onComplete: function(file, json) {
    if (json['success']) {
      $('#copu-tbl-<?php echo $product_option_id; ?> tbody tr.empty').remove();
      var newUploadRow = '<tr id="upload' + json['file']['upload_id'] + '">' + (json['file']['image'] ? '<td class="image" style="width:<?php echo $column_image_width; ?>px;">' + (json['file']['popup'] ? '<a href="' + json['file']['popup'] + '" class="colorbox" rel="copubox">' : '') + '<img src="' + json['file']['image'] + '" alt="' + json['file']['name'] + '" title="<?php echo $text_popup; ?>" />' + (json['file']['popup'] ? '</a>' : '') + '</td>' : '') + '<td class="name"><a href="' + json['file']['href'] + '" title="<?php echo $text_download; ?>">' + json['file']['name'] + '</a><br /><b><?php echo $column_size; ?>:</b> ' + json['file']['size'] + '</td><td class="action"><a href="' + json['file']['delete'] + '" class="delete-btn" id="' + json['file']['upload_id'] + '"><img src="catalog/view/theme/default/image/remove.png" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" /></a></td></tr>';
      $('#copu-tbl-<?php echo $product_option_id; ?> tbody > tr:last').before(newUploadRow);

      if(json['file']['replace']) {
        $('#image').parent().attr('href', json['file']['popup']);
        $('#image').attr('src', json['file']['replace']);
      }
      <?php if($force_qty) { ?>
      $('input[name=quantity]').val($('.delete-btn').length);
      <?php } ?>

      $('.colorbox').colorbox({
        overlayClose: true,
        opacity: 0.5
      });
    }
    
    if (json['error']) {
      $('#copu-tbl-<?php echo $product_option_id; ?>').before('<div class="warning">' + json['error'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
    }
    $('.loading').remove(); 
  }
});
//--></script>