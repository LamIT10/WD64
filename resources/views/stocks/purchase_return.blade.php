@section('content')
    <div class="w-full h-auto p-[20px] bg-[#f5f7fa]">
        <table class="w-full rounded-[10px] bg-[#ffffff] shadow-2xl">
            <tr class="w-full text-[#A49E9E] uppercase px-[20px] py-[10px] flex gap-x-[20px] justify-between items-center bg-[#f9f9f9] rounded-t-[10px] text-[12px] font-semibold">
                <td class="w-[5%] text-center"><strong>#</strong></td>
                <td class="w-[15%] text-center"><strong>Weaver</strong></td>
                <td class="w-[15%] text-center"><strong>Supplier</strong></td>
                <td class="w-[15%] text-center"><strong>Order No.</strong></td>
                <td class="w-[15%] text-center"><strong>Quality</strong></td>
                <td class="w-[10%] text-center"><strong>Colour</strong></td>
                <td class="w-[10%] text-center"><strong>Date</strong></td>
                <td class="w-[10%] text-center"><strong>Net Weight</strong></td>
                <td class="w-[15%] text-center"><strong></strong></td>
            </tr>
            <tr class="text-[#000] px-[20px] py-[10px] flex gap-x-[20px] justify-between items-center bg-[#ffffff] text-[12px] w-full mt-[10px] rounded-b-[10px]">
                <td class="w-[5%] text-center">1000</td>
                <td class="w-[15%] text-center">Shikhar Manandhar</td>
                <td class="w-[15%] text-center">Shikhara26</td>
                <td class="w-[15%] text-center">shikhara26@gmail.com</td>
                <td class="w-[15%] text-center">9869469364</td>
                <td class="w-[10%] text-center">B1</td>
                <td class="w-[10%] text-center">2/25/24</td>
                <td class="w-[10%] text-center">Sales & Marketing</td>
                <td class="w-[15%] text-center">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </td>
            </tr>
        </table>
        <button class="fixed bottom-[20px] right-[20px] w-[50px] h-[50px] bg-[#BE202F] text-white rounded-full flex justify-center items-center text-[24px] shadow-lg">
            <i class="fa-solid fa-plus"></i>
        </button>
    </div>
@endsection