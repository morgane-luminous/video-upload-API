<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    public function index()
    {
        $resp = 'Hello world!';
        return $this->json($resp);
    }

    public function uploadVideo()
    {
        $resp = 'Hello world!';
        return $this->json($resp);
    }
}
