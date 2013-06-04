<?php
class ModelMyocCopu extends Model {
	public function getTotalUploads($data) {
		if(isset($data['customer_id'])) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "myoc_customer_upload cu LEFT JOIN `" . DB_PREFIX . "myoc_upload` u ON (cu.upload_id = u.upload_id) WHERE cu.customer_id = '" . (int)$data['customer_id'] . "'");
			return $query->row['total'];
		}
		return false;
	}

	public function getUploads($data) {
		if(isset($data['customer_id'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
			
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
			
			$query = $this->db->query("SELECT u.*,cu.* FROM " . DB_PREFIX . "myoc_customer_upload cu LEFT JOIN `" . DB_PREFIX . "myoc_upload` u ON (cu.upload_id = u.upload_id) WHERE cu.customer_id = '" . (int)$data['customer_id'] . "' ORDER BY u.date_added DESC LIMIT " . (int)$data['start'] . "," . (int)$data['limit']);
		}
		if(isset($data['order_id'])) {			
			$query = $this->db->query("SELECT u.*,ou.* FROM " . DB_PREFIX . "myoc_order_upload ou LEFT JOIN `" . DB_PREFIX . "myoc_upload` u ON (ou.upload_id = u.upload_id) WHERE ou.order_id = '" . (int)$data['order_id'] . "' ORDER BY u.date_added DESC");
		}
		$uploads = array();

		foreach($query->rows as $row) {
			$uploads[$row['upload_id']] = array(
				'upload_id' => $row['upload_id'],
				'filename' => $row['filename'],
				'mask' => $row['mask'],
				'date_added' => $row['date_added'],
				'customer_id' => isset($row['customer_id']) ? $row['customer_id'] : 0,
				'order_id' => isset($row['order_id']) ? $row['order_id'] : 0,
			);
		}
		return $uploads;
	}

	public function getFiletypes($filetype_ids = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "myoc_filetype WHERE ";
		foreach ($filetype_ids as $filetype_id) {
			$sql .= "`filetype_id` = '" . (int)$filetype_id . "' OR ";
		}
		$sql .= "'0';";
		$query = $this->db->query($sql);
		
		return $query->rows;
	}

	public function addUpload($data) {
		$mask = function_exists('utf8_substr') ? basename(utf8_substr($data['filename'], 0, utf8_strrpos($data['filename'], '.'))) : basename(substr($data['filename'], 0, strrpos($data['filename'], '.')));
		$this->db->query("INSERT INTO " . DB_PREFIX . "myoc_upload SET filename = '" . $this->db->escape($data['filename']) . "', mask = '" . $this->db->escape($mask) . "', date_added = NOW();");
		$upload_id = $this->db->getLastId();

		if(isset($data['customer_id'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "myoc_customer_upload SET customer_id = '" . (int)$data['customer_id'] . "', upload_id = '" . (int)$upload_id . "';");
		}
		if(isset($data['order_id'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "myoc_order_upload SET order_id = '" . (int)$data['order_id'] . "', upload_id = '" . (int)$upload_id . "';");
		}

		return $upload_id;
	}

	public function getUpload($upload_id) {
		$query = $this->db->query("SELECT u.*,cu.* FROM " . DB_PREFIX . "myoc_upload u LEFT JOIN `" . DB_PREFIX . "myoc_customer_upload` cu ON (cu.upload_id = u.upload_id) WHERE u.upload_id = '" . (int)$upload_id . "'");

		return $query->row;
	}

	public function deleteUpload($upload_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "myoc_upload WHERE upload_id = '" . (int)$upload_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "myoc_customer_upload WHERE upload_id = '" . (int)$upload_id . "'");
	}

	public function getOrderUploadInvoice($data) {
		if(!$this->config->get('upload_order_status') || $this->config->get('upload_order_store') == "" || !in_array($this->config->get('config_store_id'), explode(',', $this->config->get('upload_order_store')))) {
			return false;
		}
		$language = new Language($data['language_directory']);
		$this->language->load('myoc/copu');

		$template = new Template();

		$template->data['column_name'] = $this->language->get('column_name');
		$template->data['column_size'] = $this->language->get('column_size');

		$template->data['text_empty'] = $this->language->get('text_empty');
		$template->data['text_upload'] = $this->language->get('text_upload');

		$this->load->helper('copu');

		$template->data['format'] = $data['format'];

		$template->data['uploads'] = array();

		$uploads = $this->getUploads(array('order_id' => $data['order_id']));

		if($uploads) {
			foreach ($uploads as $upload) {
				if (file_exists(DIR_DOWNLOAD . $upload['filename'])) {
					$size = filesize(DIR_DOWNLOAD . $upload['filename']);

					$template->data['uploads'][] = array(
						'name'      => $upload['mask'],
						'size'      => formatFilesize($size),
					);
				}
			}
		}
			
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/myoc/copu_mail.tpl')) {
			return $template->fetch($this->config->get('config_template') . '/template/myoc/copu_mail.tpl');
		} else {
			return $template->fetch('default/template/myoc/copu_mail.tpl');
		}
	}

	public function getNewCustomerId() {
		$query = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "customer ORDER BY customer_id DESC LIMIT 0,1");

		return $query->row['customer_id'];
	}
}
?>