<p><b><?php echo $text_upload; ?></b></p>
<table class="product">
  <tr class="heading">
    <td align="left"><b><?php echo $column_name; ?></b></td>
    <td align="right"><b><?php echo $column_size; ?></b></td>
  </tr>
  <?php if ($uploads) { ?>
  <?php foreach ($uploads as $file) { ?>
  <tr>
    <td align="left"><?php echo $file['name']; ?></td>
    <td align="right"><?php echo $file['size']; ?></td>
  </tr>
  <?php } ?>
  <?php } else { ?>
  <tr>
    <td align="center" colspan="2"><?php echo $text_empty; ?></td>
  </tr>
  <?php } ?>
</table>