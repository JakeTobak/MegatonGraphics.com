<?php
class ControllerMyocCopu extends Controller {
	protected function index($args) {
		$type = isset($args['type']) ? $args['type'] : false;
		$type_id = isset($this->request->get[$type . '_id']) ? $this->request->get[$type . '_id'] : 0;
		if(!$type || !$type_id) {
			return false;
		}
		$this->language->load('module/myoccopu');

		$this->data['column_image'] = $this->language->get('column_image');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_size'] = $this->language->get('column_size');
		$this->data['column_date'] = $this->language->get('column_date');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['text_popup'] = $this->language->get('text_popup');
		$this->data['text_download'] = $this->language->get('text_download');
		$this->data['text_empty'] = $this->language->get('text_empty');
		$this->data['text_delete'] = $this->language->get('text_delete');
		$this->data['text_confirm_delete'] = $this->language->get('text_confirm_delete');

		$this->data['button_upload'] = $this->language->get('button_upload');

		$this->data['token'] = $this->session->data['token'];

		if(file_exists('../catalog/view/javascript/jquery/colorbox/colorbox.css') && file_exists('../catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js')) {
			$this->document->addStyle(HTTP_CATALOG . 'catalog/view/javascript/jquery/colorbox/colorbox.css');
	        $this->document->addScript(HTTP_CATALOG . 'catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
        } elseif(file_exists('../catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.css') && file_exists('../catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.pack.js')) { //OCv1.5.1.3
			$this->document->addStyle(HTTP_CATALOG . 'catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.css');
	        $this->document->addScript(HTTP_CATALOG . 'catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.pack.js');
        }
        if($type == 'customer') {
        	$this->document->addScript('view/javascript/jquery/ajaxupload.js');
        }

		$this->load->model('myoc/copu');

		$this->load->model('tool/image');

		$this->load->helper('copu');

		$this->data['type'] = $type;

		$this->data['upload'] = isset($args['edit']) ? $args['edit'] : true;
		$this->data['delete'] = isset($args['edit']) ? $args['edit'] : true;

		$this->data['colspan'] = 5;
		if(!$this->data['delete']) {
			$this->data['colspan']--;
		}

		$this->data['uploads'] = array();

		$uploads = $this->model_myoc_copu->getUploads(array($type . '_id' => $type_id));

		if($uploads) {
			foreach ($uploads as $upload) {
				if ($upload['filename'] && file_exists(DIR_DOWNLOAD . $upload['filename'])) {
					$size = filesize(DIR_DOWNLOAD . $upload['filename']);
					if(!$size) { continue; }
					$imageinfo = @getimagesize(DIR_DOWNLOAD . $upload['filename']);
                	if($imageinfo[2] > 0 && $imageinfo[2] < 4) {
                		if(!file_exists(DIR_IMAGE . $upload['mask'])) {
	                    	copy(DIR_DOWNLOAD . $upload['filename'], DIR_IMAGE . $upload['mask']);
	                    }
	                    $image = $this->model_tool_image->resize($upload['mask'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'));
	                    $popup = $this->model_tool_image->resize($upload['mask'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
	                    //unlink(DIR_IMAGE . $upload['mask']); //comment out to improve performance but decrease security
	                } else {
	        			$image = $this->model_tool_image->resize('no_image.jpg', $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'));
	                    $popup = false;
	        		}

					$this->data['uploads'][] = array(
						'upload_id'	=> $upload['upload_id'],
						'image' 	=> $image,
						'popup' 	=> $popup,
						'date' 		=> date($this->language->get('date_format_short'), strtotime($upload['date_added'])),
						'name'      => $upload['mask'],
						'size'      => formatFilesize($size),
						'href'      => $this->url->link('myoc/copu/download', 'token=' . $this->session->data['token'] . '&f=' . urlencode($upload['filename']), 'SSL'),
						'delete'	=> $this->url->link('myoc/copu/delete', 'token=' . $this->session->data['token'] . '&upload_id=' . $upload['upload_id'] . '&confirm=0', 'SSL'),
					);
				}
			}
		}

		$this->template = 'myoc/copu_upload.tpl';

		$this->response->setOutput($this->render());
	}

	protected function product($args) {
		$order_id = isset($this->request->get['order_id']) ? $this->request->get['order_id'] : 0;
		if(!$order_id) {
			return false;
		}
		$this->language->load('module/myoccopu');

		$this->data['column_image'] = $this->language->get('column_image');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_size'] = $this->language->get('column_size');
		$this->data['column_date'] = $this->language->get('column_date');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['text_popup'] = $this->language->get('text_popup');
		$this->data['text_download'] = $this->language->get('text_download');
		$this->data['text_empty'] = $this->language->get('text_empty');
		$this->data['text_delete'] = $this->language->get('text_delete');
		$this->data['text_confirm_delete'] = $this->language->get('text_confirm_delete');

		$this->data['button_upload'] = $this->language->get('button_upload');

		$this->data['token'] = $this->session->data['token'];

		$this->document->addStyle(HTTP_CATALOG . 'catalog/view/javascript/jquery/colorbox/colorbox.css');
        $this->document->addScript(HTTP_CATALOG . 'catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');

        $this->data['html'] = isset($args['html']) ? $args['html'] : false;
        $this->data['javascript'] = isset($args['js']) ? $args['js'] : false;

        $this->data['force_qty'] = $this->config->get('upload_product_force_qty');

		$this->template = 'myoc/copu_product.tpl';

		$this->response->setOutput($this->render());
	}

	protected function invoice($args) {
		if(!$args['order_id']) {
			return false;
		}
		$this->language->load('module/myoccopu');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_size'] = $this->language->get('column_size');

		$this->data['text_empty'] = $this->language->get('text_empty');
		$this->data['text_upload'] = $this->language->get('text_upload');

		$this->load->model('myoc/copu');

		$this->load->helper('copu');

		$this->data['uploads'] = array();

		$uploads = $this->model_myoc_copu->getUploads(array('order_id' => $args['order_id']));

		if($uploads) {
			foreach ($uploads as $upload) {
				if (file_exists(DIR_DOWNLOAD . $upload['filename'])) {
					$size = filesize(DIR_DOWNLOAD . $upload['filename']);

					$this->data['uploads'][] = array(
						'name'      => $upload['mask'],
						'size'      => formatFilesize($size),
					);
				}
			}
		}

		$this->template = 'myoc/copu_invoice.tpl';

		$this->response->setOutput($this->render());
	}

	public function upload() {
		$this->language->load('module/myoccopu');

		$json = array();

		if (!$this->user->hasPermission('modify', 'module/myoccopu')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('myoc/copu');

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
			
			//check other system upload error
			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}

    	$filename_length = 40;
    	if(isset($this->request->get['filename_length'])) {
    		$filename_length = $this->request->get['filename_length'];
    	}
		
		if (!$json) {
			if (is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name']) && $this->request->files['file']['size']) {
				$upload_id = md5(mt_rand());
				$file = basename($filename) . '.' . $upload_id;
				
				move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $file);
				
				if(method_exists($this->encryption, 'encrypt')) {
					$encryption = $this->encryption;
				} else {
					$this->load->library('encryption');
				 	$encryption = new Encryption($this->config->get('config_encryption'));
				}
				$upload_id = $this->model_myoc_copu->addUpload(array('filename' => $file));

				$this->load->model('tool/image');

            	$imageinfo = @getimagesize(DIR_DOWNLOAD . $file);
            	if($imageinfo[2] > 0 && $imageinfo[2] < 4) {
                	copy(DIR_DOWNLOAD . $file, DIR_IMAGE . $filename);
                    $image = $this->model_tool_image->resize($filename, $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'));
                    $popup = $this->model_tool_image->resize($filename, $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
	                unlink(DIR_IMAGE . $filename);
                } else {
        			$image = $this->model_tool_image->resize('no_image.jpg', $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'));
        			$popup = false;
        		}

        		$json['file'] = array();
        		$json['file']['upload_id'] = $upload_id;
        		$json['file']['file'] = $file;
        		$json['file']['image'] = $image;
        		$json['file']['popup'] = $popup;
        		$json['file']['name'] = truncateFilename($filename, $filename_length);
        		$json['file']['href'] = $this->url->link('myoc/copu/download', 'token=' . $this->session->data['token'] . '&f=' . urlencode($file), 'SSL');
        		$json['file']['date'] = date($this->language->get('date_format_short'));
        		$json['file']['size'] = formatFilesize($this->request->files['file']['size']);

        		$json['file']['delete'] = $this->url->link('myoc/copu/delete', 'token=' . $this->session->data['token'] . '&upload_id=' . $upload_id, 'SSL');
			}

			$json['success'] = true;
		}	
		
		$this->response->setOutput(json_encode($json));		
	}

	public function download()
	{
		$filename = isset($this->request->get['f']) ? $this->request->get['f'] : false;
		$mask = function_exists('utf8_substr') ? utf8_substr($filename, 0, utf8_strrpos($filename, '.')) : substr($filename, 0, strrpos($filename, '.'));
		if (file_exists(DIR_DOWNLOAD . $filename)) {
			if (!headers_sent()) {
				header('Content-Type: application/octet-stream');
				header('Content-Description: File Transfer');
				header('Content-Disposition: attachment; filename="' . $mask . '"');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				header('Content-Length: ' . filesize(DIR_DOWNLOAD . $filename));
				
				readfile(DIR_DOWNLOAD . $filename, 'rb');

				exit;
			} else {
				exit('Error: Headers already sent out!');
			}
		} else {
			exit('Error: Could not find file ' . DIR_DOWNLOAD . $filename . '!');
		}
	}

	public function delete() {
        $upload_id = $this->request->get['upload_id'];
        $confirm = isset($this->request->get['confirm']) ? $this->request->get['confirm'] : true;

        $this->language->load('module/myoccopu');

	    $json = array();

	    $json['error'] = $this->language->get('error_delete');

        if (!$this->user->hasPermission('modify', 'module/myoccopu')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
		    if($confirm) {
	    		$this->load->model('myoc/copu');
		    	$upload = $this->model_myoc_copu->getUpload($upload_id);
		        if($upload && unlink(DIR_DOWNLOAD . $upload['filename']))
		        {
		            $this->model_myoc_copu->deleteUpload($upload_id);
		            $json['success'] = true;
		            unset($json['error']);
		        }
		    } else {
		    	$json['success'] = true;
		        unset($json['error']);
		    }
		}
        $this->response->setOutput(json_encode($json));
	}
}
?>