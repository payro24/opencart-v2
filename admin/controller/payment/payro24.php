<?php 

class ControllerPaymentpayro24 extends Controller
{
    /**
     * @var array
     */
	private $error = array ();

    /**
     * payro24 setting
     */
	public function index()
	{
		$this->load->language('payment/payro24');
		$this->load->model('setting/setting');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {

			$this->model_setting_setting->editSetting('payro24', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_authorization'] = $this->language->get('text_authorization');
		$data['text_sale'] = $this->language->get('text_sale');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_success_message'] = $this->language->get('text_success_message');
        $data['text_failed_message'] = $this->language->get('text_failed_message');
        $data['text_sort_order'] = $this->language->get('text_sort_order');

        $data['text_sandbox'] = $this->language->get('text_sandbox');
        $data['text_sandbox_help'] = $this->language->get('text_sandbox_help');
        $data['text_api_key'] = $this->language->get('text_api_key');
        $data['text_status'] = $this->language->get('text_status');
        $data['text_order_status'] = $this->language->get('text_order_status');
        $data['text_order_status'] = $this->language->get('text_order_status');

        $data['entry_payment_successful_message_default'] = $this->language->get('entry_payment_successful_message_default');
        $data['entry_payment_failed_message_default'] = $this->language->get('entry_payment_failed_message_default');
        $data['entry_sandbox_yes'] = $this->language->get('entry_sandbox_yes');
        $data['entry_sandbox_no'] = $this->language->get('entry_sandbox_no');
        $data['text_successful_message_help'] = $this->language->get('text_successful_message_help');
        $data['text_failed_message_help'] = $this->language->get('text_failed_message_help');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

        $data['tab_general'] = $this->language->get('tab_general');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array (

			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array (

			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array (

			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/payro24', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/payro24', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->error['warning'])) {

			$data['error_warning'] = $this->error['warning'];

		} else {

			$data['error_warning'] = false;
		}

		if (isset($this->error['api_key'])) {

			$data['error_api_key'] = $this->error['api_key'];

		} else {

			$data['error_api'] = false;
		}

		if (isset($this->request->post['api_key'])) {

			$data['payro24_api_key'] = $this->request->post['payro24_api_key'];

		} else {

			$data['payro24_api_key'] = $this->config->get('payro24_api_key');
		}

        if (isset($this->request->post['payro24_sandbox'])) {

            $data['payro24_sandbox'] = $this->request->post['payro24_sandbox'];

        } else {

            $data['payro24_sandbox'] = $this->config->get('payro24_sandbox');
        }

		if (isset($this->request->post['payro24_order_status_id'])) {

			$data['payro24_order_status_id'] = $this->request->post['payro24_order_status_id'];

		} else {

			$data['payro24_order_status_id'] = $this->config->get('payro24_order_status_id');
		}

        if (isset($this->request->post['payro24_payment_successful_message'])) {

            $data['payro24_payment_successful_message'] = trim($this->request->post['payro24_payment_successful_message']);

        } else {

            $data['payro24_payment_successful_message'] = trim($this->config->get('payro24_payment_successful_message'));
        }

        if (isset($this->request->post['payro24_payment_failed_message'])) {

            $data['payro24_payment_failed_message'] = trim($this->request->post['payro24_payment_failed_message']);

        } else {

            $data['payro24_payment_failed_message'] = trim($this->config->get('payro24_payment_failed_message'));
        }

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payro24_status'])) {

			$data['payro24_status'] = $this->request->post['payro24_status'];

		} else {

			$data['payro24_status'] = $this->config->get('payro24_status');
		}

		if (isset($this->request->post['payro24_sort_order'])) {

			$data['payro24_sort_order'] = $this->request->post['payro24_sort_order'];

		} else {

			$data['payro24_sort_order'] = $this->config->get('payro24_sort_order');
		}

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('payment/payro24.tpl', $data));
	}

    /**
     * @return bool
     */
	private function validate()
	{
		if (!$this->user->hasPermission('modify', 'payment/payro24')) {

			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payro24_api_key']) {

			$this->error['warning'] = $this->language->get('error_validate');
			$this->error['api_key'] = $this->language->get('error_api_key');
		}

		if (!$this->error) {

			return true;

		} else {

			return false;
		}
	}

}