<h2><?php echo $text_upload; ?></h2>
	<?php if($upload_message) { ?><div><?php echo $upload_message; ?></div><?php } ?>	
  <?php if($error_upload_minimum) { ?><div class="warning"><?php echo $error_upload_minimum; ?><img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div><?php } ?>
  <table class="form copu-tbl">
		<thead>
		  <tr>
		    <?php if($upload_display) { ?><td class="image"><?php echo $column_image; ?></td><?php } ?>
		    <td class="name"><?php echo $column_name; ?></td>
		    <td class="size"><?php echo $column_size; ?></td>
        <?php if($date) { ?><td class="date"><?php echo $column_date; ?></td><?php } ?>
		    <?php if($action) { ?><td class="action"><?php echo $column_action; ?></td><?php } ?>
		  </tr>
		</thead>
		<tbody><?php if ($uploads) { ?>
      <?php foreach ($uploads as $upload) { ?>
      <tr id="upload<?php echo $upload['upload_id']; ?>">
        <?php if ($upload['image']) { ?><td class="image">
          <?php if ($upload['popup']) { ?><a href="<?php echo $upload['popup']; ?>" class="colorbox" rel="copubox"><?php } ?><img src="<?php echo $upload['image']; ?>" alt="<?php echo $upload['name']; ?>" title="<?php echo $text_popup; ?>" /><?php if ($upload['popup']) { ?></a><?php } ?>
          </td><?php } ?>
        <td class="name"><a href="<?php echo $upload['href']; ?>" title="<?php echo $text_download; ?>"><?php echo $upload['name']; ?></a></td>
        <td class="size"><?php echo $upload['size']; ?></td>   
        <?php if($date) { ?><td class="date"><?php echo $upload['date']; ?></td><?php } ?>
        <?php if($action) { ?><td class="action"><a href="<?php echo $upload['delete']; ?>" class="delete-btn" id="<?php echo $upload['upload_id']; ?>"><img src="catalog/view/theme/default/image/remove.png" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" /></a></td><?php } ?>
      </tr>
      <?php } ?>
    <?php } else { ?>
      <tr class="empty"><td colspan="<?php echo $colspan; ?>"><?php echo $text_empty; ?></td></tr>
    <?php } ?>
    <?php if($action) { ?>
		  <tr>
		    <td colspan="<?php echo $colspan; ?>" class="action"><a id="button-upload" class="button"><span><?php echo $button_upload; ?></span></a></td>
		  </tr>
    <?php } ?>
		</tbody>
  </table>
<script type="text/javascript"><!--
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
<?php if($action) { ?>
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
          $('#upload' + _upload_id).fadeOut(function() { $(this).remove(); });
        }
        if(json['error']) {
          alert(json['error']);
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  }
  return false;
});

new AjaxUpload('#button-upload', {
  action: 'index.php?route=myoc/copu/upload&type=<?php echo $type; ?><?php echo $nosession; ?>',
  name: 'file',
  autoSubmit: true,
  responseType: 'json',
  onSubmit: function(file, extension) {
    $('.warning').remove();

    $('#button-upload').before('<img src="catalog/view/theme/default/image/loading.gif" class="loading" style="margin-right: 5px;" />');
  },
  onComplete: function(file, json) {
    if (json['success']) {
      $('.copu-tbl tbody tr.empty').remove();
      var newUploadRow = '<tr id="upload' + json['file']['upload_id'] + '">' + (json['file']['image'] ? '<td class="image">' + (json['file']['popup'] ? '<a href="' + json['file']['popup'] + '" class="colorbox" rel="copubox">' : '') + '<img src="' + json['file']['image'] + '" alt="' + json['file']['name'] + '" title="<?php echo $text_popup; ?>" />' + (json['file']['popup'] ? '</a>' : '') + '</td>' : '') + '<td class="name"><a href="' + json['file']['href'] + '" title="<?php echo $text_download; ?>">' + json['file']['name'] + '</a></td><td class="size">' + json['file']['size'] + '</td><?php if($date) { ?><td class="date">' + json['file']['date'] + '</td><?php } ?><td class="action"><a href="' + json['file']['delete'] + '" class="delete-btn" id="' + json['file']['upload_id'] + '"><img src="catalog/view/theme/default/image/remove.png" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" /></a></td></tr>';
      $('.copu-tbl tbody > tr:last').before(newUploadRow);
    }
    
    if (json['error']) {
      $('.copu-tbl').before('<div class="warning">' + json['error'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
    }
    $('.colorbox').colorbox({
      overlayClose: true,
      opacity: 0.5
    });
    $('.loading').remove(); 
  }
});
<?php } ?>
//--></script>