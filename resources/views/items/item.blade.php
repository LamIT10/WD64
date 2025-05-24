@section('content')
    <div class="w-full h-auto my-[10px] p-[20px] bg-[#f5f7fa]">
        <div class="w-[90%] mx-auto bg-[#ffffff] rounded-[10px] shadow-2xl p-[20px] mb-[30px]">
            <table class="w-full text-[12px] text-[#000]">
                <thead>
                    <tr class="bg-[#f9f9f9] text-[#A49E9E] uppercase ">
                        <th class="p-[10px] text-left">S.N</th>
                        <th class="p-[10px] text-left">Item Code</th>
                        <th class="p-[10px] text-left">Name</th>
                        <th class="p-[10px] text-left">Type</th>
                        <th class="p-[10px] text-left">Quality</th>
                        <th class="p-[10px] text-left">Rate</th>
                        <th class="p-[10px] text-left">Status</th>
                        <th class="p-[10px] text-left"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="p-[10px]">1</td>
                        <td class="p-[10px]">26718</td>
                        <td class="p-[10px]">MST Carpet</td>
                        <td class="p-[10px]">Natural</td>
                        <td class="p-[10px]">Silk</td>
                        <td class="p-[10px]">2500</td>
                        <td class="p-[10px]">
                            <span class="inline-block px-[10px] py-[5px] bg-[#11B066] text-[#fff] rounded-[15px]">Active</span>
                        </td>
                        <td class="p-[10px] text-right">
                            <button class="text-[#000]"><i class="fa-solid fa-ellipsis"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-[10px]">2</td>
                        <td class="p-[10px]">26718</td>
                        <td class="p-[10px]">MST Carpet</td>
                        <td class="p-[10px]">Natural</td>
                        <td class="p-[10px]">Silk</td>
                        <td class="p-[10px]">2500</td>
                        <td class="p-[10px]">
                            <span class="inline-block px-[10px] py-[5px] bg-[#E54C42] text-[#fff] rounded-[15px]">Inactive</span>
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