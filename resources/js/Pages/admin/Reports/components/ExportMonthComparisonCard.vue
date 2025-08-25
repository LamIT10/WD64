<template>
  <div class="bg-white rounded-xl shadow p-4 max-w-lg mx-auto">
    <div class="flex items-center gap-8 flex-wrap sm:flex-nowrap">
      <!-- Bên trái: mô tả -->
      <div>
        <h2 class="text-lg font-semibold text-gray-800">
          So sánh đơn xuất tháng {{ current.month }}/{{ current.year }}
        </h2>
        <p class="text-sm text-gray-500">
          So với tháng {{ previous.month }}/{{ previous.year }}
        </p>
      </div>

      <!-- Bên phải: số liệu -->
      <div class="text-right ml-auto">
        <div class="text-2xl font-bold text-indigo-700">
          {{ current.total }} đơn
        </div>
        <div class="text-sm" :class="diff >= 0 ? 'text-green-600' : 'text-red-600'">
          <template v-if="percent !== null">
            {{ diff >= 0 ? '+' : '' }}{{ diff }} đơn
            ({{ diff >= 0 ? '+' : '' }}{{ percent }}%)
          </template>
          <template v-else>
            Không có dữ liệu tháng trước
          </template>
        </div>
      </div>
    </div>
  </div>
</template>




<script setup>
const props = defineProps({
  data: Object,
})

const current = props.data?.current || { month: '-', year: '-', total: 0 }
const previous = props.data?.previous || { month: '-', year: '-', total: 0 }
const diff = props.data?.diff ?? 0
const percent = props.data?.percent ?? null
</script>
