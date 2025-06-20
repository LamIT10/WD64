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
          <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500">Tháng hiện tại</span>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </div>
        </div>

        <!-- Stats cards grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
          <!-- Tổng đơn hàng trong tháng -->
          <div class="bg-white p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng xuất đơn hàng</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ data.statistical_sale_order.count_sale_order_in_month }}
                </p>
                <!-- <p class="text-xs text-gray-400 mt-1">Trong tháng</p> -->
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
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
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
            <h4 class="text-xl font-bold text-gray-800">Thống kê đơn nhập kho hàng</h4>
            <p class="text-sm text-gray-500">Tổng quan tình hình nhập hàng trong tháng</p>
          </div>
          <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500">Tháng hiện tại</span>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </div>
        </div>

        <!-- Stats cards grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
          <!-- Tổng đơn hàng trong tháng -->
          <div class="bg-white p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng đơn hàng</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ data.statistical_purchase.count_purchase_in_month }}
                </p>
                <!-- <p class="text-xs text-gray-400 mt-1">Trong tháng</p> -->
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
                  data.statistical_purchase.count_purchase_in_month_pending }}</p>
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
                  data.statistical_purchase.count_purchase_in_month_received }}</p>
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
                  data.statistical_purchase.count_purchase_in_month_closed }}</p>
                <p class="text-xs text-gray-400 mt-1">Không thực hiện</p>
              </div>
              <div class="bg-red-50 p-3 rounded-lg">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
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
                  data.statistical_purchase.sum_value_purchase_in_month }}</p>
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
        <ChangePurchaseSevenDayAgo :purchaseChangeInSevenDay="data.statistical_purchase.purchase_change_in_seven_day"
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
      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-lg font-semibold text-gray-800">Net Revenue</h3>
          <div class="flex space-x-2">
            <select
              class="text-sm border border-gray-200 rounded-lg px-3 py-1 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
              <option>Daily</option>
              <option selected>Weekly</option>
              <option>Monthly</option>
              <option>Quarterly</option>
            </select>
            <button class="text-sm bg-indigo-600 text-white px-3 py-1 rounded-lg hover:bg-indigo-700">Export</button>
          </div>
        </div>
        <div class="h-80 bg-gray-50 rounded-lg flex items-center justify-center">
          <!-- Placeholder for revenue chart -->
          <div class="text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
              </path>
            </svg>
            <p class="mt-2 text-gray-500">Net revenue chart visualization</p>
            <p class="text-xs text-gray-400">Showing weekly revenue trends</p>
          </div>
        </div>
        <div class="mt-4 grid grid-cols-3 gap-4 text-center">
          <div class="bg-blue-50 p-3 rounded-lg">
            <p class="text-sm text-blue-600">This Week</p>
            <p class="font-bold text-blue-800">₫42.8M</p>
          </div>
          <div class="bg-green-50 p-3 rounded-lg">
            <p class="text-sm text-green-600">Last Week</p>
            <p class="font-bold text-green-800">₫38.5M</p>
          </div>
          <div class="bg-purple-50 p-3 rounded-lg">
            <p class="text-sm text-purple-600">Change</p>
            <p class="font-bold text-purple-800">+11.2%</p>
          </div>
        </div>
      </div>

      <!-- Top 10 Charts Row -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Top 10 Best Selling Products -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Top 10 Best Selling Products</h3>
            <select
              class="text-sm border border-gray-200 rounded-lg px-3 py-1 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
              <option>This Week</option>
              <option selected>This Month</option>
              <option>This Quarter</option>
              <option>This Year</option>
            </select>
          </div>
          <div class="h-80 bg-gray-50 rounded-lg flex items-center justify-center">
            <!-- Placeholder for top products chart -->
            <div class="text-center w-full px-4">
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">1. A4 70gsm - White</span>
                <span class="text-sm font-bold text-indigo-700">1,250 units</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                <div class="bg-indigo-600 h-2.5 rounded-full" style="width: 100%"></div>
              </div>

              <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">2. A3 80gsm - Yellow</span>
                <span class="text-sm font-bold text-indigo-700">980 units</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                <div class="bg-indigo-600 h-2.5 rounded-full" style="width: 78%"></div>
              </div>

              <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">3. A5 100gsm - Blue</span>
                <span class="text-sm font-bold text-indigo-700">750 units</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                <div class="bg-indigo-600 h-2.5 rounded-full" style="width: 60%"></div>
              </div>

              <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">4. A4 80gsm - Pink</span>
                <span class="text-sm font-bold text-indigo-700">620 units</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                <div class="bg-indigo-600 h-2.5 rounded-full" style="width: 50%"></div>
              </div>

              <p class="text-xs text-gray-500 mt-4">Showing top 4 of 10 products</p>
            </div>
          </div>
        </div>

        <!-- Top 10 Customers -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Top 10 Customers</h3>
            <select
              class="text-sm border border-gray-200 rounded-lg px-3 py-1 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
              <option>This Week</option>
              <option selected>This Month</option>
              <option>This Quarter</option>
              <option>This Year</option>
            </select>
          </div>
          <div class="h-80 bg-gray-50 rounded-lg flex items-center justify-center">
            <!-- Placeholder for top customers chart -->
            <div class="text-center w-full px-4">
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">1. Công ty TNHH ABC</span>
                <span class="text-sm font-bold text-green-700">₫15.2M</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                <div class="bg-green-600 h-2.5 rounded-full" style="width: 100%"></div>
              </div>

              <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">2. Trường học XYZ</span>
                <span class="text-sm font-bold text-green-700">₫12.8M</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                <div class="bg-green-600 h-2.5 rounded-full" style="width: 84%"></div>
              </div>

              <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">3. Nhà sách MNP</span>
                <span class="text-sm font-bold text-green-700">₫9.5M</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                <div class="bg-green-600 h-2.5 rounded-full" style="width: 62%"></div>
              </div>

              <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">4. Cửa hàng QRS</span>
                <span class="text-sm font-bold text-green-700">₫7.1M</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                <div class="bg-green-600 h-2.5 rounded-full" style="width: 47%"></div>
              </div>

              <p class="text-xs text-gray-500 mt-4">Showing top 4 of 10 customers</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Inventory Charts and Low Stock Section -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Inventory Chart -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 lg:col-span-2">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Inventory Overview by Paper Type</h3>
            <select
              class="text-sm border border-gray-200 rounded-lg px-3 py-1 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
              <option>Last 7 days</option>
              <option>Last 30 days</option>
              <option selected>Last 90 days</option>
            </select>
          </div>
          <div class="h-80 bg-gray-50 rounded-lg flex items-center justify-center">
            <p class="text-gray-400">Inventory chart visualization</p>
          </div>
        </div>

        <!-- Low Stock Items -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Low Stock Items</h3>
            <button class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">View All</button>
          </div>
          <div class="space-y-4">
            <div class="flex items-center p-3 bg-red-50 rounded-lg">
              <div class="bg-white p-2 rounded-lg mr-3">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <div>
                <p class="font-medium text-gray-800">A4 70gsm - White</p>
                <p class="text-sm text-red-600">Only 5 remaining</p>
              </div>
            </div>

            <div class="flex items-center p-3 bg-orange-50 rounded-lg">
              <div class="bg-white p-2 rounded-lg mr-3">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <div>
                <p class="font-medium text-gray-800">A3 80gsm - Yellow</p>
                <p class="text-sm text-orange-600">Only 9 remaining</p>
              </div>
            </div>

            <div class="flex items-center p-3 bg-red-50 rounded-lg">
              <div class="bg-white p-2 rounded-lg mr-3">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <div>
                <p class="font-medium text-gray-800">A5 100gsm - Blue</p>
                <p class="text-sm text-red-600">Only 3 remaining</p>
              </div>
            </div>

            <div class="flex items-center p-3 bg-orange-50 rounded-lg">
              <div class="bg-white p-2 rounded-lg mr-3">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <div>
                <p class="font-medium text-gray-800">A4 80gsm - Pink</p>
                <p class="text-sm text-orange-600">Only 7 remaining</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activity -->
      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-lg font-semibold text-gray-800">Recent Employee Activity</h3>
          <button class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">View All</button>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead>
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
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
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Warehouse Manager</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Processed 5 orders</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10 minutes ago</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
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
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Accountant</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Updated inventory records</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">30 minutes ago</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
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
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Delivery Staff</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Completed 2 deliveries</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1 hour ago</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">On
                    Delivery</span>
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
import { Link } from '@inertiajs/vue3';
import AppLayout from './admin/Layouts/AppLayout.vue';
import { route } from 'ziggy-js';
import ChangePurchaseSevenDayAgo from './components/ChangePurchaseSevenDayAgo.vue';
const { data } = defineProps({
  data: Object,
})
console.log(data);
</script>