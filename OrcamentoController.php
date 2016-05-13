<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orcamento extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if(ENVIRONMENT === 'development') $this->output->enable_profiler(TRUE);
		$this->load->helper('form');
		$this->load->library('CarrinhoDeCompras');
		$this->load->library('validateCaptcha/CaptchaStrategy');
	}

	public function index(){
		$lista['tituloSite'] =	'Orçamento';
		$lista['breadcrumbs'] =  createBread( $this->uri->segment(1), $lista['tituloSite']);
		$lista['lista_carrinho'] = $this->carrinhodecompras->getAllDataProducts();

		if (empty($lista['lista_carrinho'])) {
			$this->template->load('template', 'orcamento/page_emptyCart', $lista);
		}else{
			$this->template->load('template', 'orcamento/page_list', $lista);
		}

	}

/*--------------------------------------
	ADICIONA UM PRODUTO AO CARRINHO. TODOS OS PARAMETROS SÃO OBRIGATÓRIOS.
---------------------------------------*/
	public function addCarrinho()
	{
		$this->carrinhodecompras->addProduct($this->input->post());
		redirect("orcamento");
	}

/*--------------------------------------
	ATUALIZAR INFORMAÇÕES QUE JÁ ESTÃO NO CARRINHO. TODOS OS PARAMETROS SÃO OBRIGATÓRIOS.
-----------------------------------------*/
	public function atualizar()
	{
		$this->carrinhodecompras->updateQtyProduct($this->input->post());
		redirect('orcamento');
	}
/*--------------------------------------
	REMOVE PRODUTOS QUE JÁ ESTÃO NO CARRINHO. TODOS OS PARAMETROS SÃO OBRIGATÓRIOS.
-----------------------------------------*/
	public function excluir()
	{
		$this->carrinhodecompras->removeProduct($this->input->post());
		redirect('orcamento');
	}

/*--------------------------------------
	ATUALIZAR INFORMAÇÕES QUE JÁ ESTÃO NO CARRINHO VIA JSON.TODOS OS PARAMETROS SÃO OBRIGATÓRIOS.
	NÃO RETORNA NENHUM PARAMETRO, APENAS ALTERA A QUANTIDADE DO PRODUTO.
-----------------------------------------*/
	public function updateByJson()
	{
		$this->carrinhodecompras->updateQtyProduct($this->input->post());
	}

/*--------------------------------------
	REMOVE PRODUTOS QUE JÁ ESTÃO NO CARRINHO.TODOS OS PARAMETROS SÃO OBRIGATÓRIOS.
	NÃO RETORNA NENHUM PARAMETRO, APENAS ALTERA A QUANTIDADE DO PRODUTO.
-----------------------------------------*/
	public function removeByJson()
	{
		$this->carrinhodecompras->removeProduct($this->input->post());
	}


// ************* FUNÇÕES PARA ENVIAR ORÇAMENTO *****************************//
//CRIA A PAGINA COM O FORMULARIO ESPECIFICO DE ENVIO, DEPENDENDO DO PROJETO PODE-SE USAR A PAGINA DE CONTATO.
	public function enviarPedido(){
		$lista['tituloSite'] =	'Finalizar Orçamento';
		$lista['breadcrumbs'] =  createBread( $this->uri->segment(1), 'Orçamento' , 'enviarPedido', $lista['tituloSite']);
		$lista['lista_carrinho'] = $this->carrinhodecompras->getAllDataProducts();
		$this->template->load('template', 'orcamento/page_form', $lista);
	}
//CRIA A PAGINA ONDE SERÁ CONFIRMADO O ENVIO DO FORMULARIO E LISTA OS PRODUTOS QUE FORAM ENVIADOS
	public function confirmacao(){
		$lista['tituloSite'] =	'Confirmação de Pedido';
		$lista['breadcrumbs'] =  createBread( $this->uri->segment(1), 'Orçamento' , 'confiramcao', $lista['tituloSite']);
		$lista['lista_carrinho'] = $this->carrinhodecompras->getAllDataProducts();
		$this->carrinhodecompras->removeAllProducts();//<-----função para destruir o carriinho pode ser executada em outro lugar quando necessário.//
		$this->template->load('template', 'orcamento/page_confirmacao', $lista);
	}

//FUUNÇÃO BÁSICA PARA ENVIAR FORMULÁRIO, USA O MESMO MODEL QUE A PAGINA CONTATO
	public function enviar()
	{
		$captchaInput = $this->input->post("captchaInput");
		$captchaHash = $this->input->post("captchaInputHash");
		$captchaTest = $this->captchastrategy;
		
		if ( $captchaTest->loredo->testMyCaptcha($captchaHash,$captchaInput) )
		{
			$this->load->model('emails_model');
			$dados = array(
				'nome' 		=> $this->input->post("c-nome"),
				'email' 	=> $this->input->post("c-email"),
				'telefone' 	=> $this->input->post("c-telefone"),
				'celular' 	=> $this->input->post("c-celular"),
				'cidade' 	=> $this->input->post("c-cidade"),
				'estado' 	=> $this->input->post("c-estado"),
				'mensagem' 	=> $this->input->post("c-mensagem"),
				'tipo' 		=> 'Orçamento'
			);

			// envia o email
			if($this->emails_model->enviarOrcamento($dados)){
				$this->session->set_flashdata('retorno', "msg-sucesso");
				redirect("orcamento/confirmacao");//<---------redireciona para a função confirmacaoDeOrcamento()
			}else{
				$this->session->set_flashdata("retorno", "msg-erro");
				redirect("orcamento/enviarPedido");//<---------redireciona para a função enviarPedido()
			}
		}
		else
		{
			$this->session->set_flashdata("retorno", "captcha-erro");
			redirect("orcamento/enviarPedido");//<---------redireciona para a função enviarPedido()
		}

	}

}