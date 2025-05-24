@section('content')
<div class="w-full h-auto my-[10px] p-[20px] bg-[#f5f7fa]">
    <div class="w-[90%] mx-auto bg-[#ffffff] rounded-[10px] p-[15px] mb-[7px] relative">
        <h2 class="text-[14px] font-bold text-[#000]">Yarn Type</h2>
        <button class="absolute top-[15px] right-[20px] text-[#E54C42]">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
    <div class="w-[90%] mx-auto bg-[#ffffff] rounded-[10px] shadow-2xl p-[20px] mb-[30px]">
        <div class="mb-[15px]">
            <label class="block text-[12px] text-[#000] mb-[5px]">
                Name <span class="text-[#E54C42]">*</span>
            </label>
            <input type="text" placeholder="Name" class="w-full h-[40px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#f9f9f9]" />
        </div>
        <div class="mb-[15px]">
            <label class="block text-[12px] text-[#000] mb-[5px]">
                Description
            </label>
            <textarea placeholder="Enter Remarks" class="w-full h-[80px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#f9f9f9]"></textarea>
        </div>
        <div>
            <label class="flex justify-center text-[12px] text-[#000]">
                <input type="checkbox" class="mr-[5px]" checked />Active
            </label>
        </div>
    </div>
</div>
<div class="w-[87%] mx-auto flex justify-end">
    <button class="w-[100px] h-[40px] bg-[#BE202F] text-white rounded-[5px] flex justify-center items-center font-bold">
        <i class="fa-solid fa-plus mr-[5px]"></i> Save
    </button>
</div>
</div>
@endsection