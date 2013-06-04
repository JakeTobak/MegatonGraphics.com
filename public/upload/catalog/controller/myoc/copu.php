<?php  
class ControllerMyocCopu extends Controller {
	protected function index($args) {
		$type = isset($args['type']) ? $args['type'] : false;
		$path = isset($args['path']) ? $args['path'] : false;
		if(!$type || !$this->config->get('upload_' . $type . '_status') || ($type == 'customer' && $path == 'register' && !$this->config->get('upload_' . $type . '_register')) || $this->config->get('upload_' . $type . '_store') == "" || !in_array($this->config->get('config_store_id'), explode(',', $this->config->get('upload_' . $type . '_store')))) {
			return false;
		}
		$this->language->load('myoc/copu');

		if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/copu.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/copu.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/copu.css');
		}
		$this->document->addScript('catalog/view/javascript/jquery/ajaxupload.js');

		$this->data['text_upload'] = $this->language->get('text_upload');
		$this->data['text_empty'] = $this->language->get('text_empty');
		$this->data['text_confirm_delete'] = $this->language->get('text_confirm_delete');
		$this->data['text_download'] = $this->language->get('text_download');
		$this->data['text_popup'] = $this->language->get('text_popup');

		$this->data['column_image'] = $this->language->get('column_image');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_date'] = $this->language->get('column_date');
		$this->data['column_size'] = $this->language->get('column_size');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_upload'] = $this->language->get('button_upload');
		$this->data['button_remove'] = $this->language->get('button_remove');

		if ($type == 'order' && $path && $this->config->get('upload_order_minimum') && (!isset($this->session->data['order_upload']) || count($this->session->data['order_upload']) < $this->config->get('upload_order_minimum'))) {
			$this->data['error_upload_minimum'] = sprintf($this->language->get('error_upload_minimum'), $this->config->get('upload_order_minimum'));
			if($path == 'checkout') {
				$this->redirect($this->url->link('checkout/cart'));
			}
		} else {
			$this->data['error_upload_minimum'] = '';
		}

		$product_id = isset($this->request->get['product_id']) ? (int)$this->request->get['product_id'] : false;
		$product_option_id = isset($args['product_option_id']) ? $args['product_option_id'] : false;
		$this->data['product_id'] = $product_id;
		$this->data['product_option_id'] = $product_option_id;

		$this->data['force_qty'] = $this->config->get('upload_product_force_qty');

		$this->data['column_image_width'] = $this->config->get('config_image_additional_width');

		$this->data['date'] = false;
		$this->data['nosession'] = '';

		$colspan = 4;
		$this->data['upload_display'] = $this->config->get('upload_' . $type . '_display');
		if(!$this->data['upload_display']) {
			$colspan--;
		}
		if($path == 'checkout' || $type == 'product' || ($type == 'order' && !$path)) {
			$colspan--;
		}
		if($type == 'customer' && !$path) {
			$this->data['date'] = true;
			$this->data['nosession'] = '&session=0';
			$colspan++;
		}
		$this->data['colspan'] = $colspan;

		$this->data['type'] = $type;

		$this->data['action'] = ($path == 'checkout' || ($type == 'order' && !$path)) ? false : true;

		$upload_message = @unserialize($this->config->get('upload_' . $type . '_message'));
		$this->data['upload_message'] = $this->data['action'] ? html_entity_decode($upload_message[$this->config->get('config_language_id')]['message'], ENT_QUOTES, 'UTF-8') : false;

    	$filename_length = isset($args['filename_length']) ? $args['filename_length'] : 40;

		$this->load->helper('copu');
		$this->load->model('tool/image');
		$this->load->model('myoc/copu');

		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;			

		if($type == 'product') {
			$uploads = isset($this->session->data['product_upload'][$product_id][$product_option_id]) ? $this->session->data['product_upload'][$product_id][$product_option_id] : false;
		} elseif($type == 'customer' && !$path) {
			$uploads = $this->model_myoc_copu->getUploads(array('customer_id' => $this->customer->isLogged(), 'start' => ($page - 1) * $this->config->get('config_catalog_limit'), 'limit' => $this->config->get('config_catalog_limit')));
		} elseif($type == 'order' && !$path) {
			$uploads = $this->model_myoc_copu->getUploads(array('order_id' => $this->request->get['order_id']));
		} else {
			$uploads = isset($this->session->data[$type . '_upload']) ? $this->session->data[$type . '_upload'] : false;
		}
		$this->data['uploads'] = array();

		if($uploads) {
			if(method_exists($this->encryption, 'encrypt')) {
				$encryption = $this->encryption;
			} else {
				$this->load->library('encryption');
			 	$encryption = new Encryption($this->config->get('config_encryption'));
			}
			foreach ($uploads as $upload_id => $upload) {
				$file = is_array($upload) ? $upload['filename'] : $encryption->decrypt($upload);
				$filename = function_exists('utf8_substr') ? utf8_substr($file, 0, utf8_strrpos($file, '.')) : substr($file, 0, strrpos($file, '.'));
				if (file_exists(DIR_DOWNLOAD . $file)) {
					$size = filesize(DIR_DOWNLOAD . $file);
					$image = false;
	        		$popup = false;
                    $replace = false;
					if(($this->config->get('upload_' . $type . '_display') || $this->config->get('upload_product_replace')) && $file && filesize(DIR_DOWNLOAD . $file)) {
						$imageinfo = @getimagesize(DIR_DOWNLOAD . $file);
                		if($imageinfo[2] > 0 && $imageinfo[2] < 4) {
		                    if(!file_exists(DIR_IMAGE . $filename)) {
		                    	copy(DIR_DOWNLOAD . $file, DIR_IMAGE . $filename);
		                    }
		                    $image = $this->config->get('upload_' . $type . '_display') ? $this->model_tool_image->resize($filename, $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height')) : false;
		                    $popup = ($this->config->get('upload_' . $type . '_display') || $this->config->get('upload_product_replace')) ? $this->model_tool_image->resize($filename, $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')) : false;
		                    $replace = ($type == 'product' && $this->config->get('upload_product_replace'))  ? $this->model_tool_image->resize($filename, $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')) : false;
		                    unlink(DIR_IMAGE . $filename); //comment out to improve performance but decrease security
		                } else {
		        			$image = $this->config->get('upload_' . $type . '_display') ? $this->model_tool_image->resize('no_image.jpg', $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height')) : false;
		        		}
		        	}

					$this->data['uploads'][] = array(
						'upload_id'	=> $upload_id,
						'image' 	=> $image,
						'popup' 	=> $popup,
						'replace'   => $replace,
						'date' 		=> isset($upload['date_added']) ? date($this->language->get('date_format_short'), strtotime($upload['date_added'])) : false,
						'name'      => truncateFilename($filename, $filename_length),
						'size'      => formatFilesize($size),
						'href'      => $this->url->link('myoc/copu/download', 'f=' . urlencode(is_array($upload) ? $encryption->encrypt($upload['filename']) : $upload), 'SSL'),
						'delete'	=> $this->url->link('myoc/copu/delete', 'upload_id=' . $upload_id, 'SSL'),
					);
				}
			}
		}

		$tpl = 'copu.tpl';
		if($type == 'product') {
			$tpl = 'copu_product.tpl';
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/myoc/' . $tpl)) {
			$this->template = $this->config->get('config_template') . '/template/myoc/' . $tpl;
		} else {
			$this->template = 'default/template/myoc/' . $tpl;
		}
								
		$this->render();
	}

	protected function cart($args) {
		if(!$this->config->get('upload_product_status') || $this->config->get('upload_product_store') == "" || !in_array($this->config->get('config_store_id'), explode(',', $this->config->get('upload_product_store')))) {
			return false;
		}
		$this->language->load('myoc/copu');
		$this->load->model('tool/image');
		$this->load->helper('copu');

		$this->data['text_download'] = $this->language->get('text_download');

		$this->data['uploads'] = array();

  		$this->data['path'] = 'cart';
		if(isset($this->request->get['route'])) {
			if(trim($this->request->get['route']) == 'checkout/confirm' || trim($this->request->get['route']) == 'account/order/info') {
				$this->data['path'] = 'confirm';
	  		}
		}

		if(method_exists($this->encryption, 'encrypt')) {
			$encryption = $this->encryption;
		} else {
			$this->load->library('encryption');
		 	$encryption = new Encryption($this->config->get('config_encryption'));
		}

		if(isset($args['key'])) {
			$products = $this->cart->getProducts();

			foreach ($products as $key => $product) {
	  			if($key == $args['key']) {
		  			foreach ($product['option'] as $option) {
		  				if($option['type'] == 'file' && $option['option_value']) {
		  					$file = $encryption->decrypt($option['option_value']);
	         				$filename = function_exists('utf8_substr') ? utf8_substr($file, 0, utf8_strrpos($file, '.')) : substr($file, 0, strrpos($file, '.'));
	         				$size = file_exists(DIR_DOWNLOAD . $file) ? filesize(DIR_DOWNLOAD . $file) : 0;
		  					$this->data['uploads'][] = array(
		  						'option_name' => $option['name'],
		  						'size' => formatFilesize($size),
		  						'href' => $this->url->link('myoc/copu/download', 'f=' . urlencode($option['option_value']), 'SSL'),
		  						'filename' => truncateFilename($filename, 20),
		  					);
		  				}
		  			}
		  		}
	  		}
	  	} elseif(isset($args['order_id'])) {
	  		$this->load->model('account/order');
	  		$products = $this->model_account_order->getOrderProducts($args['order_id']);

			foreach ($products as $product) {
	  			if($product['order_product_id'] == $args['order_product_id']) {
	  				$options = $this->model_account_order->getOrderOptions($args['order_id'], $args['order_product_id']);
		  			foreach ($options as $option) {
		  				if($option['type'] == 'file' && $option['value']) {
		  					$size = file_exists(DIR_DOWNLOAD . $option['value']) ? filesize(DIR_DOWNLOAD . $option['value']) : 0;
	         				$filename = function_exists('utf8_substr') ? utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.')) : substr($option['value'], 0, strrpos($option['value'], '.'));
		  					$this->data['uploads'][] = array(
		  						'option_name' => $option['name'],
		  						'size' => formatFilesize($size),
		  						'href' => $this->url->link('myoc/copu/download', 'f=' . urlencode($encryption->encrypt($option['value'])), 'SSL'),
		  						'filename' => truncateFilename($filename, 20),
		  					);
		  				}
		  			}
		  		}
	  		}
	  	}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/myoc/copu_cart.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/myoc/copu_cart.tpl';
		} else {
			$this->template = 'default/template/myoc/copu_cart.tpl';
		}
								
		$this->render();
	}

	public function customer() {
		if(!$this->config->get('upload_customer_status') || $this->config->get('upload_customer_store') == "" || !in_array($this->config->get('config_store_id'), explode(',', $this->config->get('upload_customer_store')))) {
			$this->redirect($this->url->link('account/account', '', 'SSL'));
		}
		if(!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/myoccopu', '', 'SSL');
			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}
		$this->language->load('myoc/copu');

		$this->document->setTitle($this->language->get('heading_title'));
      	
		$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	); 

      	$this->data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);

      	$this->data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('account/myoccopu'),
        	'separator' => $this->language->get('text_separator')
      	);
								
		$this->data['heading_title'] = $this->language->get('heading_title');	

		$this->data['button_continue'] = $this->language->get('button_continue');

		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;
		$this->load->model('myoc/copu');
		$upload_total = $this->model_myoc_copu->getTotalUploads(array('customer_id' => $this->customer->isLogged()));

		$pagination = new Pagination();
		$pagination->total = $upload_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_catalog_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/myoccopu', 'page={page}', 'SSL');
		
		$this->data['pagination'] = $pagination->render();

		$this->data['continue'] = $this->url->link('account/account', '', 'SSL');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/myoc/copu_customer.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/myoc/copu_customer.tpl';
		} else {
			$this->template = 'default/template/myoc/copu_customer.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
		);

		$this->data['copu_customer'] = $this->getChild('myoc/copu', array('type' => 'customer'));
							
		$this->response->setOutput($this->render());
	}

	public function upload() {
		$this->language->load('myoc/copu');

		$this->load->model('myoc/copu');

		$type = $this->request->get['type'];

		$type_id = isset($this->request->get['type_id']) ? $this->request->get['type_id'] : false;
		$product_option_id = isset($this->request->get['product_option_id']) ? $this->request->get['product_option_id'] : false;
		$session = isset($this->request->get['session']) ? $this->request->get['session'] : true;

		$json = array();

		//create upload session
		if(!isset($this->session->data[$type . '_upload']) && $session) {
			$this->session->data[$type . '_upload'] = array();
		}
		if($type == 'product' && $type_id && !isset($this->session->data[$type . '_upload'][$type_id])) {
			$this->session->data[$type . '_upload'][$type_id] = array();
		}
		if($type == 'product' && $type_id && $product_option_id && !isset($this->session->data[$type . '_upload'][$type_id][$product_option_id])) {
			$this->session->data[$type . '_upload'][$type_id][$product_option_id] = array();
		}
		//check status and store
		if(!$this->config->get('upload_' . $type . '_status') || $this->config->get('upload_' . $type . '_store') == "" || !in_array($this->config->get('config_store_id'), explode(',', $this->config->get('upload_' . $type . '_store')))) {
			$json['error'] = $this->language->get('error_upload_status');
			$this->response->setOutput(json_encode($json));
			return;
		}

		//check login
		if($this->config->get('upload_' . $type . '_login') && ($session xor $type == 'customer') && (!$this->customer->isLogged() || !$this->config->get('upload_' . $type . '_customer_group') || !in_array($this->customer->getCustomerGroupId(), explode(',', $this->config->get('upload_' . $type . '_customer_group'))))) {
			$json['error'] = $this->language->get('error_login');
			$this->response->setOutput(json_encode($json));
			return;
		}

        //check file limit
        $upload_total = 0;
        if($type != 'product' && isset($this->session->data[$type . '_upload'])) {
        	$upload_total = count($this->session->data[$type . '_upload']);
        }
        if($type == 'product' && $this->session->data[$type . '_upload'][$type_id][$product_option_id]) {
			$upload_total = count($this->session->data[$type . '_upload'][$type_id][$product_option_id]);
		}
		if(!$session) {
			$upload_total = $this->model_myoc_copu->getTotalUploads(array('customer_id' => $this->customer->isLogged()));
		}
        if($upload_total >= $this->config->get('upload_' . $type . '_limit')) {
        	$json['error'] = $this->language->get('error_limit');
			$this->response->setOutput(json_encode($json));
			return;
        }

		$filetypes = $this->model_myoc_copu->getFiletypes(explode(',', $this->config->get('upload_' . $type . '_filetype')));

		$this->load->helper('copu');
		
		if (!empty($this->request->files['file']['name'])) {
			$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8')));
			
			if(function_exists('utf8_strlen')) {
				if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
	        		$json['error'] = $this->language->get('error_filename');
		  		}
		  	} else {
		  		if ((strlen($filename) < 3) || (strlen($filename) > 64)) {
	        		$json['error'] = $this->language->get('error_filename');
		  		}
		  	}
			
			$allowed_ext = array();
			$allowed_mime = array();
			
			foreach ($filetypes as $filetype) {
				$allowed_ext[] = trim($filetype['ext']);
				$allowed_mime[trim($filetype['ext'])] = ($filetype['mime'] == '') ? false : explode(",", $filetype['mime']);
			}

			$ext = strtolower(substr(strrchr($filename, '.'), 1));
			$mime = function_exists('mime_content_type') ? mime_content_type($this->request->files['file']['tmp_name']) : false;
			
			//check file ext and mime
			if (!in_array($ext, $allowed_ext) || ($mime && $allowed_mime[$ext] && !in_array($mime, $allowed_mime[$ext]))) {
				$json['error'] = sprintf($this->language->get('error_filetype'), implode(", ", $allowed_ext));
       		}

       		//check file size
       		if(filesize($this->request->files['file']['tmp_name']) > $this->config->get('upload_' . $type . '_max_filesize') * 1024) {
       			$json['error'] = sprintf($this->language->get('error_filesize'), formatFilesize($this->config->get('upload_' . $type . '_max_filesize') * 1024));
       		}
						
			//check other system upload error
			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}

    	$filename_length = isset($this->request->get['filename_length']) ? $this->request->get['filename_length'] : 40;
		
		if (!$json) {
			if (is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
				$upload_id = md5(mt_rand());
				$file = basename($filename) . '.' . $upload_id;
				
				move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $file);
				
				if(method_exists($this->encryption, 'encrypt')) {
					$encryption = $this->encryption;
				} else {
					$this->load->library('encryption');
				 	$encryption = new Encryption($this->config->get('config_encryption'));
				}
				if(!$session) {
					$upload_id = $this->model_myoc_copu->addUpload(array('filename' => $file, 'customer_id' => $this->customer->isLogged()));
				} elseif($type == 'product' && $type_id && $product_option_id) {
					$this->session->data[$type . '_upload'][$type_id][$product_option_id][$upload_id] = $encryption->encrypt($file);
				} else {
					$this->session->data[$type . '_upload'][$upload_id] = $encryption->encrypt($file);
				}
				$this->load->model('tool/image');

        		$image = false;
        		$popup = false;
                $replace = false;
                if(($this->config->get('upload_' . $type . '_display') || $this->config->get('upload_product_replace')) && $file && filesize(DIR_DOWNLOAD . $file)) {
                	$imageinfo = @getimagesize(DIR_DOWNLOAD . $file);
                	if($imageinfo[2] > 0 && $imageinfo[2] < 4) {
	                    copy(DIR_DOWNLOAD . $file, DIR_IMAGE . $filename);
	                    $image = $this->config->get('upload_' . $type . '_display') ? $this->model_tool_image->resize($filename, $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height')) : false;
	                    $popup = ($this->config->get('upload_' . $type . '_display') || $this->config->get('upload_product_replace')) ? $this->model_tool_image->resize($filename, $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')) : false;
	                    $replace = ($type == 'product' && $this->config->get('upload_product_replace')) ? $this->model_tool_image->resize($filename, $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')) : false;
		                unlink(DIR_IMAGE . $filename);
	                } else {
		        		$image = $this->config->get('upload_' . $type . '_display') ? $this->model_tool_image->resize('no_image.jpg', $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height')) : false;
		        	}
	        	}

        		$json['file'] = array();
        		$json['file']['upload_id'] = $upload_id;
        		$json['file']['image'] = $image;
        		$json['file']['popup'] = $popup;
        		$json['file']['replace'] = $replace;
        		$json['file']['name'] = truncateFilename($filename, $filename_length);
        		$json['file']['href'] = $this->url->link('myoc/copu/download', 'f=' . urlencode($encryption->encrypt($file)), 'SSL');
        		$json['file']['date'] = date($this->language->get('date_format_short'));
        		$json['file']['size'] = formatFilesize($this->request->files['file']['size']);

        		$json['file']['delete'] = $this->url->link('myoc/copu/delete', 'upload_id=' . $upload_id, 'SSL');
			}

			$json['success'] = true;
		}	
		
		$this->response->setOutput(json_encode($json));		
	}

	public function download() {
		if(method_exists($this->encryption, 'encrypt')) {
			$encryption = $this->encryption;
		} else {
			$this->load->library('encryption');
		 	$encryption = new Encryption($this->config->get('config_encryption'));
		}
		$filename = $encryption->decrypt($this->request->get['f']);
		$mask = function_exists('utf8_substr') ? utf8_substr($filename, 0, utf8_strrpos($filename, '.')) : substr($filename, 0, strrpos($filename, '.'));
		$filename = DIR_DOWNLOAD . $filename;
		if (file_exists($filename)) {
			if (!headers_sent()) {
				header('Content-Type: application/octet-stream');
				header('Content-Description: File Transfer');
				header('Content-Disposition: attachment; filename="' . $mask . '"');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				header('Content-Length: ' . filesize($filename));
				
				readfile($filename, 'rb');

				exit;
			} else {
				exit('Error: Headers already sent out!');
			}
		} else {
			exit('Error: Could not find file ' . $filename . '!');
		}
	}

	public function delete() {
        $upload_id = $this->request->get['upload_id'];
        
        $this->language->load('myoc/copu');

	    $json = array();
	    $json['error'] = $this->language->get('error_delete');

		if(method_exists($this->encryption, 'encrypt')) {
			$encryption = $this->encryption;
		} else {
			$this->load->library('encryption');
		 	$encryption = new Encryption($this->config->get('config_encryption'));
		}
		if(isset($this->session->data['customer_upload'][$upload_id]) && unlink(DIR_DOWNLOAD . $encryption->decrypt($this->session->data['customer_upload'][$upload_id]))) {
			unset($this->session->data['customer_upload'][$upload_id]);
            $json['success'] = true;
            unset($json['error']);
		} elseif(isset($this->session->data['order_upload'][$upload_id]) && unlink(DIR_DOWNLOAD . $encryption->decrypt($this->session->data['order_upload'][$upload_id]))) {
			unset($this->session->data['order_upload'][$upload_id]);
            $json['success'] = true;
            unset($json['error']);
		} elseif(isset($this->session->data['product_upload']) && !empty($this->session->data['product_upload'])) {
			foreach ($this->session->data['product_upload'] as $product_id => $product_uploads) {
				foreach ($product_uploads as $product_option_id => $product_upload) {
					if(isset($this->session->data['product_upload'][$product_id][$product_option_id][$upload_id]) && unlink(DIR_DOWNLOAD . $encryption->decrypt($this->session->data['product_upload'][$product_id][$product_option_id][$upload_id]))) {
						unset($this->session->data['product_upload'][$product_id][$product_option_id][$upload_id]);
			            $json['success'] = true;
			            unset($json['error']);
			            break 2;
					}
				}
			}
		}
		if(isset($json['error'])) {
			$this->load->model('myoc/copu');
	    	$upload = $this->model_myoc_copu->getUpload($upload_id);
	        if($upload && $upload['customer_id'] == $this->customer->isLogged())
	        {
		        if(unlink(DIR_DOWNLOAD . $upload['filename']))
		        {
		            $this->model_myoc_copu->deleteUpload($upload_id);
		            $json['success'] = true;
		            unset($json['error']);
		        }
		    }
		}

        $this->response->setOutput(json_encode($json));
	}
}
?>