@section('content')
    <div class="w-full h-auto p-[20px] bg-[#f5f7fa]">
        <table class="w-full rounded-[10px] bg-[#ffffff] shadow-2xl">
            <tr class="w-full text-[#A49E9E] uppercase px-[20px] py-[10px] flex gap-x-[20px] justify-between items-center bg-[#f9f9f9] rounded-t-[10px] text-[12px] font-semibold">
                <td class="w-[5%] text-center"><strong>#</strong></td>
                <td class="w-[15%] text-center"><strong>Item Code</strong></td>
                <td class="w-[15%] text-center"><strong>Item</strong></td>
                <td class="w-[15%] text-center"><strong>Customer Order</strong></td>
                <td class="w-[10%] text-center"><strong>Qty</strong></td>
                <td class="w-[15%] text-center"><strong>Status</strong></td>
                <td class="w-[15%] text-center"><strong>Request On</strong></td>
                <td class="w-[10%] text-center"><strong></strong></td>
            </tr>
            <tr class="text-[#000] px-[20px] py-[10px] flex gap-x-[20px] justify-between items-center bg-[#ffffff] text-[12px] w-full mt-[10px]">
                <td class="w-[5%] text-center">3</td>
                <td class="w-[15%] text-center">FG-Silk101474</td>
                <td class="w-[15%] text-center">Blue Thread</td>
                <td class="w-[15%] text-center">Dy-009</td>
                <td class="w-[10%] text-center">50</td>
                <td class="w-[15%] text-center">
                    <div class="w-full h-[22px] rounded-[16px] bg-[#E6F0FA] flex justify-center items-center gap-[10px] font-bold">
                        <i class="fa-solid fa-circle text-[#1E90FF] text-[8px]"></i>
                        <p class="text-[#1E90FF] text-[12px]">Pending</p>
                    </div>
                </td>
                <td class="w-[15%] text-center">2058-03-07</td>
                <td class="w-[10%] text-center">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </td>
            </tr>
            <tr class="text-[#000] px-[20px] py-[10px] flex gap-x-[20px] justify-between items-center bg-[#ffffff] text-[12px] w-full mt-[10px]">
                <td class="w-[5%] text-center">3</td>
                <td class="w-[15%] text-center">FG-Silk101474</td>
                <td class="w-[15%] text-center">Yellow Thread</td>
                <td class="w-[15%] text-center">CO-008</td>
                <td class="w-[10%] text-center">50</td>
                <td class="w-[15%] text-center">
                    <div class="w-full h-[22px] rounded-[16px] bg-[#E6F7EC] flex justify-center items-center gap-[10px] font-bold">
                        <i class="fa-solid fa-circle text-[#11B066] text-[8px]"></i>
                        <p class="text-[#11B066] text-[12px]">Completed</p>
                    </div>
                </td>
                <td class="w-[15%] text-center">2058-03-07</td>
                <td class="w-[10%] text-center">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </td>
            </tr>
            <tr class="text-[#000] px-[20px] py-[10px] flex gap-x-[20px] justify-between items-center bg-[#ffffff] text-[12px] w-full mt-[10px] rounded-b-[10px]">
                <td class="w-[5%] text-center">3</td>
                <td class="w-[15%] text-center">FG-Silk101474</td>
                <td class="w-[15%] text-center">Yellow Thread</td>
                <td class="w-[15%] text-center">CO-008</td>
                <td class="w-[10%] text-center">50</td>
                <td class="w-[15%] text-center">
                    <div class="w-full h-[22px] rounded-[16px] bg-[#F6E9E4] flex justify-center items-center gap-[10px] font-bold">
                        <i class="fa-solid fa-circle text-[#E54C42] text-[8px]"></i>
                        <p class="text-[#E54C42] text-[12px]">Terminated</p>
                    </div>
                </td>
                <td class="w-[15%] text-center">2058-03-07</td>
                <td class="w-[10%] text-center">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </td>
            </tr>
        </table>
        <button class="fixed bottom-[20px] right-[20px] w-[50px] h-[50px] bg-[#BE202F] text-white rounded-full flex justify-center items-center text-[24px] shadow-lg">
            <i class="fa-solid fa-plus"></i>
        </button>
    </div>
@endsection