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
        $perPage = request()->get('perPage', 20);
        $transactionSupplier = $this->handleRepository->getData($data, $perPage);
        $listSuppliers = $this->supplierRepository->listSelectSupplier();
        return Inertia::render("admin/Suppliertransactions/Index", ["transactionSupplier"=> $transactionSupplier, 'listSuppliers' => $listSuppliers]);
    }
    public function update(int $id){
        // dd(request()->all());
        $supplierTransaction = $this->handleRepository->hanldeUpdateCreditDueDate($id, request()->all());
        return $this->returnInertia($supplierTransaction, "Cập nhật hạn công nợ thành công", 'admin.suppliers.index');
    }
    public function updatePayment(int $id){
        $supplierTransaction = $this->handleRepository->hanldeUpdatePayment($id, request()->all());
        return $this->returnInertia($supplierTransaction, "Cập nhật công nợ thành công", 'admin.suppliers.index');
    }
    public function show($id){
        $supplierTransaction = $this->handleRepository->getDataForShowTransaction($id);
        return Inertia::render('admin/Suppliertransactions/Show', ['supplierTransaction'=> $supplierTransaction]);
    }
    
}