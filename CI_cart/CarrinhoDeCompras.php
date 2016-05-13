<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CarrinhoDeCompras extends CI_Cart{

	public function __construct(){
		parent::__construct();
	}


/*---------------------------------------------------------
	ADICIONAR ITENS AO CARRINHO FUNÇÃO OBRIGATÓRIA. 3 PRIMEIROS PARAMETROS OBRIGATÓRIOS.
---------------------------------------------------------*/
	public function addProduct()
	{
	//Recebo todo o conteúdo postado por um formulário
		foreach($dataProduct as $prod){
			$data = array(
               	'id'      => $prod['id-produto'],
               	'qty'     => $prod['qnt-produto'],
               	'price'   =>  1,
               	'name'    => $prod['nome-produto'],
              	'options' => array(
              		'ref' => $prod['ref-produto'],
              		'image' => $prod['imagem']
              		)
            );
		}

		$this->product_name_rules = "'\d\D'";
		if (!$this->insert($data)){
			return false;
		}

	}


/*---------------------------------------------------------
	ATUALIZAR INFORMAÇÕES QUE JÁ ESTÃO NO CARRINHO. TODOS OS PARAMETROS SÃO OBRIGATÓRIOS.
---------------------------------------------------------*/
	public function updateQtyProduct($dataProduct)
	{
	//Recebo todo o conteúdo postado por um formulário
		foreach($dataProduct as $prod){
			$dados[] = array(
				"rowid" => $prod['rowid'],
				"qty" => $prod['qty']
			);
		}

		if (!$this->update($dados)){
			return false;
		}
	}


/*---------------------------------------------------------
	EXCLUI UM UNICO PRODUTO OU VARIOS PRODUTOS;
	É A MESMA FUNÇÃO QUE updateQtyProduct() ACIMA, O PARAMETRO "quantidade" SOMENTE PRACISA RECEBER 0;
---------------------------------------------------------*/
	public function removeProduct($dataProduct)
	{
	//Recebo todo o conteúdo postado por um formulário
		foreach($dataProduct as $content){
			$dados[] = array(
				"rowid" => $content['rowid'],
				"qty" => 0,
			);
		}

		if (!$this->update($dados)){
			return false;
		}
	}


/*---------------------------------------------------------
	APAGA TODOS OS DADOS DO CARRINHO DE COMPRAS;
---------------------------------------------------------*/
	public function removeAllProducts()
	{
		$this->destroy();
	}


/*---------------------------------------------------------
	RETORNA TODOS OS DADOS DO CARRINHO DE COMPRAS;
---------------------------------------------------------*/
	public function getAllDataProducts()
	{
		return $this->contents();
	}


}
