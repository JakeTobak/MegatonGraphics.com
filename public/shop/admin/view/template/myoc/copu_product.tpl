<?php if($html) { ?>
html += '<div>';
if (option['required']) {
  html += '<span class="required">*</span> ';
}          
html += option['name'] + '<br />';
html += '  <table id="option-' + option['product_option_id'] + '" class="list copu-tbl">';
html += '    <thead>';
html += '      <tr>';
html += '        <td class="center" style="background-color: #EFEFEF;"><?php echo $column_image; ?></td>';
html += '        <td class="left" style="background-color: #EFEFEF;"><?php echo $column_name; ?></td>';
html += '        <td class="right" style="background-color: #EFEFEF;"><?php echo $column_action; ?></td>';
html += '      </tr>';
html += '    </thead>';
html += '    <tbody>';
html += '      <tr class="empty">';
html += '        <td class="center" colspan="3"><?php echo $text_empty; ?></td>';
html += '      </tr>';
html += '      <tr>';
html += '        <td colspan="3" class="right"><a id="button-upload-' + option['product_option_id'] + '" class="button"><span><?php echo $button_upload; ?></span></a></td>';
html += '      </tr>';
html += '    </tbody>';
html += '  </table>';
html += '</div>';
<?php } ?>
<?php if($javascript) { ?>
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
$('.delete-btn-update').die('click.deleteBtnUpdate');
$('.delete-btn-update').live('click.deleteBtnUpdate', function() {
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
        $('input[name=quantity]').val(parseInt($('input[name=quantity]').val()) > 1 ? $('.delete-btn-update').length : 1);
        <?php } ?>
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
new AjaxUpload('#button-upload-' + option['product_option_id'], {
  action: 'index.php?route=myoc/copu/upload&token=<?php echo $token; ?>',
  name: 'file',
  autoSubmit: true,
  responseType: 'json',
  data: option,
  onSubmit: function(file, extension) {
    $('.warning').remove();

    $('#button-upload-' + this._settings.data['product_option_id']).before('<img class="loading" src="view/image/loading.gif" style="margin-right: 5px;" />');
  },
  onComplete: function(file, json) {
    if (json['success']) {
      $('#option-' + this._settings.data['product_option_id'] + ' tbody tr.empty').remove();
      var newUploadRow = '<tr id="upload' + json['file']['upload_id'] + '"><input type="hidden" name="option[' + this._settings.data['product_option_id'] + '][]" value="' + json['file']['file'] + '" /><td class="center">' + (json['file']['popup'] ? '<a href="' + json['file']['popup'] + '" class="colorbox" rel="copubox">' : '') + '<img src="' + json['file']['image'] + '" alt="' + json['file']['name'] + '" title="<?php echo $text_popup; ?>" />' + (json['file']['popup'] ? '</a>' : '') + '</td><td class="left"><a href="' + json['file']['href'] + '" title="<?php echo $text_download; ?>">' + json['file']['name'] + '</a><br /><b><?php echo $column_size; ?>:</b> ' + json['file']['size'] + '</td><td class="right">[ <a href="' + json['file']['delete'] + '" class="delete-btn-update" id="' + json['file']['upload_id'] + '"><?php echo $text_delete; ?></a> ]</td></tr>';
      $('#option-' + this._settings.data['product_option_id'] + ' tbody > tr:last').before(newUploadRow);
      <?php if($force_qty) { ?>
        $('input[name=quantity]').val($('.delete-btn-update').length);
      <?php } ?>
    }
    
    if (json['error']) {
      $('#option-' + this._settings.data['product_option_id']).before('<div class="warning" style="display: none;">' + json['error'] + '</div>');
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