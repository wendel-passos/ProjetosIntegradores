<?php

namespace Sts\Controllers;

if (!defined('URL')) {
    header("Location: /");
    exit();
}

class CompraColetivaController
{
    private array $data;

    public function __construct()
    {
        $u = new \Helper\Utils;
        $u->valSession();
    }

    public function index()
    {
        $loadView = new \Core\ConfigView("sts/Views/compra-coletiva/compra-coletiva");
        $loadView->renderAll();
    }

    public function getProducts()
    {
        $sendApp = new \Sts\Models\StsCompraColetiva();
        $sendApp->getProducts();
    }

    public function productDetails()
    {
        $url = $_SERVER['REQUEST_URI'];
        $url_parts = parse_url($url);
        $path_parts = explode('/', $url_parts['path']);
        $data['id'] = end($path_parts);

        $loadView = new \Core\ConfigView("sts/Views/compra-coletiva/detalhes-produto", $data);
        $loadView->renderAll();
    }

    public function getProductDetails()
    {
        $url = $_SERVER['REQUEST_URI'];
        $url_parts = parse_url($url);
        $path_parts = explode('/', $url_parts['path']);
        $this->data['id_lista_compra'] = end($path_parts);

        $sendApp = new \Sts\Models\StsCompraColetiva($this->data);
        $sendApp->getProducts();
    }

    public function cadOrder()
    {
        $this->data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        $url = $_SERVER['REQUEST_URI'];
        $url_parts = parse_url($url);
        $path_parts = explode('/', $url_parts['path']);
        $this->data['id_lista_compra'] = end($path_parts);

        $sendApp = new \Sts\Models\StsCompraColetiva($this->data);
        $sendApp->cadOrder();
    }
}
