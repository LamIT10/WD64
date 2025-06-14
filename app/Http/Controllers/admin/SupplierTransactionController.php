<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\SupplierRepository;
use App\Repositories\SupplierTransactionRepository;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class SupplierTransactionController extends Controller
{
    private $supplierRepository;
    public function __construct(SupplierTransactionRepository $suppliertransactionRepository, SupplierRepository $supplierRepo)
    {
        $this->handleRepository = $suppliertransactionRepository;
        $this->supplierRepository = $supplierRepo;
    }
    public function index(){
        $data = request()->all();
        $perPage = request()->get('perPage', 15);
        $transactionSupplier = $this->handleRepository->getData($data, $perPage);
        $listSuppliers = $this->supplierRepository->listSelectSupplier();
        return Inertia::render("admin/Suppliertransactions/Index", ["transactionSupplier"=> $transactionSupplier, 'listSuppliers' => $listSuppliers]);
    }
    public function update(int $id){
        $supplierTransaction = $this->handleRepository->hanldeUpdateCreditDueDate($id, request()->all());
        return $this->returnInertia($supplierTransaction, "Cập nhật hạn công nợ thành công", 'admin.supplier-transaction.index');
    }
    public function updatePayment(int $id){
        $supplierTransaction = $this->handleRepository->hanldeUpdatePayment($id, request()->all());
        return $this->returnInertia($supplierTransaction, "Cập nhật hạn công nợ thành công", 'admin.supplier-transaction.index');
    }
    
}