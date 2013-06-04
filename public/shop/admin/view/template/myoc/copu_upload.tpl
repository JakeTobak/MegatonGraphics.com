<div id="tab-copu"<?php if($type == 'order') { ?> class="vtabs-content"<?php } ?>>
  <table class="list copu-tbl">
    <thead>
      <tr>
        <td class="center"><?php echo $column_image; ?></td>
        <td class="left"><?php echo $column_name; ?></td>
        <td class="right"><?php echo $column_size; ?></td>
        <td class="right"><?php echo $column_date; ?></td>
        <?php if($delete) { ?><td class="right"><?php echo $column_action; ?></td><?php } ?>
      </tr>
    </thead>
    <tbody>
      <?php if ($uploads) { ?>
      <?php foreach ($uploads as $file) { ?>
      <tr id="upload<?php echo $file['upload_id']; ?>">
        <?php if($upload) { ?><input type="hidden" name="<?php echo $type; ?>_upload[]" value="<?php echo $file['upload_id']; ?>" /><?php } ?>
        <td class="center"><?php if($file['popup']) { ?><a href="<?php echo $file['popup']; ?>" class="colorbox" rel="copubox"><?php } ?><img src="<?php echo $file['image']; ?>" alt="<?php echo $file['name']; ?>" title="<?php echo $text_popup; ?>" /><?php if($file['popup']) { ?></a><?php } ?></td>
        <td class="left"><a href="<?php echo $file['href']; ?>" title="<?php echo $text_download; ?>"><?php echo $file['name']; ?></a></td>
        <td class="right"><?php echo $file['size']; ?></td>
        <td class="right"><?php echo $file['date']; ?></td>
        <?php if($delete) { ?><td class="right">[ <a href="<?php echo $file['delete']; ?>" class="delete-btn-info" id="<?php echo $file['upload_id']; ?>"><?php echo $text_delete; ?></a> ]</td><?php } ?>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr class="empty">
        <td class="center" colspan="<?php echo $colspan; ?>"><?php echo $text_empty; ?></td>
      </tr>
      <?php } ?>
      <?php if($upload) { ?>
      <tr>
        <td colspan="<?php echo $colspan; ?>" class="right"><a id="button-upload" class="button"><span><?php echo $button_upload; ?></span></a></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
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
<?php if($delete) { ?>
$('.delete-btn-info').die('click.deleteBtnInfo');
$('.delete-btn-info').live('click.deleteBtnInfo', function() {
  var _thisBtn = $(this);
  var _url = _thisBtn.attr('href');
  var _upload_id = _thisBtn.attr('id');
  if(confirm('<?php echo $text_confirm_delete; ?>?'))
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
          $('#upload' + _upload_id).fadeOut(function() { $(this).remove(); });
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
<?php } ?>
<?php if($upload) { ?>
new AjaxUpload('#button-upload', {
  action: 'index.php?route=myoc/copu/upload&token=<?php echo $token; ?>',
  name: 'file',
  autoSubmit: true,
  responseType: 'json',
  onSubmit: function(file, extension) {
    $('.warning').remove();

    $('#button-upload').before('<img class="loading" src="view/image/loading.gif" style="margin-right: 5px;" />');
  },
  onComplete: function(file, json) {
    if (json['success']) {
      $('.copu-tbl tbody tr.empty').remove();
      var newUploadRow = '<tr id="upload' + json['file']['upload_id'] + '"><?php if($upload) { ?><input type="hidden" name="<?php echo $type; ?>_upload[]" value="' + json['file']['upload_id'] + '" /><?php } ?><td class="center">' + (json['file']['popup'] ? '<a href="' + json['file']['popup'] + '" class="colorbox" rel="copubox">' : '') + '<img src="' + json['file']['image'] + '" alt="' + json['file']['name'] + '" title="<?php echo $text_popup; ?>" />' + (json['file']['popup'] ? '</a>' : '') + '</td><td class="left"><a href="' + json['file']['href'] + '" title="<?php echo $text_download; ?>">' + json['file']['name'] + '</a></td><td class="right">' + json['file']['size'] + '</td><td class="right">' + json['file']['date'] + '</td><?php if($delete) { ?><td class="right">[ <a href="' + json['file']['delete'] + '" class="delete-btn-info" id="' + json['file']['upload_id'] + '"><?php echo $text_delete; ?></a> ]</td><?php } ?></tr>';
      $('.copu-tbl tbody > tr:last').before(newUploadRow);
    }
    
    if (json['error']) {
      $('.box').before('<div class="warning" style="display: none;">' + json['error'] + '</div>');
      $('.warning').fadeIn('slow');
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