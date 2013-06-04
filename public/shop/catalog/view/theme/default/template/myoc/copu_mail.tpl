<?php if($format == 'html') { ?>
<p><b><?php echo $text_upload; ?></b></p>
<table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
  <thead>
    <tr>
      <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;"><?php echo $column_name; ?></td>
      <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 7px; color: #222222;"><?php echo $column_size; ?></td>
    </tr>
  </thead>
  <tbody>
    <?php if($uploads) { ?>
    <?php foreach ($uploads as $upload) { ?>
    <tr>
      <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><?php echo $upload['name']; ?></td>
      <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?php echo $upload['size']; ?></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr><td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: center; padding: 7px;" colspan="2"><?php echo $text_empty; ?></td></tr>
    <?php } ?>
  </tbody>
</table>
<?php } ?>
<?php if($format == 'text') { ?>
<?php echo $text_upload; ?>

<?php if($uploads) { ?>
<?php foreach ($uploads as $upload) { ?>
<?php echo $upload['name']; ?> [<?php echo $upload['size']; ?>]
<?php } ?>
<?php } else { ?>
<?php echo $text_empty; ?>

<?php } ?>

<?php } ?>