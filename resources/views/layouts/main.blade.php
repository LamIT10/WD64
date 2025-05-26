@extends('app')
    @section('content')
      <div class="w-[769px] min-h-[460px] gap-y-[20px] flex flex-wrap justify-end px-[8px] py-[13px]  mx-auto my-[20px] shadow rounded-[10px]">
        <div class="w-full h-[10%] items-center flex justify-between px-[20px] py-[10px] rounded-[10px] bg-[#fff]  shadow ">
            <p class="text-[14px] text-[#000]">Return</p>
            <i class="fa-solid fa-xmark text-[12px] text-[#D93F21]"></i>
        </div>
        <div class="w-full grid grid-cols-3 gap-[20px] p-[20px] rounded-[10px] bg-[#fff] shadow text-[12px]">
         
               
          
          
          
          
        
           
          
            <div class="h-[65px] col-span-3">
                <label for="">Return Quantity</label>
                <input class="w-full bg-[#fff] border-[1px] border-[#D9D9D9] p-[10px] rounded-[5px] placeholder-[#A5A1A1] mt-[10px]" placeholder="Kg" type="text">
            </div>
            <div class="relative h-[65px] col-span-3">
                <label for="">Supplier</label>
                <select
                  class="w-full border border-[#D9D9D9] p-[10px] pr-[36px] rounded-[5px] text-[#A5A1A1] appearance-none mt-[10px]">
                  <option selected>Select Supplier</option>
                </select>
                <div class="pointer-events-none absolute right-[12px] mt-[10px] top-[58%] transform -translate-y-1/2">
                  <svg class="w-4 h-4 text-[#A5A1A1]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </div>
              </div> 
              <div class="grid grid-cols-20 col-span-3">
              <div class="h-[240px] col-span-9 border-[1px] border-[#D9D9D9] rounded-[5px] p-[20px]">
                <div class="flex items-center mb-4">
                    <input id="default-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="default-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Default checkbox</label>
                </div>
                <div class="flex items-center">
                    <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Checked state</label>
                </div>
            </div>
            <div class="h-[240px] col-span-1 flex justify-center items-center">
                <i class="fa-solid fa-repeat text-[#000] text-[20px]"></i>
            </div>
            <div class="h-[240px] col-span-9 border-[1px] border-[#D9D9D9] rounded-[5px] p-[20px]">
                <div class="flex items-center mb-4">
                    <input id="default-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="default-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Default checkbox</label>
                </div>
                <div class="flex items-center">
                    <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Checked state</label>
                </div>
            </div>
        </div>
        </div>
        <div class="flex items-center mb-4 h-[5%] w-full justify-center">
            <input  id="disabled-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="disabled-checkbox" class="ms-2 text-sm font-medium">Active</label>
        </div>
        <button class="h-[10%] py-[11px] pl-[20px] pr-[24px] text-[#fff] text-[14px] font-semibold text-center rounded-[10px] bg-[#BE202F] mt-[10px] self-end">Return</button>
        </div>

    @endsection
