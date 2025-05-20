@extends('app')
    @section('content')
    <div class="w-full h-[203px] my-[10px]">
        <table class="w-[90%] mx-auto my-0 rounded-[5px]">
            <tr class="w-full text-[#A49E9E] uppercase px-[20px] py-[10px] flex gap-x-[20px] justify-between items-center bg-[#ffffff] rounded-[10px] opacity-50 text-[12px] shadow-2xl">
                <td class="w-[5%] text-center">#</td>
                <td class="w-[15%] text-center">Order No.</td>
                <td class="w-[15%] text-center">Item</td>
                <td class="w-[15%] text-center">Customer</td>
                <td class="w-[15%] text-center">Status</td>
                <td class="w-[15%] text-center">Ordered on</td>
                <td class="w-[15%] text-center">Created By</td>
            </tr>
            <tr class="text-[#000] px-[20px] py-[10px] flex gap-x-[20px] justify-between items-center bg-[#ffffff]  text-[12px] w-full mt-[20px] rounded-[10px] shadow-2xl">
                <td class="w-[5%] text-center">1000</td>
                <td class="w-[15%] text-center">FG-Silk101474</td>
                <td class="w-[15%] text-center">Red Carpet</td>
                <td class="w-[15%] text-center">Silky Wool</td>
                <td class="w-[15%] text-center">
                    <div class="w-full h-[22px] rounded-[16px]  bg-[#F6E9E4] flex justify-center items-center gap-[10px] font-bold">
                        <i class="fa-solid fa-circle text-[#E54C42] text-[8px]"></i>
                        <p class="text-[#E54C42] text-[12px]">Pending</p>
                    </div>
                </td>
                <td class="w-[15%] text-center">2058-03-07</td>
                <td class="w-[15%] text-center">ParasBuda</td>
            </tr>
            <tr class="text-[#000] px-[20px] py-[10px] flex gap-x-[20px] justify-between items-center bg-[#ffffff]  text-[12px] w-full mt-[20px] rounded-[10px] shadow-2xl">
                <td class="w-[5%] text-center">1000</td>
                <td class="w-[15%] text-center">FG-Silk101474</td>
                <td class="w-[15%] text-center">Red Carpet</td>
                <td class="w-[15%] text-center">Silky Wool</td>
                <td class="w-[15%] text-center">
                    <div class="w-full h-[22px] rounded-[16px]  bg-[#F6E9E4] flex justify-center items-center gap-[10px] font-bold">
                        <i class="fa-solid fa-circle text-[#11B066] text-[8px]"></i>
                        <p class="text-[#11B066] text-[12px]">Weaving</p>
                    </div>
                </td>
                <td class="w-[15%] text-center">2058-03-07</td>
                <td class="w-[15%] text-center">ParasBuda</td>
            </tr>
        </table>
    </div>
    @endsection
