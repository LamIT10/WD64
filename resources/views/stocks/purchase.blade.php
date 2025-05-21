@section('content')
    <div class="w-full h-auto my-[10px] p-[20px] bg-[#f5f7fa]">
        <div class="w-[90%] mx-auto bg-[#ffffff] rounded-[10px] shadow-2xl p-[15px] mb-[7px] relative">
            <h2 class="text-[14px] font-bold text-[#000]">Purchase</h2>
            <button class="absolute top-[15px] right-[20px] text-[#E54C42]">
                <i class="fa-solid fa-times"></i>
            </button>   
        </div>
        <div class="w-[90%] mx-auto bg-[#ffffff] rounded-[10px] shadow-2xl p-[20px] mb-[20px] relative">
            <div class="grid grid-cols-2 gap-x-[20px] gap-y-[15px]">
                <div>
                    <label class="block text-[12px] text-[#A49E9E] uppercase mb-[5px]">Name</label>
                    <input type="text" value="YARN" class="w-full h-[40px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#f9f9f9]" readonly />
                </div>
                <div>
                    <label class="block text-[12px] text-[#A49E9E] uppercase mb-[5px]">Supplier</label>
                    <select class="w-full h-[40px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#fff]">
                        <option>Select Supplier</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[12px] text-[#A49E9E] uppercase mb-[5px]">Quality</label>
                    <input type="text" value="Yarn" class="w-full h-[40px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#fff]" />
                </div>
                <div>
                    <label class="block text-[12px] text-[#A49E9E] uppercase mb-[5px]">Color</label>
                    <select class="w-full h-[40px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#fff]">
                        <option>Select Color</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-[20px] mt-[15px]">
                <div class="flex-1">
                    <label class="block text-[12px] text-[#A49E9E] uppercase mb-[5px]">Quantity</label>
                    <input type="text" value="Kg" class="w-full h-[40px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#fff]" />
                </div>
                <div class="flex-1">
                    <label class="block text-[12px] text-[#A49E9E] uppercase mb-[5px]">Rate (Rs.)</label>
                    <input type="text" placeholder="Rate" class="w-full h-[40px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#fff]" />
                </div>
                <div class="flex-1">
                    <label class="block text-[12px] text-[#A49E9E] uppercase mb-[5px]">Total (Rs.)</label>
                    <input type="text" placeholder="Total" class="w-full h-[40px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#fff]" />
                </div>
            </div>
            <div class="mt-[15px]">
                <label class="block text-[12px] text-[#000] uppercase mb-[5px]">Remarks</label>
                <textarea class="w-full h-[80px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#fff]" placeholder="Remarks"></textarea>
            </div>
            <div class="flex justify-end mt-[15px]">
                <button class="w-[100px] h-[40px] bg-[#BE202F] text-[#fff] rounded-[5px] flex justify-center items-center font-bold">
                    <i class="fa-solid fa-plus mr-[5px]"></i> Save
                </button>
            </div>
        </div>
    </div>
@endsection
