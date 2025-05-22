

@section('content')

<div class="px-[20px]">
    <div class="w-full bg-[#FFFFFF] rounded-[10px] px-[10px] py-[10px]">
        <div class="flex justify-between items-center">
            <h3 class="text-[16px] font-semibold">Karma Suppliers Pvt. Ltd. Due Bills</h3>
            <button class="text-[#D93F21] px-[15px] py-[6px] rounded-[12px] text-[15px] leading-none">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>
  
<div class="w-full h-[203px] my-[10px]">
    <table class="w-full mx-auto my-0 rounded-[5px]">
        <tr class="w-full text-[#A49E9E] uppercase px-[20px] py-[10px] flex gap-x-[20px] justify-between items-center bg-[#ffffff] rounded-[10px] opacity-50 text-[12px] shadow-2xl">
            <td class="w-[5%] text-center">#</td>
            <td class="w-[15%] text-center">bill no</td>
            <td class="w-[15%] text-center">Invoice DAte</td>
            <td class="w-[15%] text-center">Due Amount</td>
            <td class="w-[15%] text-center">Aging </td>
   
            <td class="w-[15%] text-center"></td>
        </tr>
        <tr class="text-[#000] px-[20px] py-[10px] flex gap-x-[20px] justify-between items-center bg-[#ffffff]  text-[12px] w-full mt-[10px] rounded-[10px] shadow-2xl">
            <td class="w-[5%] text-center">1</td>
            <td class="w-[15%] text-center">INV 56</td>
            <td class="w-[15%] text-center">12/06/2020</td>
            <td class="w-[15%] text-center">$680.00</td>
            <td class="w-[15%] text-center">16 Days</td>
            <td class="w-[15%] text-center">
            <i class="fa-solid fa-ellipsis text-[#6B7280] cursor-pointer"></i>
            </td>
        </tr>
        <tr class="text-[#000] px-[20px] py-[10px] flex gap-x-[20px] justify-between items-center bg-[#ffffff]  text-[12px] w-full mt-[10px] rounded-[10px] shadow-2xl">
            <td class="w-[5%] text-center">1</td>
            <td class="w-[15%] text-center">INV 28</td>
            <td class="w-[15%] text-center">28/10/2012</td>
            <td class="w-[15%] text-center">$5,600.00</td>
            <td class="w-[15%] text-center">16 Days</td>
            <td class="w-[15%] text-center">
            <i class="fa-solid fa-ellipsis text-[#6B7280] cursor-pointer"></i>
            </td>
        </tr>
        <tr class="text-[#000] px-[20px] py-[10px] flex gap-x-[20px] justify-between items-center bg-[#ffffff]  text-[12px] w-full mt-[10px] rounded-[10px] shadow-2xl">
            <td class="w-[5%] text-center">1</td>
            <td class="w-[15%] text-center">INV 25</td>
            <td class="w-[15%] text-center">15/08/2017</td>
            <td class="w-[15%] text-center">$102.00</td>
            <td class="w-[15%] text-center">16 Days</td>
            <td class="w-[15%] text-center">
             <i class="fa-solid fa-ellipsis text-[#6B7280] cursor-pointer"></i>
            </td>
        </tr>
        <tr class="text-[#000] px-[20px] py-[10px] flex gap-x-[20px] justify-between items-center bg-[#ffffff]  text-[12px] w-full mt-[10px] rounded-[10px] shadow-2xl">
            <td class="w-[5%] text-center">1</td>
            <td class="w-[15%] text-center">INV 64</td>
            <td class="w-[15%] text-center">18/09/2016</td>
            <td class="w-[15%] text-center">$120,000.00</td>
            <td class="w-[15%] text-center">16 Days</td>
            <td class="w-[15%] text-center">
            <i class="fa-solid fa-ellipsis text-[#6B7280] cursor-pointer"></i>
            </td>
        </tr>
       
    </table>
</div>

@endsection