<?php
class ModelMyocCopu extends Model {
	public function addFiletype($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "myoc_filetype SET ext = '" . $this->db->escape(strtolower($data['ext'])) . "', mime = '" . $this->db->escape(strtolower(str_replace("\r\n",'',$data['mime']))) . "'");

		$filetype_id = $this->db->getLastId();
		return array(
			'filetype_id' => $filetype_id,
			'ext' => $data['ext'],
			'mime' => $data['mime'],
		);
	}

	public function deleteFiletype($filetype_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "myoc_filetype WHERE filetype_id = '" . (int)$filetype_id . "'");
	}

	public function getFiletype($filetype_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "myoc_filetype WHERE filetype_id = '" . (int)$filetype_id . "'");

		return $query->row;
	}

	public function getFiletypes() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "myoc_filetype ORDER BY ext");
		
		return $query->rows;
	}

	public function addUpload($data) {
		$mask = function_exists('utf8_substr') ? basename(utf8_substr($data['filename'], 0, utf8_strrpos($data['filename'], '.'))) : basename(substr($data['filename'], 0, strrpos($data['filename'], '.')));
		$this->db->query("INSERT INTO " . DB_PREFIX . "myoc_upload SET filename = '" . $this->db->escape($data['filename']) . "', mask = '" . $this->db->escape($mask) . "', date_added = NOW();");
		$upload_id = $this->db->getLastId();

		return $upload_id;
	}

	public function addCustomerUpload($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "myoc_customer_upload SET customer_id = '" . (int)$data['customer_id'] . "', upload_id = '" . (int)$data['upload_id'] . "';");
	}

	public function addOrderUpload($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "myoc_order_upload SET order_id = '" . (int)$data['order_id'] . "', upload_id = '" . (int)$data['upload_id'] . "';");
	}

	public function getUploads($data) {
		if(isset($data['customer_id'])) {
			$query = $this->db->query("SELECT u.* FROM " . DB_PREFIX . "myoc_customer_upload cu LEFT JOIN `" . DB_PREFIX . "myoc_upload` u ON (cu.upload_id = u.upload_id) WHERE cu.customer_id = '" . (int)$data['customer_id'] . "' ORDER BY u.date_added DESC");
		}
		if(isset($data['order_id'])) {
			$query = $this->db->query("SELECT u.* FROM " . DB_PREFIX . "myoc_order_upload ou LEFT JOIN `" . DB_PREFIX . "myoc_upload` u ON (ou.upload_id = u.upload_id) WHERE ou.order_id = '" . (int)$data['order_id'] . "' ORDER BY u.date_added DESC");
		}
		return $query->rows;
	}

	public function getUpload($upload_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "myoc_upload WHERE upload_id = '" . (int)$upload_id . "'");

		return $query->row;
	}

	public function deleteUpload($upload_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "myoc_customer_upload WHERE upload_id = '" . (int)$upload_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "myoc_order_upload WHERE upload_id = '" . (int)$upload_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "myoc_upload WHERE upload_id = '" . (int)$upload_id . "'");
	}

	public function installTable()
	{
		$this->uninstallTable();
		$this->upgradeTable();
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "myoc_customer_upload` (
			  `customer_id` int(11) NOT NULL,
			  `upload_id` int(11) NOT NULL,
			  PRIMARY KEY (`customer_id`,`upload_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "myoc_order_upload` (
			  `order_id` int(11) NOT NULL,
			  `upload_id` int(11) NOT NULL,
			  PRIMARY KEY (`order_id`,`upload_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "myoc_upload` (
			  `upload_id` int(11) NOT NULL AUTO_INCREMENT,
			  `filename` varchar(168) COLLATE utf8_bin NOT NULL DEFAULT '',
			  `mask` varchar(136) COLLATE utf8_bin NOT NULL DEFAULT '',
			  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			  PRIMARY KEY (`upload_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "myoc_filetype` (
			  `filetype_id` int(11) NOT NULL AUTO_INCREMENT,
			  `ext` varchar(16) NOT NULL,
			  `mime` text NOT NULL,
			  PRIMARY KEY (`filetype_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

		//preset file extensions
		$file_ext = array(
			'3gp' => 'audio/3gpp,video/3gpp',
			'ai' => 'application/postscript',
			'avi' => 'video/avi,video/msvideo,video/x-msvideo,image/avi,video/xmpg2,application/x-troff-msvideo,audio/aiff,audio/avi',
			'bmp' => 'image/bmp,image/x-bmp,image/x-bitmap,image/x-xbitmap,image/x-win-bitmap,image/x-windows-bmp,image/ms-bmp,image/x-ms-bmp,application/bmp,application/x-bmp,application/x-win-bitmap',
			'csv' => 'text/csv,text/comma-separated-values',
			'doc' => 'application/msword',
			'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'eps' => 'application/postscript,application/eps,application/x-eps,image/eps,image/x-eps',
			'flv' => 'video/x-flv',
			'gif' => 'image/gif',
			'jpeg' => 'image/jpeg,image/pjpeg',
			'jpg' => 'image/jpeg,image/pjpeg',
			'mkv' => 'video/x-matroska',
			'mov' => 'video/quicktime,video/x-quicktime,image/mov,audio/aiff,audio/x-midi,audio/x-wav,video/avi',
			'mp3' => 'audio/mpeg,audio/x-mpeg,audio/mp3,audio/x-mp3,audio/mpeg3,audio/x-mpeg3,audio/mpg,audio/x-mpg,audio/x-mpegaudio',
			'mp4' => 'video/mp4v-es,audio/mp4',
			'mpeg' => 'video/mpeg',
			'mpg' => 'video/mpeg,video/mpg,video/x-mpg,video/mpeg2,application/x-pn-mpg,video/x-mpeg,video/x-mpeg2a,audio/mpeg,audio/x-mpeg,image/mpg',
			'ogg' => 'audio/ogg,application/ogg,audio/x-ogg,application/x-ogg',
			'pdf' => 'application/pdf,application/x-pdf',
			'png' => 'image/png',
			'psd' => 'image/photoshop,image/x-photoshop,image/psd,application/photoshop,application/psd,zz-application/zz-winassoc-psd,application/octet-stream',
			'rar' => 'application/x-rar-compressed,application/octet-stream',
			'swf' => 'application/x-shockwave-flash,application/x-shockwave-flash2-preview,application/futuresplash,image/vnd.rn-realflash',
			'txt' => 'text/plain',
			'wav' => 'audio/wav,audio/x-wav,audio/wave,audio/x-pn-wav',
			'wma' => 'audio/x-ms-wma,video/x-ms-asf',
			'wmv' => 'video/x-ms-wmv',
			'xls' => 'application/excel,application/vnd.ms-excel,application/x-excel,application/x-msexcel',
			'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'zip' => 'application/x-compress,application/x-compressed,application/x-zip-compressed,application/zip,application/x-zip,multipart/x-zip,application/octet-stream',
		);
		$query = $this->db->query("SELECT ext FROM `" . DB_PREFIX . "myoc_filetype`");
		foreach ($query->rows as $row) {
			unset($file_ext[$row['ext']]);
		}
		foreach ($file_ext as $ext => $mime) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "myoc_filetype` (`ext`, `mime`) VALUES ('" . $ext . "', '" . $mime . "');");
		}
	}

	public function uninstallTable()
	{
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "myoc_customer_upload`, `" . DB_PREFIX . "myoc_order_upload`, `" . DB_PREFIX . "myoc_upload`, `" . DB_PREFIX . "myoc_filetype`;");
	}

	public function upgradeTable()
	{
		//from v1.3
		$query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "order_product_upload'");
		if($query->num_rows)
		{
			$this->db->query("INSERT INTO `" . DB_PREFIX . "order_option` (order_id, order_product_id, product_option_id, name, value, type) SELECT op.order_id,opu.order_product_id,0,'File',u.filename,'file' FROM `" . DB_PREFIX . "order_product` op LEFT JOIN `" . DB_PREFIX . "order_product_upload` opu ON (op.order_product_id = opu.order_product_id) LEFT JOIN `" . DB_PREFIX . "upload` u ON (opu.upload_id = u.upload_id) WHERE op.order_product_id = opu.order_product_id");
			$this->db->query("RENAME TABLE `" . DB_PREFIX . "customer_upload` TO  `" . DB_PREFIX . "myoc_customer_upload` ;");
			$this->db->query("RENAME TABLE `" . DB_PREFIX . "order_upload` TO  `" . DB_PREFIX . "myoc_order_upload` ;");
			$this->db->query("RENAME TABLE `" . DB_PREFIX . "upload` TO  `" . DB_PREFIX . "myoc_upload` ;");
			$this->db->query("RENAME TABLE `" . DB_PREFIX . "filetype` TO  `" . DB_PREFIX . "myoc_filetype` ;");
			$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "order_product_upload`");

			$this->load->model('setting/setting');
			$data = array(
				'upload_customer_message' => serialize(array(
					$this->config->get('config_language_id') => array(
						'message' => $this->config->get('upload_customer_message')),
				)),
				'upload_order_message' => serialize(array(
					$this->config->get('config_language_id') => array(
						'message' => $this->config->get('upload_order_message')),
				)),
				'upload_product_message' => serialize(array(
					$this->config->get('config_language_id') => array(
						'message' => $this->config->get('upload_product_message')),
				)),
			);
			$this->model_setting_setting->editSetting('copu', $data);
			$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `group` = 'copu' WHERE `group` = 'upload'");
		}
	}
}
?>