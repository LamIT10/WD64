@section('content')
<div class="px-[20px]">
      <div class="w-full bg-[#FFFFFF] rounded-[10px] px-[10px] py-[10px]">
        <div class="flex justify-between items-center">
            <h3 class="text-[14px] font-bold text-[#000000] leading-none">Order Details</h3>
            <button class="text-[#D93F21] px-[15px] py-[6px] rounded-[12px] text-[15px] leading-none">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>
    <div class="w-full px-[20px] py-[10px] my-[10px] bg-[#ffffff] rounded-[10px] text-[12px]">
        <div class="grid grid-cols-6 gap-x-[20px] gap-y-[10px] mb-[20px]">
            <div>
                <label class="text-[#000000] block mb-[4px]">Order No.</label>
                <input type="text" value="CO-00987"
                    class="w-full bg-[#E7E7E7] rounded-[5px] px-[10px] py-[6px] text-[#5B5656]" readonly />
            </div>
            <div>
                <label class="text-[#000000] block mb-[4px]">Customer</label>
                <select class="w-full bg-[#E7E7E7] rounded-[5px] px-[10px] py-[6px] text-[#5B5656]">
                    <option>Sameer</option>
                </select>
            </div>
            <div>
                <label class="text-[#000000] block mb-[4px]">Customer Code</label>
                <select class="w-full bg-[#F5F5F5] rounded-[5px] px-[10px] py-[6px] text-[#A5A1A1]">
                    <option>SP-0032</option>
                </select>
            </div>
            <div>
                <label class="text-[#000000]  block mb-[4px]">Destination</label>
                <input type="text" value="Italy"
                    class="w-full bg-[#F5F5F5] rounded-[5px] px-[10px] py-[6px] text-[#A5A1A1]" />
            </div>
            <div>
                <label class="text-[#000000] block mb-[4px]">Shipping Mode</label>
                <input type="text" value="Air"
                    class="w-full bg-[#F5F5F5] rounded-[5px] px-[10px] py-[6px] text-[#A5A1A1]" />
            </div>
            <div>
                <label class="text-[#000000]  block mb-[4px]">Status</label>
                <input type="text" value="Pending"
                    class="w-full bg-[#E7E7E7] rounded-[5px] px-[10px] py-[6px] text-[#5B5656]" readonly />
            </div>
            <div>
                <label class="text-[#000000]  block mb-[4px]">Ordered On</label>
                <input type="text" value="2058-03-07"
                    class="w-full bg-[#E7E7E7] rounded-[5px] px-[10px] py-[6px] text-[#5B5656]" />
            </div>
            <div>
                <label class="text-[#000000]  block mb-[4px]">Quality</label>
                <input type="text" value="100KNOTS"
                    class="w-full bg-[#F5F5F5] rounded-[5px] px-[10px] py-[6px] text-[#A5A1A1]" />
            </div>
            <div>
                <label class="text-[#000000]  block mb-[4px]">Wash / Non-Washed</label>
                <select class="w-full bg-[#F5F5F5] rounded-[5px] px-[10px] py-[6px] text-[#A5A1A1]">
                    <option>Washed</option>
                </select>
            </div>
            <div>
                <label class="text-[#000000] block mb-[4px]">Silk %</label>
                <input type="text" value="50"
                    class="w-full bg-[#F5F5F5] rounded-[5px] px-[10px] py-[6px] text-[#A5A1A1]" />
            </div>
            <div>
                <label class="text-[#000000]  block mb-[4px]">Delivery Date</label>
                <input type="date" value="2058-03-07"
                    class="w-full bg-[#F5F5F5] rounded-[5px] px-[10px] py-[6px] text-[#A5A1A1]" />
            </div>
            <div>
                <label class="text-[#000000]  block mb-[4px]">Carpet Type</label>
                <input type="text" value="Normal"
                    class="w-full bg-[#F5F5F5] rounded-[5px] px-[10px] py-[6px] text-[#A5A1A1]" />
            </div>
            <div>
                <label class="text-[#000000]  block mb-[4px]">Color</label>
                <input type="text" value="Greenish Green"
                    class="w-full bg-[#F5F5F5] rounded-[5px] px-[10px] py-[6px] text-[#A5A1A1]" />
            </div>
            <div>
                <label class="text-[#000000]  block mb-[4px]">Length</label>
                <input type="text" value="300"
                    class="w-full bg-[#F5F5F5] rounded-[5px] px-[10px] py-[6px] text-[#A5A1A1]" />
            </div>
            <div>
                <label class="text-[#000000]  block mb-[4px]">Breadth</label>
                <input type="text" value="160"
                    class="w-full bg-[#F5F5F5] rounded-[5px] px-[10px] py-[6px] text-[#A5A1A1]" />
            </div>
            <div>
                <label class="text-[#000000] block mb-[4px]">Sq. Meter</label>
                <input type="text" value="4.80"
                    class="w-full bg-[#F5F5F5] rounded-[5px] px-[10px] py-[6px] text-[#A5A1A1]" />
            </div>
            <div>
                <label class="text-[#000000]  block mb-[4px]">Quantity</label>
                <input type="text" value="5"
                    class="w-full bg-[#F5F5F5] rounded-[5px] px-[10px] py-[6px] text-[#A5A1A1]" />
            </div>
            <div>
                <label class="text-[#000000]  block mb-[4px]">Weaving Day</label>
                <input type="text" value="27"
                    class="w-full bg-[#F5F5F5] rounded-[5px] px-[10px] py-[6px] text-[#A5A1A1]" />
            </div>
        </div>

    </div>
    <div class="w-full px-[20px] py-[10px] my-[10px] bg-[#ffffff] rounded-[10px]  text-[12px]">


        <div class="mt-[30px]">
            <div class="w-full bg-[#F5F5F5] rounded-[10px] px-[10px] py-[10px]">
                <div class="flex justify-between items-center">
                    <h3 class="text-[14px] font-bold text-[#000000] leading-none">Raw Materials</h3>
                    <button class="text-white bg-[#BE202F] px-[15px] py-[6px] rounded-[12px] text-[12px] leading-none">
                        Return
                    </button>
                </div>
            </div>


        </div>
        <div class="w-[100%] mx-auto my-[10px] grid grid-cols-4 gap-x-[20px]">

            <div class="flex flex-col gap-[5px]">
                <label class="text-[12px] text-[#000000]  ">Item</label>
                <select
                    class="w-full px-[12px] py-[8px] text-[12px] text-[#A5A1A1] rounded-[5px] shadow-2xl bg-[#ffffff] border border-gray-200">
                    <option>Yarn Type</option>
                </select>
            </div>


            <div class="flex flex-col gap-[5px]">
                <label class="text-[12px] text-[#000000] ">Item Code</label>
                <input type="text" value="10521" disabled
                    class="w-full px-[12px] py-[8px] text-[12px] text-[#000] rounded-[5px] shadow-2xl bg-[#E7E7E7] border border-gray-200">
            </div>


            <div class="flex flex-col gap-[5px]">
                <label class="text-[12px] text-[#000000] ">Quantity</label>
                <input type="text" placeholder="Name"
                    class="w-full px-[12px] py-[8px] text-[12px] text-[#000] rounded-[5px]  bg-[#ffffff] border border-gray-200">
            </div>


            <div class="flex gap-[10px] items-end">
                <div class="flex flex-col gap-[5px] w-full">
                    <label class="text-[12px] text-[#A49E9E] font-medium ">Unit</label>
                    <input type="text" placeholder="Name"
                        class="w-full px-[12px] py-[8px] text-[12px] text-[#000] rounded-[5px]  bg-[#ffffff] border border-gray-200">
                </div>
                <button
                    class="w-[38px] h-[38px] mb-[2px] bg-[#BE202F] text-white rounded-full  hover:bg-[#c53e38] text-[20px] font-bold flex items-center justify-center">
                    +
                </button>

            </div>
        </div>


        <div class="overflow-x-auto">
            <table class="w-full text-[12px] text-left border-collapse">
                <thead>
                    <tr class="bg-[#F5F5F5] text-[#000000] uppercase">
                        <th class="px-[10px] py-[8px]">#</th>
                        <th class="w-[30%] px-[10px] py-[8px]">ITEM</th>
                        <th class="w-[15%] px-[10px] py-[8px]">CODE</th>
                        <th class=" w-[15%] px-[10px] py-[8px]">REQUIRED QTY</th>
                        <th class="w-[15%] px-[10px] py-[8px]">UNIT</th>
                        <th class=" w-[15%] px-[10px] py-[8px]">AVAILABLE</th>
                        <th class=" w-[15%] px-[10px] py-[8px]"></th>
                    </tr>
                </thead>
                <tbody class="text-[#000]">
                    <tr class="bg-white rounded-[10px] shadow-2xl mt-[10px]">
                        <td class="px-[10px] py-[8px]">1</td>
                        <td class="px-[10px] py-[8px]">Yarn-001</td>
                        <td class="px-[10px] py-[8px]">I-002</td>
                        <td class="px-[10px] py-[8px]">30</td>
                        <td class="px-[10px] py-[8px]">kg</td>
                        <td class="px-[10px] py-[8px] text-[#11B066] font-semibold">50 (available)</td>
                        <td class="px-[10px] py-[8px] text-[#BE202F] "><i class="fa-solid fa-xmark"></i></td>
                    </tr>
                    <tr class="bg-white rounded-[10px] shadow-2xl mt-[10px]">
                        <td class="px-[10px] py-[8px]">2</td>
                        <td class="px-[10px] py-[8px]">Blue Thread</td>
                        <td class="px-[10px] py-[8px]">I-012</td>
                        <td class="px-[10px] py-[8px]">50</td>
                        <td class="px-[10px] py-[8px]">kg</td>
                        <td class="px-[10px] py-[8px] text-[#11B066] font-semibold">50 (available)</td>
                        <td class="px-[10px] py-[8px] text-[#BE202F]"> <i class="fa-solid fa-xmark"></i></td>
                    </tr>
                    <tr class="bg-white rounded-[10px] shadow-2xl mt-[10px]">
                        <td class="px-[10px] py-[8px]">3</td>
                        <td class="px-[10px] py-[8px]">Nylon</td>
                        <td class="px-[10px] py-[8px]">I-009</td>
                        <td class="px-[10px] py-[8px]">80</td>
                        <td class="px-[10px] py-[8px]">kg</td>
                        <td class="px-[10px] py-[8px] text-[#BE202F] font-semibold">12 (insufficient)</td>
                        <td class="px-[10px] py-[8px] text-[#BE202F]"> <i class="fa-solid fa-xmark"></i></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex justify-end mt-[20px]">
            <div class="flex flex-col items-end gap-[10px] w-[40%]">
                <div class="w-full">
                    <label class="text-[#000000] block mb-[6px]">Remarks</label>
                    <textarea class="w-full bg-[#F5F5F5] rounded-[5px] px-[2px] py-[6px]" rows="5" placeholder="Remarks"></textarea>
                </div>
                <button class="bg-[#BE202F] text-white px-[20px] py-[10px] rounded-[8px] text-[12px]">
                    Weave
                </button>
            </div>
        </div>

    </div>
    </div>

</div>
 
@endsection
