<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\SupplierRepository;
use App\Repositories\SupplierTransactionRepository;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
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
        $supplierTransaction = $this->handleRepository->hanldeUpdateCreditDueDate($id, request()->all());
        return $this->returnInertia($supplierTransaction, "Cập nhật hạn công nợ thành công", 'admin.suppliers.index');
    }
    public function updatePayment(int $id){
        $validator = Validator::make(request()->all(), [
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',  // Validation cho file
            'payment' => 'required|numeric|min:0',  // Validation cho số tiền thanh toán
        ], [
            'file.required' => 'Vui lòng chọn một tệp để tải lên.',
            'file.file' => 'Trường này phải là một tệp.',
            'file.mimes' => 'Chỉ cho phép các tệp có định dạng jpg, jpeg, png, pdf.',
            'file.max' => 'Kích thước tệp không được vượt quá 10MB.',
            'payment.required' => 'Số tiền thanh toán là bắt buộc.',
            'payment.numeric' => 'Số tiền thanh toán phải là một số.',
            'payment.min' => 'Số tiền thanh toán phải lớn hơn hoặc bằng 0.',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $supplierTransaction = $this->handleRepository->hanldeUpdatePayment($id, request()->all());
        return $this->returnInertia($supplierTransaction, "Cập nhật công nợ thành công", 'admin.suppliers.index');
    }
    public function show($id){
        $supplierTransaction = $this->handleRepository->getDataForShowTransaction($id);
        return Inertia::render('admin/Suppliertransactions/Show', ['supplierTransaction'=> $supplierTransaction]);
    }
    
}