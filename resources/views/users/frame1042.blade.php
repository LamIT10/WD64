
@section('content')
    <div class="w-90% bg-white shadow-sm px-[20px] py-[15px] flex rounded-[8px] justify-between items-center mt-6 mx-6">
        <div class="flex items-center gap-[10px]">

        </div>
        <div class="flex items-center gap-[15px]">
            <div class="flex items-center gap-[10px] text-[14px] text-[#6B7280]">
                <img src="" alt="">
                <span>BHUWAN BUDA</span>
                <i class="fa-solid fa-chevron-down text-[10px]"></i>
            </div>
        </div>
    </div>

    <div class="px-[20px] py-[30px]">
        <div class="flex justify-between items-center mb-[30px]">
            <h1 class="text-[24px] font-bold text-[#000000]">Users</h1>
            <button
                class="bg-[#2A66FF] text-white px-[20px] py-[8px] rounded-[8px] text-[14px] font-medium flex items-center gap-[8px]">
                <i class="fa-solid fa-plus text-[12px]"></i>
                Create
            </button>
        </div>

    
        <div class="flex gap-[30px] mb-[20px] border-b border-[#E5E7EB]">
            <button class="text-[#2A66FF] text-[14px] font-medium pb-[10px] border-b-2 border-[#4F46E5]">All User</button>
            <button class="text-[#000000] text-[14px] pb-[10px]">Active User</button>
            <button class="text-[#000000] text-[14px]  pb-[10px]">Passive User</button>
            <div class="ml-auto">
                <input type="text" placeholder="Search"
                    class="bg-[#F9FAFB] border border-[#E5E7EB] rounded-[9px] px-[12px] py-[8px] text-[14px] w-[200px]">
            </div>
        </div>

        <div class="w-full">
            <table class="w-full rounded-[10px] overflow-hidden">
         
                <tr
                    class="w-full text-[#A49E9E] uppercase px-[20px] py-[12px] flex gap-x-[20px] justify-between items-center bg-[#F9FAFB] text-[12px] font-medium">
                    <td class="w-[8%] text-left">#</td>
                    <td class="w-[15%] text-left">FULL NAME</td>
                    <td class="w-[12%] text-left">USERNAME</td>
                    <td class="w-[15%] text-left">EMAIL</td>
                    <td class="w-[12%] text-left">MOBILE NO.</td>
                    <td class="w-[15%] text-left">DEPARTMENT</td>
                    <td class="w-[10%] text-center">SUPERUSER</td>
                    <td class="w-[8%] text-center">STAFF</td>
                    <td class="w-[8%] text-center">STATUS</td>
                    <td class="w-[5%] text-center"></td>
                </tr>

          
                <tr
                    class="text-[#000000] px-[20px] py-[15px] flex gap-x-[20px] justify-between items-center bg-[#ffffff] text-[14px] w-full mt-[2px] shadow-sm">
                    <td class="w-[8%] text-left">1000</td>
                    <td class="w-[15%] text-left">
                        <div>
                            <div class="font-medium">Shikhar</div>
                            <div class="text-[12px] text-[#000000] ">Manandhar</div>
                        </div>
                    </td>
                    <td class="w-[12%] text-left">Shikharaa26</td>
                    <td class="w-[15%] text-left">shikharaa26@gmail.com</td>
                    <td class="w-[12%] text-left">9869496364</td>
                    <td class="w-[15%] text-left">Sales & Marketing</td>
                    <td class="w-[10%] text-center">
                        <div class="w-[24px] h-[24px] bg-[#4DB163] rounded-full mx-auto">
                                <i class="fa-solid fa-check text-white text-[12px]"></i>
                        </div>
                    </td>
                    <td class="w-[8%] text-center">
                        <div class="w-[24px] h-[24px] bg-[#4DB163] rounded-full mx-auto">
                                <i class="fa-solid fa-check text-white text-[12px]"></i>
                        </div>
                    </td>
                    <td class="w-[8%] text-center">
                        <div class="bg-[#4DB163] text-[#FFFFFF] px-[8px] py-[4px] rounded-[16px] text-[12px] font-medium">
                            Active
                        </div>
                    </td>
                    <td class="w-[5%] text-center">
                        <i class="fa-solid fa-ellipsis text-[#6B7280] cursor-pointer"></i>
                    </td>
                </tr>

                <tr
                    class="text-[#000000] px-[20px] py-[15px] flex gap-x-[20px] justify-between items-center bg-[#ffffff] text-[14px] w-full mt-[2px] shadow-sm">
                    <td class="w-[8%] text-left">1000</td>
                    <td class="w-[15%] text-left">
                        <div>
                            <div class="font-medium">Shikhar</div>
                            <div class="text-[12px]text-[#000000]">Manandhar</div>
                        </div>
                    </td>
                    <td class="w-[12%] text-left">Shikharaa26</td>
                    <td class="w-[15%] text-left">shikharaa26@gmail.com</td>
                    <td class="w-[12%] text-left">9869496364</td>
                    <td class="w-[15%] text-left">Sales & Marketing</td>
                    <td class="w-[10%] text-center">
                        <div class="w-[24px] h-[24px] bg-[#D93F21] rounded-full mx-auto">
                                <i class="fa-solid fa-check text-white text-[12px]"></i>
                        </div>
                    </td>
                    <td class="w-[8%] text-center">
                        <div class="w-[24px] h-[24px] bg-[#4DB163] rounded-full mx-auto">
                                <i class="fa-solid fa-check text-white text-[12px]"></i>
                        </div>
                    </td>
                    <td class="w-[8%] text-center">
                        <div class="bg-[#4DB163] text-[#FFFFFF] px-[8px] py-[4px] rounded-[16px] text-[12px] font-medium">
                            Active
                        </div>
                    </td>
                    <td class="w-[5%] text-center">
                        <i class="fa-solid fa-ellipsis text-[#6B7280] cursor-pointer"></i>
                    </td>
                </tr>

          
                <tr
                    class="text-[#000000] px-[20px] py-[15px] flex gap-x-[20px] justify-between items-center bg-[#ffffff] text-[14px] w-full mt-[2px] shadow-sm">
                    <td class="w-[8%] text-left">1000</td>
                    <td class="w-[15%] text-left">
                        <div>
                            <div class="font-medium">Shikhar</div>
                            <div class="text-[12px] text-[#000000]">M Anandhar</div>
                        </div>
                    </td>
                    <td class="w-[12%] text-left">Shikharaa26</td>
                    <td class="w-[15%] text-left">shikharaa26@gmail.com</td>
                    <td class="w-[12%] text-left">9869496364</td>
                    <td class="w-[15%] text-left">Sales & Marketing</td>
                    <td class="w-[10%] text-center">
                        <div class="w-[24px] h-[24px] bg-[#4DB163] rounded-full mx-auto">
                                <i class="fa-solid fa-check text-white text-[12px]"></i>
                        </div>
                    </td>
                    <td class="w-[8%] text-center">
                        <div class="w-[24px] h-[24px] bg-[#4DB163] rounded-full mx-auto">
                                <i class="fa-solid fa-check text-white text-[12px]"></i>
                        </div>
                    </td>
                    <td class="w-[8%] text-center">
                        <div class="bg-[#D93F21] text-[#FFFFFF] px-[8px] py-[4px] rounded-[16px] text-[12px] font-medium">
                            In-Active
                        </div>
                    </td>
                    <td class="w-[5%] text-center">
                        <i class="fa-solid fa-ellipsis text-[#6B7280] cursor-pointer"></i>
                    </td>
                </tr>
            </table>
        </div>
    </div>
@endsection