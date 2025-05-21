@section('content')
    <div class="w-full h-auto my-[10px] p-[20px] bg-[#f5f7fa]">
        <div class="w-[90%] mx-auto bg-[#ffffff] rounded-[10px] p-[15px] mb-[7px] relative">
            <h2 class="text-[14px] font-bold text-[#000]">Customer Order Details</h2> 
            <button class="absolute top-[15px] right-[20px] text-[#E54C42]">
                <i class="fa-solid fa-times"></i>
            </button>   
        </div>
        <div class="w-[90%] mx-auto bg-[#ffffff] rounded-[10px] shadow-2xl p-[20px] mb-[30px]">
            <div class="grid grid-cols-4 gap-[15px]">
                <div>
                    <label class="block text-[12px] text-[#000] mb-[5px]">Customer</label>
                    <select class="w-full h-[40px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#f9f9f9]">
                        <option>Semper</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[12px] text-[#000] mb-[5px]">Customer Code</label>
                    <select class="w-full h-[40px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#f9f9f9]">
                        <option>SP-0032</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[12px] text-[#000] mb-[5px]">Destination</label>
                    <input type="text" value="Italy" class="w-full h-[40px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#f9f9f9]" readonly  placeholder="Italy"/>
                </div>
                <div>
                    <label class="block text-[12px] text-[#000] mb-[5px]">Shipping Mode</label>
                    <select class="w-full h-[40px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#f9f9f9]">
                        <option>Air</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[12px] text-[#000] mb-[5px]">Carpet Name</label>
                    <input type="text" value="Loop Carpet" class="w-full h-[40px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#f9f9f9]" />
                </div>
                <div>
                    <label class="block text-[12px] text-[#000] mb-[5px]">Carpet Type</label>
                    <input type="text" value="Normal" class="w-full h-[40px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#f9f9f9]" />
                </div>
                <div>
                    <label class="block text-[12px] text-[#000] mb-[5px]">Quality</label>
                    <input type="text" value="100KNOTS" class="w-full h-[40px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#f9f9f9]" />
                </div>
                <div>
                    <label class="block text-[12px] text-[#000] mb-[5px]">Wash / Non-Washed</label>
                    <select class="w-full h-[40px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#f9f9f9]">
                        <option>Washed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[12px] text-[#000] mb-[5px]">Silk %</label>
                    <input type="text" value="60" class="w-full h-[40px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#f9f9f9]" />
                </div>
                <div>
                    <label class="block text-[12px] text-[#000] mb-[5px]">Color</label>
                    <input type="text" value="Greenish Green" class="w-full h-[40px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#f9f9f9]" />
                </div>
                <div>
                    <label class="block text-[12px] text-[#000] mb-[5px]">Length</label>
                    <input type="text" value="300" class="w-full h-[40px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#f9f9f9]" />
                </div>
                <div>
                    <label class="block text-[12px] text-[#000] mb-[5px]">Breadth</label>
                    <input type="text" value="160" class="w-full h-[40px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#f9f9f9]" />
                </div>
                <div>
                    <label class="block text-[12px] text-[#000] mb-[5px]">Delivery Date</label>
                    <input type="text" value="2058-03-07" class="w-full h-[40px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#f9f9f9]" />
                </div>
                <div>
                    <label class="block text-[12px] text-[#000] mb-[5px]">Rate Per Sq. Meter</label>
                    <input type="text" value="4.80" class="w-full h-[40px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#f9f9f9]" />
                </div>
                <div>
                    <label class="block text-[12px] text-[#000] mb-[5px]">Quantity</label>
                    <input type="text" value="5" class="w-full h-[40px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#f9f9f9]" />
                </div>
                <div>
                    <label class="block text-[12px] text-[#000] mb-[5px]">Weaving Day</label>
                    <input type="text" value="27" class="w-full h-[40px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#f9f9f9]" />
                </div>
            </div>
        </div>

        <div class="w-[90%] mx-auto bg-[#ffffff] rounded-[10px] p-[15px] mb-[7px] relative">
            <h2 class="text-[14px] font-bold text-[#000]">Raw Materials</h2>
            <button class="absolute top-[15px] right-[20px] text-[#E54C42]">
                <i class="fa-solid fa-times"></i>
            </button>   
        </div>
        <div class="w-[90%] mx-auto bg-[#ffffff] rounded-[10px] shadow-2xl p-[20px] mb-[30px]">
            <div class="flex items-center gap-[15px] mb-[15px]">
                <div class="w-[25%]">
                    <label class="block text-[12px] text-[#000] mb-[5px]">Item</label>
                    <select class="w-full h-[40px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#f9f9f9]">
                        <option>Yarn Type</option>
                    </select>
                </div>
                <div class="w-[25%]">
                    <label class="block text-[12px] text-[#000] mb-[5px]">Item Code</label>
                    <input type="text" value="10521" class="w-full h-[40px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#f9f9f9]" readonly />
                </div>
                <div class="w-[25%]">
                    <label class="block text-[12px] text-[#000] mb-[5px]">Quantity</label>
                    <input type="text" placeholder="Enter quantity" class="w-full h-[40px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#f9f9f9]" />
                </div>
                <div class="w-[25%]">
                    <label class="block text-[12px] text-[#000] mb-[5px]">Unit</label>
                    <input type="text" value="Name" class="w-full h-[40px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#f9f9f9]" readonly />
                </div>
                <div class="w-[5%] flex items-end">
                    <button class="w-[40px] h-[40px] bg-[#BE202F] text-[#fff] rounded-[20px] flex justify-center items-center">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>
            <table class="w-full text-[12px] text-[#000]">
                <thead>
                    <tr class="bg-[#f9f9f9] text-[#A49E9E] uppercase">
                        <th class="p-[10px] text-left">#</th>
                        <th class="p-[10px] text-left">Item</th>
                        <th class="p-[10px] text-left">Code</th>
                        <th class="p-[10px] text-left">Required Qty</th>
                        <th class="p-[10px] text-left">Unit</th>
                        <th class="p-[10px] text-left">Available</th>
                        <th class="p-[10px] text-left"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="p-[10px]">1</td>
                        <td class="p-[10px]">YARN-001</td>
                        <td class="p-[10px]">I-002</td>
                        <td class="p-[10px]">30</td>
                        <td class="p-[10px]">kg</td>
                        <td class="p-[10px] text-[#11B066]">50 (Available)</td>
                        <td class="p-[10px] text-right">
                            <button class="text-[#E54C42]"><i class="fa-solid fa-times"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-[10px]">2</td>
                        <td class="p-[10px]">BLUE THREAD</td>
                        <td class="p-[10px]">I-012</td>
                        <td class="p-[10px]">50</td>
                        <td class="p-[10px]">kg</td>
                        <td class="p-[10px] text-[#11B066]">50 (Available)</td>
                        <td class="p-[10px] text-right">
                            <button class="text-[#E54C42]"><i class="fa-solid fa-times"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-[10px]">3</td>
                        <td class="p-[10px]">NYLON</td>
                        <td class="p-[10px]">I-009</td>
                        <td class="p-[10px]">80</td>
                        <td class="p-[10px]">kg</td>
                        <td class="p-[10px] text-[#E54C42]">12 (Insufficient)</td>
                        <td class="p-[10px] text-right">
                            <button class="text-[#E54C42]"><i class="fa-solid fa-times"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="w-[90%] mx-auto flex justify-between items-center">
            <div class="w-[70%]">
                <label class="block text-[12px] text-[#000] mb-[5px]">Remarks</label>
                <textarea class="w-full h-[80px] rounded-[5px] border border-[#ddd] text-[12px] text-[#000] p-[10px] bg-[#f9f9f9]" placeholder="Remarks"></textarea>
            </div>
            <div class="flex gap-[10px]">
                <button class="w-[100px] h-[40px] bg-[#BE202F] text-[#fff] rounded-[5px] flex justify-center items-center font-bold">Return</button>
                <button class="w-[100px] h-[40px] bg-[#BE202F] text-[#fff] rounded-[5px] flex justify-center items-center font-bold">Save</button>
            </div>
        </div>
    </div>
@endsection  