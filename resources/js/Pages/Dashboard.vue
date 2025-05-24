<template>
  <AppLayout>
    <div class="w-full h-auto my-[10px] p-[20px] bg-[#f5f7fa]">
      <div class="w-[90%] mx-auto bg-[#ffffff] rounded-[10px] shadow p-[20px] mb-[30px]">
        <table class="w-full text-[12px] text-[#000]">
          <thead>
            <tr class="bg-[#f9f9f9] text-[#A49E9E] uppercase">
              <th class="p-[10px] text-left">#</th>
              <th class="p-[10px] text-left">Item Code</th>
              <th class="p-[10px] text-left">Item</th>
              <th class="p-[10px] text-left">Item Type</th>
              <th class="p-[10px] text-left">Customer Order</th>
              <th class="p-[10px] text-left">Stock</th>
              <th class="p-[10px] text-left">Qty</th>
              <th class="w-[15%] text-center">Status</th>
              <th class="p-[10px] text-left">Request On</th>
              <th class="p-[10px] text-left">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="p-[10px]">1</td>
              <td class="p-[10px]">FG-SILK101474</td>
              <td class="p-[10px]">Yarn</td>
              <td class="p-[10px]">RAW Materials</td>
              <td class="p-[10px]">CO-0008</td>
              <td class="p-[10px]">50</td>
              <td class="p-[10px]">50</td>
              <td class="w-[15%] text-center">
                <div
                  class="w-full h-[22px] rounded-[16px] bg-[#F6E9E4] flex justify-center items-center gap-[10px] font-bold">
                  <i class="fa-solid fa-circle text-[#E54C42] text-[8px]"></i>
                  <p class="text-[#E54C42] text-[12px]">Out of stock</p>
                </div>
              </td>
              <td class="p-[10px]">2058-03-07</td>
              <td class="p-[10px]">
                <button class="text-[#2A66FF] font-bold">Purchase</button>
              </td>
            </tr>
            <tr>
              <td class="p-[10px]">2</td>
              <td class="p-[10px]">FG-SILK101474</td>
              <td class="p-[10px]">Nylon</td>
              <td class="p-[10px]">RAW Materials</td>
              <td class="p-[10px]">CO-0008</td>
              <td class="p-[10px]">50</td>
              <td class="p-[10px]">50</td>
              <td class="w-[15%] text-center">
                <div
                  class="w-full h-[22px] rounded-[16px] bg-[#E6F7EC] flex justify-center items-center gap-[10px] font-bold">
                  <i class="fa-solid fa-circle text-[#11B066] text-[8px]"></i>
                  <p class="text-[#11B066] text-[12px]">Available</p>
                </div>
              </td>
              <td class="p-[10px]">2058-03-07</td>
              <td class="p-[10px]">
                <button class="text-[#4DB163] font-bold">Dispatch</button>
              </td>
            </tr>
            <tr>
              <td class="p-[10px]">3</td>
              <td class="p-[10px]">FG-SILK101474</td>
              <td class="p-[10px]">Blue Thread</td>
              <td class="p-[10px]">DYED</td>
              <td class="p-[10px]">CO-0008</td>
              <td class="p-[10px]">50</td>
              <td class="p-[10px]">50</td>
              <td class="w-[15%] text-center">
                <div
                  class="w-full h-[22px] rounded-[16px] bg-[#F6E9E4] flex justify-center items-center gap-[10px] font-bold">
                  <i class="fa-solid fa-circle text-[#E54C42] text-[8px]"></i>
                  <p class="text-[#E54C42] text-[12px]">Out of stock</p>
                </div>
              </td>
              <td class="p-[10px]">2058-03-07</td>
              <td class="p-[10px]">
                <button class="text-[#BE202F] font-bold">Dye</button>
              </td>
            </tr>
            <tr>
              <td class="p-[10px]">4</td>
              <td class="p-[10px]">FG-SILK101474</td>
              <td class="p-[10px]">Yellow Thread</td>
              <td class="p-[10px]">DYED</td>
              <td class="p-[10px]">CO-0008</td>
              <td class="p-[10px]">50</td>
              <td class="p-[10px]">50</td>
              <td class="w-[15%] text-center">
                <div
                  class="w-full h-[22px] rounded-[16px] bg-[#E6F7EC] flex justify-center items-center gap-[10px] font-bold">
                  <i class="fa-solid fa-circle text-[#11B066] text-[8px]"></i>
                  <p class="text-[#11B066] text-[12px]">Available</p>
                </div>
              </td>
              <td class="p-[10px]">2058-03-07</td>
              <td class="p-[10px]">
                <button class="text-[#4DB163] font-bold">Dispatch</button>
              </td>
            </tr>
          </tbody>
        </table>
        <button
          class="fixed bottom-[20px] right-[20px] w-[50px] h-[50px] bg-[#BE202F] text-white rounded-full flex justify-center items-center text-[24px] shadow-lg">
          <i class="fa-solid fa-plus"></i>
        </button>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from './admin/Layouts/AppLayout.vue';


</script>

<style lang="scss" scoped></style>