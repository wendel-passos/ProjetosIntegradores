<?php

namespace Sts\Controllers;

if (!defined('URL')) {
    header("Location: /");
    exit();
}

class PedidoController
{
    public function __construct()
    {
        $u = new \Helper\Utils;
        $u->valSession();
    }

    public function index()
    {
        $loadView = new \Core\ConfigView("sts/Views/pedido/pedido");
        $loadView->renderAll();
    }

    public function getOrders()
    {
        $sendApp = new \Sts\Models\StsPedido();
        $sendApp->getOrders();
    }
}
