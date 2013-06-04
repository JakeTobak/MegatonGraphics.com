<?php
class ControllerModuleMyoccopu extends Controller {
	private $error = array();

	public function index()
	{
		$this->language->load('module/myoccopu');

		$this->document->setTitle($this->language->get('common_title'));
	
		$this->load->model('myoc/copu');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            foreach($this->request->post as $key => $value) {
                $this->request->post['upload_' . $key] = $value;
                unset($this->request->post[$key]);
            }

			$this->request->post['upload_product_option'] = isset($this->request->post['upload_product_option']) ? implode(',', $this->request->post['upload_product_option']) : '';

            $this->request->post['upload_customer_filetype'] = isset($this->request->post['upload_customer_filetype']) ? implode(',', $this->request->post['upload_customer_filetype']) : '';
            $this->request->post['upload_order_filetype'] = isset($this->request->post['upload_order_filetype']) ? implode(',', $this->request->post['upload_order_filetype']) : '';
            $this->request->post['upload_product_filetype'] = isset($this->request->post['upload_product_filetype']) ? implode(',', $this->request->post['upload_product_filetype']) : '';

            $this->request->post['upload_customer_customer_group'] = isset($this->request->post['upload_customer_customer_group']) ? implode(',', $this->request->post['upload_customer_customer_group']) : '';
			$this->request->post['upload_order_customer_group'] = isset($this->request->post['upload_order_customer_group']) ? implode(',', $this->request->post['upload_order_customer_group']) : '';
			$this->request->post['upload_product_customer_group'] = isset($this->request->post['upload_product_customer_group']) ? implode(',', $this->request->post['upload_product_customer_group']) : '';
			
			$this->request->post['upload_customer_store'] = isset($this->request->post['upload_customer_store']) ? implode(',', $this->request->post['upload_customer_store']) : '';
			$this->request->post['upload_order_store'] = isset($this->request->post['upload_order_store']) ? implode(',', $this->request->post['upload_order_store']) : '';
			$this->request->post['upload_product_store'] = isset($this->request->post['upload_product_store']) ? implode(',', $this->request->post['upload_product_store']) : '';

			$this->request->post['upload_customer_message'] = serialize($this->request->post['upload_customer_message']);
			$this->request->post['upload_order_message'] = serialize($this->request->post['upload_order_message']);
			$this->request->post['upload_product_message'] = serialize($this->request->post['upload_product_message']);

            $this->model_setting_setting->editSetting('copu', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('module/myoccopu', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('common_title'),
			'href'      => $this->url->link('module/myoccopu', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['common_title'] = $this->language->get('common_title');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_filetype'] = $this->language->get('button_add_filetype');

		$this->data['tab_customer'] = $this->language->get('tab_customer');
		$this->data['tab_order'] = $this->language->get('tab_order');
		$this->data['tab_product'] = $this->language->get('tab_product');
		$this->data['tab_filetype'] = $this->language->get('tab_filetype');

		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_limit'] = $this->language->get('entry_limit');
		$this->data['entry_minimum'] = $this->language->get('entry_minimum');
		$this->data['entry_register'] = $this->language->get('entry_register');
		$this->data['entry_max_filesize'] = $this->language->get('entry_max_filesize');
		$this->data['entry_message'] = $this->language->get('entry_message');
		$this->data['entry_comment'] = $this->language->get('entry_comment');
		$this->data['entry_display'] = $this->language->get('entry_display');
		$this->data['entry_filetype'] = $this->language->get('entry_filetype');
		$this->data['entry_replace'] = $this->language->get('entry_replace');
		$this->data['entry_login'] = $this->language->get('entry_login');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_ext'] = $this->language->get('entry_ext');
		$this->data['entry_mime'] = $this->language->get('entry_mime');
		$this->data['entry_upload_option'] = $this->language->get('entry_upload_option');
		$this->data['entry_force_qty'] = $this->language->get('entry_force_qty');

		$this->data['column_ext'] = $this->language->get('column_ext');
		$this->data['column_mime'] = $this->language->get('column_mime');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_add_filetype'] = $this->language->get('text_add_filetype');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_delete'] = $this->language->get('text_delete');
		$this->data['text_confirm_delete'] = $this->language->get('text_confirm_delete');
		
		$this->data['action'] = $this->url->link('module/myoccopu', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['add_filetype'] = $this->url->link('module/myoccopu/filetype', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['token'] = $this->session->data['token'];
		
		$this->data['myoc_copyright'] = $this->language->get('myoc_copyright');
 
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		if (isset($this->error['customer_limit'])) {
			$this->data['error_customer_limit'] = $this->error['customer_limit'];
		} else {
			$this->data['error_customer_limit'] = array();
		}
		if (isset($this->error['order_limit'])) {
			$this->data['error_order_limit'] = $this->error['order_limit'];
		} else {
			$this->data['error_order_limit'] = array();
		}
		if (isset($this->error['product_limit'])) {
			$this->data['error_product_limit'] = $this->error['product_limit'];
		} else {
			$this->data['error_product_limit'] = array();
		}

		if (isset($this->error['customer_minimum'])) {
			$this->data['error_customer_minimum'] = $this->error['customer_minimum'];
		} else {
			$this->data['error_customer_minimum'] = array();
		}
		if (isset($this->error['order_minimum'])) {
			$this->data['error_order_minimum'] = $this->error['order_minimum'];
		} else {
			$this->data['error_order_minimum'] = array();
		}
		if (isset($this->error['product_minimum'])) {
			$this->data['error_product_minimum'] = $this->error['product_minimum'];
		} else {
			$this->data['error_product_minimum'] = array();
		}

		//Status
		if (isset($this->request->post['customer_status'])) {
			$this->data['customer_status'] = $this->request->post['customer_status'];
		} else {
			$this->data['customer_status'] = $this->config->get('upload_customer_status');
		}
		if (isset($this->request->post['order_status'])) {
			$this->data['order_status'] = $this->request->post['order_status'];
		} else {
			$this->data['order_status'] = $this->config->get('upload_order_status');
		}
		if (isset($this->request->post['product_status'])) {
			$this->data['product_status'] = $this->request->post['product_status'];
		} else {
			$this->data['product_status'] = $this->config->get('upload_product_status');
		}

        //Limit
        if (isset($this->request->post['customer_limit'])) {
			$this->data['customer_limit'] = $this->request->post['customer_limit'];
		} elseif($this->config->get('upload_customer_limit')) {
			$this->data['customer_limit'] = $this->config->get('upload_customer_limit');
		} else {
            $this->data['customer_limit'] = 0;
        }
        if (isset($this->request->post['order_limit'])) {
			$this->data['order_limit'] = $this->request->post['order_limit'];
		} elseif($this->config->get('upload_order_limit')) {
			$this->data['order_limit'] = $this->config->get('upload_order_limit');
		} else {
            $this->data['order_limit'] = 0;
        }
        if (isset($this->request->post['product_limit'])) {
			$this->data['product_limit'] = $this->request->post['product_limit'];
		} elseif($this->config->get('upload_product_limit')) {
			$this->data['product_limit'] = $this->config->get('upload_product_limit');
		} else {
            $this->data['product_limit'] = 0;
        }

        //Minimum
        if (isset($this->request->post['customer_minimum'])) {
			$this->data['customer_minimum'] = $this->request->post['customer_minimum'];
		} elseif($this->config->get('upload_customer_minimum')) {
			$this->data['customer_minimum'] = $this->config->get('upload_customer_minimum');
		} else {
            $this->data['customer_minimum'] = 0;
        }
        if (isset($this->request->post['order_minimum'])) {
			$this->data['order_minimum'] = $this->request->post['order_minimum'];
		} elseif($this->config->get('upload_order_minimum')) {
			$this->data['order_minimum'] = $this->config->get('upload_order_minimum');
		} else {
            $this->data['order_minimum'] = 0;
        }
        if (isset($this->request->post['product_minimum'])) {
			$this->data['product_minimum'] = $this->request->post['product_minimum'];
		} elseif($this->config->get('upload_product_minimum')) {
			$this->data['product_minimum'] = $this->config->get('upload_product_minimum');
		} else {
            $this->data['product_minimum'] = 0;
        }

        //Customer Register
        if (isset($this->request->post['customer_register'])) {
			$this->data['customer_register'] = $this->request->post['customer_register'];
		} else {
			$this->data['customer_register'] = $this->config->get('upload_customer_register');
		}

        //Max Filesize
        if (isset($this->request->post['customer_max_filesize'])) {
			$this->data['customer_max_filesize'] = $this->request->post['customer_max_filesize'];
		} elseif ($this->config->get('upload_customer_max_filesize')) {
			$this->data['customer_max_filesize'] = $this->config->get('upload_customer_max_filesize');
		}
        if (isset($this->request->post['order_max_filesize'])) {
			$this->data['order_max_filesize'] = $this->request->post['order_max_filesize'];
		} elseif ($this->config->get('upload_order_max_filesize')) {
			$this->data['order_max_filesize'] = $this->config->get('upload_order_max_filesize');
		}
        if (isset($this->request->post['product_max_filesize'])) {
			$this->data['product_max_filesize'] = $this->request->post['product_max_filesize'];
		} elseif ($this->config->get('upload_product_max_filesize')) {
			$this->data['product_max_filesize'] = $this->config->get('upload_product_max_filesize');
		}

        //Message
        $this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['customer_message'])) {
			$this->data['customer_message'] = $this->request->post['customer_message'];
		} else {
			$customer_message = @unserialize($this->config->get('upload_customer_message'));
			$this->data['customer_message'] = $customer_message !== false ? $customer_message : '';
		}
        if (isset($this->request->post['order_message'])) {
			$this->data['order_message'] = $this->request->post['order_message'];
		} else {
			$order_message = @unserialize($this->config->get('upload_order_message'));
			$this->data['order_message'] = $order_message !== false ? $order_message : '';
		}
        if (isset($this->request->post['product_message'])) {
			$this->data['product_message'] = $this->request->post['product_message'];
		} else {
			$product_message = @unserialize($this->config->get('upload_product_message'));
			$this->data['product_message'] = $product_message !== false ? $product_message : '';
		}

        //Display
        if (isset($this->request->post['customer_display'])) {
			$this->data['customer_display'] = $this->request->post['customer_display'];
		} else {
			$this->data['customer_display'] = $this->config->get('upload_customer_display');
		}
        if (isset($this->request->post['order_display'])) {
			$this->data['order_display'] = $this->request->post['order_display'];
		} else {
			$this->data['order_display'] = $this->config->get('upload_order_display');
		}
        if (isset($this->request->post['product_display'])) {
			$this->data['product_display'] = $this->request->post['product_display'];
		} else {
			$this->data['product_display'] = $this->config->get('upload_product_display');
		}

        //Replace
        if (isset($this->request->post['product_replace'])) {
			$this->data['product_replace'] = $this->request->post['product_replace'];
		} else {
            $this->data['product_replace'] = $this->config->get('upload_product_replace');
        }

        //File types
        if (isset($this->request->post['customer_filetype'])) {
            $this->data['customer_filetypes'] = $this->request->post['customer_filetype'];
		} else {
            $customer_filetype = $this->config->get('upload_customer_filetype');
            $this->data['customer_filetypes'] = explode(',', $customer_filetype);
		}
        if (isset($this->request->post['order_filetype'])) {
            $this->data['order_filetypes'] = $this->request->post['order_filetype'];
		} else {
            $order_filetype = $this->config->get('upload_order_filetype');
            $this->data['order_filetypes'] = explode(',', $order_filetype);
		}
        if (isset($this->request->post['product_filetype'])) {
            $this->data['product_filetypes'] = $this->request->post['product_filetype'];
		} else {
            $product_filetype = $this->config->get('upload_product_filetype');
            $this->data['product_filetypes'] = explode(',', $product_filetype);
		}

		//Login
        if (isset($this->request->post['customer_login'])) {
			$this->data['customer_login'] = $this->request->post['customer_login'];
		} else {
			$this->data['customer_login'] = $this->config->get('upload_customer_login');
		}
        if (isset($this->request->post['order_login'])) {
			$this->data['order_login'] = $this->request->post['order_login'];
		} else {
			$this->data['order_login'] = $this->config->get('upload_order_login');
		}
        if (isset($this->request->post['product_login'])) {
			$this->data['product_login'] = $this->request->post['product_login'];
		} else {
			$this->data['product_login'] = $this->config->get('upload_product_login');
		}

		//Customer Groups
        if (isset($this->request->post['customer_customer_group'])) {
            $this->data['customer_customer_groups'] = $this->request->post['customer_customer_group'];
		} else {
            $customer_customer_group = $this->config->get('upload_customer_customer_group');
            $this->data['customer_customer_groups'] = explode(',', $customer_customer_group);
		}
        if (isset($this->request->post['order_customer_group'])) {
            $this->data['order_customer_groups'] = $this->request->post['order_customer_group'];
		} else {
            $order_customer_group = $this->config->get('upload_order_customer_group');
            $this->data['order_customer_groups'] = explode(',', $order_customer_group);
		}
        if (isset($this->request->post['product_customer_group'])) {
            $this->data['product_customer_groups'] = $this->request->post['product_customer_group'];
		} else {
            $product_customer_group = $this->config->get('upload_product_customer_group');
            $this->data['product_customer_groups'] = explode(',', $product_customer_group);
		}

		//Stores
        if (isset($this->request->post['customer_store'])) {
            $this->data['customer_stores'] = $this->request->post['customer_store'];
		} else {
            $customer_store = $this->config->get('upload_customer_store');
            $this->data['customer_stores'] = explode(',', $customer_store);
		}
        if (isset($this->request->post['order_store'])) {
            $this->data['order_stores'] = $this->request->post['order_store'];
		} else {
            $order_store = $this->config->get('upload_order_store');
            $this->data['order_stores'] = explode(',', $order_store);
		}
        if (isset($this->request->post['product_store'])) {
            $this->data['product_stores'] = $this->request->post['product_store'];
		} else {
            $product_store = $this->config->get('upload_product_store');
            $this->data['product_stores'] = explode(',', $product_store);
		}

        //Product only

        //Replace
        if (isset($this->request->post['product_replace'])) {
			$this->data['product_replace'] = $this->request->post['product_replace'];
		} else {
            $this->data['product_replace'] = $this->config->get('upload_product_replace');
        }		

        //Force Qty
        if (isset($this->request->post['product_force_qty'])) {
			$this->data['product_force_qty'] = $this->request->post['product_force_qty'];
		} else {
            $this->data['product_force_qty'] = $this->config->get('upload_product_force_qty');
        }	

		if (isset($this->request->post['product_option'])) {
            $this->data['product_options'] = $this->request->post['product_option'];
		} else {
            $this->data['product_options'] = explode(',', $this->config->get('upload_product_option'));
		}
		$this->data['upload_options'] = array();
		$this->load->model('catalog/option');
		$options = $this->model_catalog_option->getOptions();
		if(!empty($options)) {
			foreach ($options as $option) {
				if($option['type'] == 'file') {
					$this->data['upload_options'][] = array(
						'option_id' => $option['option_id'],
            			'name' => $option['name'],
					);
				}
			}
		}

		$this->data['filetypes'] = array();
        $filetypes = $this->model_myoc_copu->getFiletypes();
        foreach ($filetypes as $filetype) {
        	$this->data['filetypes'][$filetype['filetype_id']] = array(
        		'filetype_id' => $filetype['filetype_id'],
        		'ext' => $filetype['ext'],
        		'mime' => $filetype['mime'],
        		'delete' => $this->url->link('module/myoccopu/delete', 'filetype_id=' . $filetype['filetype_id'] . '&token=' . $this->session->data['token'], 'SSL'),
        	);
        }

		$this->load->model('sale/customer_group');
        $this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		$this->load->model('setting/store');
        $this->data['stores'] = $this->model_setting_store->getStores();

		$this->template = 'myoc/copu.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/myoccopu')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

        if (isset($this->request->post['customer_limit'])) {
            if(!is_numeric($this->request->post['customer_limit'])) {
                $this->error['customer_limit'] = $this->language->get('error_numeric');
                $this->error['warning'] = $this->language->get('error_submission');
            }
        }
        if (isset($this->request->post['customer_minimum'])) {
            if(!is_numeric($this->request->post['customer_minimum'])) {
                $this->error['customer_minimum'] = $this->language->get('error_numeric');
                $this->error['warning'] = $this->language->get('error_submission');
            }
        }

        if (isset($this->request->post['order_limit'])) {
            if(!is_numeric($this->request->post['order_limit'])) {
                $this->error['order_limit'] = $this->language->get('error_numeric');
                $this->error['warning'] = $this->language->get('error_submission');
            }
        }
        if (isset($this->request->post['order_minimum'])) {
            if(!is_numeric($this->request->post['order_minimum'])) {
                $this->error['order_minimum'] = $this->language->get('error_numeric');
                $this->error['warning'] = $this->language->get('error_submission');
            }
        }

        if (isset($this->request->post['product_limit'])) {
            if(!is_numeric($this->request->post['product_limit'])) {
                $this->error['product_limit'] = $this->language->get('error_numeric');
                $this->error['warning'] = $this->language->get('error_submission');
            }
        }
        if (isset($this->request->post['product_minimum'])) {
            if(!is_numeric($this->request->post['product_minimum'])) {
                $this->error['product_minimum'] = $this->language->get('error_numeric');
                $this->error['warning'] = $this->language->get('error_submission');
            }
        }

		if (!$this->error) {
            return true;
		} else {
            return false;
		}
	}

	public function insert() {
		$json = array();
		if (!$this->user->hasPermission('modify', 'module/myoccopu')) {
			$this->language->load('module/myoccopu');
			$json['error'] = $this->language->get('error_permission');
		}
		if(!$json) {
			$data = array(
				'ext' => $this->request->get['ext'],
				'mime' => $this->request->get['mime'],
			);
			$this->load->model('myoc/copu');
			$json['success'] = true;
			$json['filetype'] = $this->model_myoc_copu->addFiletype($data);
		}
		$this->response->setOutput(json_encode($json));
	}

	public function delete() {
		$json = array();
		if (!$this->user->hasPermission('modify', 'module/myoccopu')) {
			$this->language->load('module/myoccopu');
			$json['error'] = $this->language->get('error_permission');
		}
		if(!$json) {
	        $filetype_id = $this->request->get['filetype_id'];
	        $this->load->model('myoc/copu');

	        $filetype = $this->model_myoc_copu->getFiletype($filetype_id);
	        if($filetype) {
		        $this->model_myoc_copu->deleteFiletype($filetype_id);
			    $json['success'] = true;
			}
		}
        $this->response->setOutput(json_encode($json));
	}

	public function install()
	{
		$this->load->model('myoc/copu');
		$this->model_myoc_copu->installTable();

		$this->load->model('user/user_group');
		$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'myoc/copu');
		$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'myoc/copu');
	}

	public function uninstall()
	{
		$this->model_setting_setting->deleteSetting('copu');
		$this->load->model('myoc/copu');
		$this->model_myoc_copu->uninstallTable();
	}
}
?>