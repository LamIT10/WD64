@section('content')
    <div class="w-full h-auto my-[5px] p-[10px] bg-[#f5f7fa]">
        <div class="w-[50%] mx-auto bg-[#ffffff] rounded-[8px] shadow-xl p-[15px] mb-[15px]">
            <div class="flex items-center justify-between mb-[10px]">
                <h2 class="text-[14px] font-bold text-[#E54C42]">Why Do You Want To Reject?</h2>
                <button class="text-[#E54C42]">
                    <i class="fa-solid fa-times text-[14px]"></i>
                </button>
            </div>
            <div class="mb-[10px]">
                <label class="block text-[10px] text-[#000] mb-[3px]">
                    Remarks <span class="text-[#E54C42]">*</span>
                </label>
                <textarea placeholder="Enter Remarks" class="w-full h-[60px] rounded-[4px] border border-[#ddd] text-[10px] text-[#000] p-[8px] bg-[#f9f9f9]"></textarea>
            </div>
            <div class="flex justify-center gap-[30px] ">
                <button class="w-[90px] h-[30px] bg-[#E54C42] text-[#fff] rounded-[4px] flex justify-center items-center font-bold uppercase text-[10px]">Terminate</button>
                <button class="w-[90px] h-[30px] bg-[#11B066] text-[#fff] rounded-[4px] flex justify-center items-center font-bold uppercase text-[10px]">Try New Lot</button>
            </div>
        </div>
    </div>
@endsection 
