<template>
  <AppLayout>
    <div class="min-h-screen bg-gray-50 p-6">
      <!-- Header Section -->
      <!-- <div class="flex justify-between items-center mb-8">
        <div>
          <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
          <p class="text-gray-500">Welcome back! Here's what's happening with your inventory today.</p>
        </div>
        <div class="flex items-center space-x-4">
          <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
            </span>
            <input type="text" class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Search...">
          </div>
          <button class="p-2 bg-white rounded-lg shadow-sm border border-gray-200">
            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
          </button>
        </div>
      </div>   -->

      <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-xl">
        <!-- Header section -->
        <div class="flex items-center justify-between mb-6">
          <div>
            <h4 class="text-xl font-bold text-gray-800">Thống kê đơn xuất</h4>
            <p class="text-sm text-gray-500">Tổng quan tình hình xuất hàng trong tháng</p>
          </div>
          <div class="flex items-center justify-between p-2  rounded-lg shadow">

            <!-- Phần chọn ngày bắt đầu và kết thúc -->
            <div class="flex items-center space-x-4">
              <!-- Ô ngày bắt đầu -->
              <div class="flex items-center space-x-2">
                <label for="start-date" class="text-xs text-indigo-600">Từ ngày</label>
                <input type="text" id="start-date"
                  class="px-2 py-1 text-xs border border-indigo-200 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-400 text-indigo-700 date-picker"
                  v-model="dataFilterDateSaleOrder.sub_from_date" v-date-picker @change="handleFilterPurchase">
              </div>

              <!-- Ô ngày kết thúc -->
              <div class="flex items-center space-x-2">
                <label for="end-date" class="text-xs text-indigo-600">Đến ngày</label>
                <input type="text" id="end-date"
                  class="px-2 py-1 text-xs border border-indigo-200 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-400 text-indigo-700 date-picker"
                  v-model="dataFilterDateSaleOrder.sub_to_date" v-date-picker @change="handleFilterPurchase">
              </div>

              <!-- Nút reset -->
              <button @click="resetDateFilterSaleOrder"
                class="p-1 text-indigo-400 hover:text-indigo-600 transition-colors" title="Reset">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Stats cards grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
          <!-- Tổng đơn hàng trong tháng -->
          <div class="bg-white p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng xuất đơn hàng</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{
                  data.statistical_sale_order.count_sale_order_in_month }}
                </p>
                <p class="text-xs text-gray-400 mt-1">Trong tháng</p>
              </div>
              <div class="bg-blue-50 p-3 rounded-lg">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                  </path>
                </svg>
              </div>
            </div>
          </div>

          <!-- Đơn hàng đang chờ xử lý -->
          <div class="bg-white p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Đang chuẩn bị</p>
                <p class="text-2xl font-bold text-yellow-600 mt-1">{{
                  data.statistical_sale_order.count_sale_order_in_month_pending }}</p>
                <p class="text-xs text-gray-400 mt-1">Chờ xử lý</p>
              </div>
              <div class="bg-yellow-50 p-3 rounded-lg">
                <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
            </div>
          </div>

          <!-- Đơn hàng đã nhận -->
          <div class="bg-white p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow border-l-4 border-green-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Hoàn thành</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{
                  data.statistical_sale_order.count_sale_order_in_month_shipped }}</p>
                <p class="text-xs text-gray-400 mt-1">Đã giao</p>
              </div>
              <div class="bg-green-50 p-3 rounded-lg">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
              </div>
            </div>
          </div>

          <!-- Đơn hàng huỷ -->
          <div class="bg-white p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow border-l-4 border-red-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Đơn huỷ</p>
                <p class="text-2xl font-bold text-red-600 mt-1">{{
                  data.statistical_sale_order.count_sale_order_in_month_closed }}</p>
                <p class="text-xs text-gray-400 mt-1">Không thực hiện</p>
              </div>
              <div class="bg-red-50 p-3 rounded-lg">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                  </path>
                </svg>
              </div>
            </div>
          </div>

          <!-- Tổng giá trị đơn hàng -->
          <div class="bg-white p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng giá trị</p>
                <p class="text-2xl font-bold text-indigo-600 mt-1">{{
                  data.statistical_sale_order.sum_value_sale_order_in_month }}</p>
                <p class="text-xs text-gray-400 mt-1">Tổng thu nhập</p>
              </div>
              <div class="bg-indigo-50 p-3 rounded-lg">
                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                  </path>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-xl">
        <!-- Header section -->
        <div class="flex items-center justify-between mb-6">
          <div>
            <h4 class="text-xl font-bold text-gray-800">Thống kê phiếu nhập kho hàng</h4>
            <p class="text-sm text-gray-500">Tổng quan tình hình nhập hàng trong tháng</p>
          </div>
          <div class="flex items-center justify-between p-2  rounded-lg shadow">

            <!-- Phần chọn ngày bắt đầu và kết thúc -->
            <div class="flex items-center space-x-4">
              <!-- Ô ngày bắt đầu -->
              <div class="flex items-center space-x-2">
                <label for="start-date" class="text-xs text-indigo-600">Từ ngày</label>
                <input type="text" id="start-date"
                  class="px-2 py-1 text-xs border border-indigo-200 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-400 text-indigo-700 date-picker"
                  v-model="dataFilterDatePurchase.sub_from_date" v-date-picker @change="handleFilterPurchase">
              </div>

              <!-- Ô ngày kết thúc -->
              <div class="flex items-center space-x-2">
                <label for="end-date" class="text-xs text-indigo-600">Đến ngày</label>
                <input type="text" id="end-date"
                  class="px-2 py-1 text-xs border border-indigo-200 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-400 text-indigo-700 date-picker"
                  v-model="dataFilterDatePurchase.sub_to_date" v-date-picker @change="handleFilterPurchase">
              </div>
              <!-- Nút reset -->
              <button @click="resetDateFilterPurchase"
                class="p-1 text-indigo-400 hover:text-indigo-600 transition-colors" title="Reset">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Stats cards grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
          <!-- Tổng đơn hàng trong tháng -->
          <div class="bg-white p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng phiếu nhập</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ data.statistical_good_receipt.count_good_receipt_in_month
                }}
                </p>
                <p class="text-xs text-gray-400 mt-1">Trong tháng</p>
              </div>
              <div class="bg-blue-50 p-3 rounded-lg">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                  </path>
                </svg>
              </div>
            </div>
          </div>

          <!-- Đơn hàng đang chờ xử lý -->
          <div class="bg-white p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Chờ duyệt</p>
                <p class="text-2xl font-bold text-yellow-600 mt-1">{{
                  data.statistical_good_receipt.count_good_receipt_in_month_pending }}</p>
                <p class="text-xs text-gray-400 mt-1">Chờ xử lý</p>
              </div>
              <div class="bg-yellow-50 p-3 rounded-lg">
                <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
            </div>
          </div>

          <!-- Đơn hàng đã nhận -->
          <div class="bg-white p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow border-l-4 border-green-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Hoàn thành</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{
                  data.statistical_good_receipt.count_good_receipt_in_month_received }}</p>
                <p class="text-xs text-gray-400 mt-1">Đã nhập kho</p>
              </div>
              <div class="bg-green-50 p-3 rounded-lg">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
              </div>
            </div>
          </div>

          <!-- Đơn hàng huỷ -->
          <div class="bg-white p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow border-l-4 border-red-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Đơn huỷ</p>
                <p class="text-2xl font-bold text-red-600 mt-1">{{
                  data.statistical_good_receipt.count_good_receipt_in_month_closed }}</p>
                <p class="text-xs text-gray-400 mt-1">Không thực hiện</p>
              </div>
              <div class="bg-red-50 p-3 rounded-lg">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                  </path>
                </svg>
              </div>
            </div>
          </div>

          <!-- Tổng giá trị đơn hàng -->
          <div class="bg-white p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng giá trị</p>
                <p class="text-2xl font-bold text-indigo-600 mt-1">{{
                  data.statistical_good_receipt.sum_value_good_receipt_in_month }}</p>
                <p class="text-xs text-gray-400 mt-1">Tổng chi phí</p>
              </div>
              <div class="bg-indigo-50 p-3 rounded-lg">
                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                  </path>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="flex">
        <ChangePurchaseSevenDayAgo :purchaseChangeInSevenDay="data.statistical_good_receipt.good_receipt_change_in_seven_day"
          :chartTitle="'Đơn nhập hàng trong 7 ngày'" />
      <ChangePurchaseSevenDayAgo
          :purchaseChangeInSevenDay="data.statistical_sale_order.sale_order_change_in_seven_day"
          :chartTitle="'Đơn xuất hàng trong 7 ngày'" />
      </div>
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <Link :href="route('admin.products.index')"
          class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-start">
        <div class="p-3 bg-indigo-50 rounded-lg mr-4">
          <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
          </svg>
        </div>
        <div>
          <p class="text-sm font-medium text-gray-500">Tổng loại sản phẩm trong kho</p>
          <p class="text-2xl font-bold text-gray-800 mt-1">{{ data.count_product }}</p>
          <!-- <p class="text-xs text-green-500 mt-1">+12% from last month</p> -->
        </div>
        </Link>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-start">
          <div class="p-3 bg-red-50 rounded-lg mr-4">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
              </path>
            </svg>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-500">Sản phẩm sắp hết hàng</p>
            <p class="text-2xl font-bold text-red-600 mt-1">{{ data.product_is_out_of_stock }}</p>
            <p class="text-xs text-red-500 mt-1">Cần nhập</p>
          </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-start">
          <div class="p-3 bg-blue-50 rounded-lg mr-4">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
              </path>
            </svg>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-500">Pending Orders</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">12</p>
            <p class="text-xs text-yellow-500 mt-1">+2 from yesterday</p>
          </div>
        </div>



        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-start">
          <div class="p-3 bg-green-50 rounded-lg mr-4">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
              </path>
            </svg>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-500">Active Customers</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">27</p>
            <p class="text-xs text-green-500 mt-1">+3 new this week</p>
          </div>
        </div>
      </div>

      <!-- Net Revenue Chart -->
      <RevenueChart :data="data.net_revenue" />

      <!-- Top 10 Charts Row -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 mt-20">
        <!-- Top 10 Best Selling Products -->

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Top 10 Sản phẩm bán chạy</h3>
            <select v-model="selectedPeriod"
              class="text-sm border border-gray-200 rounded-lg px-3 py-1 focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
              <option value="week">Tuần này</option>
              <option value="month">Tháng này</option>
              <option value="year">Năm này</option>
            </select>
          </div>

          <div class="h-80 overflow-y-auto pr-2">
            <div class="text-center w-full px-2" v-if="topProducts.length">
              <div v-for="(item, index) in topProducts" :key="item.variant_id" class="mb-3">
                <div class="flex items-center justify-between mb-1">
                  <span class="text-sm font-medium text-gray-700">{{ index + 1 }}. {{ item.full_variant_name }}</span>
                  <span class="text-sm font-bold text-indigo-700">{{ formatNumber(item.total_quantity) }} {{
                    item.base_unit_name }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                  <div class="bg-indigo-600 h-2.5 rounded-full"
                    :style="{ width: getBarWidth(item.total_quantity) + '%' }"></div>
                </div>
              </div>
              <p class="text-xs text-gray-500 mt-2">Hiển thị top {{ topProducts.length }} trong số 10 sản phẩm</p>
            </div>
            <div v-else class="text-sm text-gray-500 text-center">Không có dữ liệu</div>
          </div>
        </div>



        <!-- Top 10 Customers -->

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Top 10 Khách hàng</h3>
            <select v-model="selectedCustomerPeriod"
              class="text-sm border border-gray-200 rounded-lg px-3 py-1 focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
              <option value="week">Tuần này</option>
              <option value="month" selected>Tháng này</option>
              <option value="year">Năm này</option>
            </select>
          </div>

          <div class="h-80 overflow-y-auto pr-2">
            <div class="text-center w-full px-2" v-if="topCustomers.length">
              <div v-for="(item, index) in topCustomers" :key="item.customer_id" class="mb-3">
                <div class="flex items-center justify-between mb-1">
                  <span class="text-sm font-medium text-gray-700">{{ index + 1 }}. {{ item.customer_name }}</span>
                  <span class="text-sm font-bold text-green-700">{{ formatCurrency(item.total_spent) }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                  <div class="bg-green-600 h-2.5 rounded-full"
                    :style="{ width: getBarWidthCustomer(item.total_spent) + '%' }">
                  </div>
                </div>
              </div>
              <p class="text-xs text-gray-500 mt-2">Hiển thị top {{ topCustomers.length }} trong số 10 khách hàng</p>
            </div>
            <div v-else class="text-sm text-gray-500 text-center">Không có dữ liệu</div>
          </div>
        </div>


      </div>

      <!-- Inventory Charts and Low Stock Section -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Inventory Chart -->
        <InventoryByPaperChart :data="data.inventory_by_paper_type" />


        <!-- Low Stock Items -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Sản phẩm tồn kho thấp</h3>
            <button class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Xem tất cả</button>
          </div>

          <div class="space-y-4">
            <div v-for="item in data.low_stock_items" :key="item.id" class="flex items-center p-3 rounded-lg" :class="item.quantity_on_hand <= 5
              ? 'bg-red-50 text-red-600'
              : item.quantity_on_hand <= 10
                ? 'bg-orange-50 text-orange-600'
                : 'bg-gray-100 text-gray-500'">
              <div class="bg-white p-2 rounded-lg mr-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div>
                <p class="font-medium text-gray-800">{{ item.product_name }} - {{ item.code }}</p>
                <p class="text-sm">
                 {{ `Chỉ còn ${item.quantity_on_hand} ${item.unit ?? ''}` }}
                </p>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Recent Activity -->
      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-lg font-semibold text-gray-800">Các hoạt động gần đây</h3>
          <button class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Xem tất cả</button>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead>
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nhân viên
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vai trò</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hoạt động</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                      <span class="text-indigo-600 font-medium">NA</span>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">Nguyễn Văn A</div>
                      <div class="text-sm text-gray-500">nva@suvan.com</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Quản lý kho</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Đã xử lý 5 đơn hàng</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10 phút trước</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Đang hoạt động</span>
                </td>
              </tr>
              <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                      <span class="text-blue-600 font-medium">TB</span>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">Trần Thị B</div>
                      <div class="text-sm text-gray-500">ttb@suvan.com</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Kế toán</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Cập nhật hồ sơ tồn kho</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">30 phút trước</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Đang hoạt động</span>
                </td>
              </tr>
              <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 bg-purple-100 rounded-full flex items-center justify-center">
                      <span class="text-purple-600 font-medium">PC</span>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">Phạm Văn C</div>
                      <div class="text-sm text-gray-500">pvc@suvan.com</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Nhân viên giao hàng</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Đã hoàn thành 2 đơn hàng</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1 giờ trước</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Đang giao hàng</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from './admin/Layouts/AppLayout.vue';
import { route } from 'ziggy-js';
import { ref, computed } from 'vue';
import ChangePurchaseSevenDayAgo from './components/ChangePurchaseSevenDayAgo.vue';
import RevenueChart from './components/RevenueChart.vue';
import { reactive } from 'vue';
import InventoryByPaperChart from './components/InventoryByPaperChart.vue';
const { data } = defineProps({
  data: Object,
});
const formatDateForSubmit = (dateString) => {
  if (!dateString) return '';
  const [day, month, year] = dateString.split('/');
  return `${year}-${month}-${day}`;
};
const formatDateForDisplay = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  if (isNaN(date.getTime())) return '';
  return `${String(date.getDate()).padStart(2, '0')}/${String(date.getMonth() + 1).padStart(2, '0')}/${date.getFullYear()}`;
};
const url = new URL(window.location.href);
// Lấy tất cả tham số từ query string
const params = new URLSearchParams(url.search);
const queryData = reactive({
  fromDatePurchase: "",
  toDatePurchase: "",
  fromDateSaleOrder: "",
  toDateSaleOrder: "",
});

params.forEach((value, key) => {
  if (key == "perPage") {
    formPerpage.perPage = value;
  } else {
    queryData[key] = value;
  }
});


const formFilterDatePurchaseAndSaleOrder = useForm({
  fromDatePurchase: queryData.fromDatePurchase ?? "",
  toDatePurchase: queryData.toDatePurchase ?? "",
  toDateSaleOrder: queryData.toDateSaleOrder ?? "",
  fromDateSaleOrder: queryData.fromDateSaleOrder ?? "",
});
const dataFilterDatePurchase = reactive({
  sub_from_date: formatDateForDisplay(formFilterDatePurchaseAndSaleOrder.fromDatePurchase) ?? "",
  sub_to_date: formatDateForDisplay(formFilterDatePurchaseAndSaleOrder.toDatePurchase) ?? "",
});
const dataFilterDateSaleOrder = reactive({
  sub_from_date: formatDateForDisplay(formFilterDatePurchaseAndSaleOrder.fromDateSaleOrder) ?? "",
  sub_to_date: formatDateForDisplay(formFilterDatePurchaseAndSaleOrder.toDateSaleOrder) ?? "",
})
console.log(data);

const selectedPeriod = ref('month')

const topProducts = computed(() => {
  return data?.top_10_product_variants?.[selectedPeriod.value] ?? []
})

const maxQuantity = computed(() => {
  if (!topProducts.value.length) return 1
  return Math.max(...topProducts.value.map(p => p.total_quantity))
})

const getBarWidth = (quantity) => {
  return Math.round((quantity / maxQuantity.value) * 100)
}

const formatNumber = (val) => {
  return new Intl.NumberFormat().format(val)
}
const selectedCustomerPeriod = ref('month')

const topCustomers = computed(() => {
  return data?.top_10_customers?.[selectedCustomerPeriod.value] ?? []
})

const maxTotalSpent = computed(() => {
  if (!topCustomers.value.length) return 1
  return Math.max(...topCustomers.value.map(c => Number(c.total_spent) || 0))
})

const getBarWidthCustomer = (value) => {
  const numericValue = Number(value) || 0
  const max = Number(maxTotalSpent.value) || 1
  return Math.round((numericValue / max) * 100)
}

const formatCurrency = (val) => {
  const number = Number(val) || 0
  return '₫' + (number / 1_000_000).toFixed(1) + 'M'
}

const handleFilterPurchase = () => {
  formFilterDatePurchaseAndSaleOrder.toDatePurchase = formatDateForSubmit(dataFilterDatePurchase.sub_to_date);
  formFilterDatePurchaseAndSaleOrder.fromDatePurchase = formatDateForSubmit(dataFilterDatePurchase.sub_from_date);
  formFilterDatePurchaseAndSaleOrder.toDateSaleOrder = formatDateForSubmit(dataFilterDateSaleOrder.sub_to_date);
  formFilterDatePurchaseAndSaleOrder.fromDateSaleOrder = formatDateForSubmit(dataFilterDateSaleOrder.sub_from_date);
  formFilterDatePurchaseAndSaleOrder.get(route("admin.dashboard"));
}

const resetDateFilterSaleOrder = () => {
  dataFilterDateSaleOrder.sub_from_date = "",
    dataFilterDateSaleOrder.sub_to_date = "",
    handleFilterPurchase();
}
const resetDateFilterPurchase = () => {
  dataFilterDatePurchase.sub_from_date = "",
    dataFilterDatePurchase.sub_to_date = "",
    handleFilterPurchase();
}


</script>