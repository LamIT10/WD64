<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\CustomerTransactionRepository;
use Inertia\Inertia;

class CustomerTransactionController extends Controller
{
    public function __construct(CustomerTransactionRepository $customertransactionRepository)
    {
        $this->handleRepository = $customertransactionRepository;
    }
    public function index(){
        $data = request()->all();
     
        $customerTransaction = $this->handleRepository->getData($data);
   
        return Inertia::render("admin/Customertransactions/Index");
    }
}