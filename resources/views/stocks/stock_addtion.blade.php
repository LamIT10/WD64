
@section('content')
    <div class="px-[20px]">
        <div class="w-full bg-[#FFFFFF] rounded-[10px] px-[10px] py-[10px]">
            <div class="flex justify-between items-center">
                <h3 class="text-[14px] font-bold text-[#000000] leading-none">Stock Addtion</h3>
                <button class="text-[#D93F21] px-[15px] py-[6px] rounded-[12px] text-[15px] leading-none">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>


        <div class="w-full bg-[#FFFFFF] rounded-[10px] px-[10px] py-[10px] mt-[10px]">
            <div class="w-[100%] mx-auto my-[10px] grid grid-cols-4 gap-x-[20px]">

                <div class="flex flex-col gap-[5px]">
                    <label class="text-[12px] text-[#000000]">Supplier</label>
                    <select
                        class="w-full px-[12px] py-[8px] text-[12px] text-[#A5A1A1] rounded-[5px]  bg-[#ffffff] border border-gray-200">
                        <option>Yarn Type</option>
                    </select>
                </div>


                <div class=" flex flex-col gap-[5px]">
                    <label class="text-[12px] text-[#000000] ">Item</label>
                    <input type="text" value="10521" disabled
                        class="w-full px-[12px] py-[8px] text-[12px] text-[#000] rounded-[5px]  bg-[#ffffff] border border-gray-200">
                </div>


                <div class="flex flex-col gap-[5px]">
                    <label class="text-[12px] text-[#000000] ">Item Code</label>
                    <input type="text" value="10521" disabled
                        class="w-full px-[12px] py-[8px] text-[12px] text-[#000] rounded-[5px]  bg-[#ffffff] border border-gray-200">
                </div>


                <div class="flex gap-[10px] items-end">
                    <div class="flex flex-col gap-[5px] w-full">
                        <label class="text-[12px] text-[#000000] ">Delivery Date</label>
                        <input type="text" value="10521" disabled
                            class="w-full px-[12px] py-[8px] text-[12px] text-[#000] rounded-[5px]  bg-[#ffffff] border border-gray-200">
                    </div>


                </div>
            </div>
        </div>

        <div class="w-full px-[20px] py-[10px] my-[10px] bg-[#ffffff] rounded-[10px]">

            <div class="w-full bg-[#FFFFFF] rounded-[10px] px-[10px] py-[10px] mt-[10px]">
                <div class="w-[100%] mx-auto my-[10px] grid grid-cols-7 gap-x-[20px]">

                    <div class="flex flex-col gap-[5px]">
                        <label class="text-[12px] text-[#000000]">Item</label>
                        <select
                            class="w-full px-[12px] py-[8px] text-[12px] text-[#A5A1A1] rounded-[5px]  bg-[#ffffff] border border-gray-200">
                            <option>Yarn Type</option>
                        </select>
                    </div>


                    <div class=" flex flex-col gap-[5px]">
                        <label class="text-[12px] text-[#000000] ">Category</label>
                        <select
                            class="w-full px-[12px] py-[8px] text-[12px] text-[#A5A1A1] rounded-[5px]  bg-[#ffffff] border border-gray-200">
                            <option>Yarn Type</option>
                        </select>
                    </div>

                    <div class=" flex flex-col gap-[5px]">
                        <label class="text-[12px] text-[#000000] ">Quality</label>
                        <select
                            class="w-full px-[12px] py-[8px] text-[12px] text-[#A5A1A1] rounded-[5px]  bg-[#ffffff] border border-gray-200">
                            <option>Yarn Type</option>
                        </select>
                    </div>

                    <div class=" flex flex-col gap-[5px]">
                        <label class="text-[12px] text-[#000000] ">Color</label>
                        <select
                            class="w-full px-[12px] py-[8px] text-[12px] text-[#A5A1A1] rounded-[5px]  bg-[#ffffff] border border-gray-200">
                            <option>Yarn Type</option>
                        </select>
                    </div>
                    <div class=" flex flex-col gap-[5px]">
                        <label class="text-[12px] text-[#000000] ">Item Code</label>
                        <input type="text" value="10521" disabled
                            class="w-full px-[12px] py-[8px] text-[12px] text-[#000] rounded-[5px]  bg-[#E7E7E7] border border-gray-200">
                    </div>


                    <div class="flex flex-col gap-[5px]">
                        <label class="text-[12px] text-[#000000] ">Quantity</label>
                        <input type="text" value="Name" disabled
                            class="w-full px-[12px] py-[8px] text-[12px] text-[#A5A1A1] rounded-[5px]  bg-[#ffffff] border border-gray-200">
                    </div>


                    <div class="flex gap-[10px] items-end">
                        <div class="flex flex-col gap-[5px] w-full">
                            <label class="text-[12px] text-[#000000] ">Unit</label>
                            <input type="text" value="Name" disabled
                                class="w-full px-[12px] py-[8px] text-[12px] text-[#A5A1A1] rounded-[5px]  bg-[#ffffff] border border-gray-200">
                        </div>

                        <button
                            class="w-[38px] h-[38px] mb-[2px] bg-[#BE202F] text-white rounded-full  hover:bg-[#c53e38] text-[20px] font-bold flex items-center justify-center">
                            +
                        </button>
                    </div>
                </div>
            </div>


            <div class="overflow-x-auto">
                <table class="w-full text-[12px] text-left border-collapse">
                    <thead>
                        <tr class="bg-[#F5F5F5] text-[#000000] uppercase">
                            <th class=" w-[5%] px-[10px] py-[8px]">#</th>
                            <th class="w-[60%] px-[10px] py-[8px]">ITEM</th>
                            <th class="w-[10%] px-[10px] py-[8px]">CODE</th>
                            <th class="w-[10%] px-[10px] py-[8px]">Quantity</th>
                            <th class="w-[10%] px-[10px] py-[8px]">UNIT</th>
                            <th class="w-[5%] px-[10px] py-[8px]"></th>
                        </tr>
                    </thead>
                    <tbody class="text-[#000]">
                        <tr class="bg-white rounded-[10px]  mt-[10px] ">
                            <td class="px-[10px] py-[8px]">1</td>
                            <td class="px-[10px] py-[8px]">Jacket</td>
                            <td class="px-[10px] py-[8px]">S100</td>
                            <td class="px-[10px] py-[8px]">10000</td>
                            <td class="px-[10px] py-[8px]">kg</td>

                            <td class="px-[10px] py-[8px] text-[#BE202F] "><i class="fa-solid fa-xmark"></i></td>
                        </tr>

                    </tbody>
                </table>
            </div>


        </div>
        <div class="flex justify-end mt-[20px]">
            <div class="flex flex-col items-end gap-[10px] w-[40%]">
                <div class="w-full">
                    <label class="text-[#000000] block mb-[6px]">Remarks</label>
                    <textarea class="w-full bg-[#FFFFFF] rounded-[5px] px-[2px] py-[6px]" rows="5" placeholder="Remarks"></textarea>
                </div>
                <button class="bg-[#BE202F] text-white px-[20px] py-[10px] rounded-[8px] text-[12px]">
                    Save
                </button>
            </div>
        </div>
    </div>

    </div>
@endsection
