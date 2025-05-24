@section('content')
    <div class="w-full h-auto my-[10px] p-[20px] bg-[#f5f7fa]">
        <div class="w-[90%] mx-auto bg-[#ffffff] rounded-[10px] shadow p-[20px] mb-[30px]">
            <table class="w-full text-[12px] text-[#000]">
                <thead>
                    <tr class="bg-[#f9f9f9] text-[#A49E9E] uppercase">
                        <th class="p-[10px] text-left">#</th>
                        <th class="p-[10px] text-left">Name</th>
                        <th class="w-[15%] text-center">Status</th>
                        <th class="p-[10px] text-left"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="p-[10px]">1</td>
                        <td class="p-[10px]">White Yarn</td>
                        <td class="w-[15%] text-center">
                            <div class="w-full h-[22px] rounded-[16px] bg-[#E6F7EC] flex justify-center items-center gap-[10px] font-bold">
                                <i class="fa-solid fa-circle text-[#11B066] text-[8px]"></i>
                                <p class="text-[#11B066] text-[12px]">Active</p>
                            </div>
                        </td>
                        <td class="p-[10px] text-right">
                            <button class="text-[#000]"><i class="fa-solid fa-ellipsis"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <button class="fixed bottom-[20px] right-[20px] w-[50px] h-[50px] bg-[#BE202F] text-white rounded-full flex justify-center items-center text-[24px] shadow-lg">
            <i class="fa-solid fa-plus"></i>
            </button>
        </div>     
    </div>
@endsection