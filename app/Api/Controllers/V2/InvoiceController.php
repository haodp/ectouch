<?php

namespace app\api\controller\v2;

use app\api\controller\Controller;
use app\api\model\v2\Invoice;

class InvoiceController extends Controller
{

    /**
    * POST ecapi.invoice.type.list
    */
    public function type()
    {
        $data = Invoice::getTypeList();
        return $this->json($data);
    }

    /**
    * POST ecapi.invoice.content.list
    */
    public function content()
    {
        $data = Invoice::getContentList();
        return $this->json($data);
    }

    /**
    * POST ecapi.invoice.status.get
    */
    public function status()
    {
        $data = Invoice::getStatus();
        return $this->json($data);
    }
}
