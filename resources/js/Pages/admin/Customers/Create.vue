<template>
    <AppLayout>
        <div class="bg-gray-50 p-6">
            <div class="p-4 shadow rounded bg-white mb-4 flex justify-between items-center">
                <h5 class="text-lg text-indigo-700 font-semibold">Thêm khách hàng mới</h5>
                <Waiting route-name="admin.customers.index" :route-params="{}" :color="'bg-indigo-50 text-indigo-700'">
                    <i class="fas fa-arrow-left mr-1"></i> Quay lại
                </Waiting>
            </div>
            <div class="mx-auto bg-white rounded shadow-md overflow-hidden">
                <!-- Tabs -->
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-4 px-6 pt-4">
                        <button class="pb-2 px-1 border-b-2 border-indigo-600 text-indigo-600 font-medium">Thông tin</button>
                    </nav>
                </div>

                <!-- Main Content -->
                <div class="flex flex-col md:flex-row p-6">
                    <!-- Left Panel - Photo Upload -->
                    <div class="w-full md:w-1/4 mb-6 md:mb-0 md:pr-6">
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                            <div class="w-32 h-32 mx-auto bg-gray-100 rounded flex items-center justify-center">
                                <i class="fas fa-camera text-gray-400 text-2xl"></i>
                            </div>
                            <input type="file" accept="image/*" @change="form.avatar = $event.target.files[0]" class="hidden" id="avatar-upload" />
                            <button type="button" @click="$refs.avatarInput.click()" class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                Chọn ảnh
                            </button>
                            <input type="file" accept="image/*" @change="form.avatar = $event.target.files[0]" class="hidden" ref="avatarInput" />
                            <p v-if="form.errors.avatar" class="text-red-500 text-sm mt-1">{{ form.errors.avatar }}</p>
                        </div>
                    </div>

                    <!-- Right Panel - Fields -->
                    <div class="w-full md:w-3/4">
                        <form @submit.prevent="submit" class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Thông tin cơ bản</h3>
                            <!-- Row 1: Name + Email -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tên khách hàng *</label>
                                    <input v-model="form.name" type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="Nhập tên khách hàng..." />
                                    <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input v-model="form.email" type="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="Nhập địa chỉ email..." />
                                    <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
                                </div>
                            </div>

                            <!-- Row 2: Phone + Rank -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại *</label>
                                    <input v-model="form.phone" type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="Nhập số điện thoại..." />
                                    <p v-if="form.errors.phone" class="text-red-500 text-sm mt-1">{{ form.errors.phone }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Hạng khách hàng</label>
                                    <select v-model="form.rank_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                        <option value="" disabled>Chọn hạng...</option>
                                        <option v-for="rank in rankTemplates" :key="rank.id" :value="rank.id">{{ rank.name }}</option>
                                    </select>
                                    <p v-if="form.errors.rank_id" class="text-red-500 text-sm mt-1">{{ form.errors.rank_id }}</p>
                                </div>
                            </div>

                            <!-- Row 3: Password + Confirm Password -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu</label>
                                    <input v-model="form.password" type="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="Nhập mật khẩu..." />
                                    <p v-if="form.errors.password && !form.errors.password.toLowerCase().includes('khớp')" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Xác nhận mật khẩu</label>
                                    <input v-model="form.password_confirmation" type="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="Nhập lại mật khẩu..." />
                                    <p v-if="form.errors.password && form.errors.password.toLowerCase().includes('khớp')" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
                                </div>
                            </div>

                            <!-- Additional Information Section -->
                            <div v-if="showAdditionalInfo">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 mt-6">Thông tin bổ sung</h3>
                                <div class="space-y-6">
                                    <!-- Row 4: Address + Current Debt -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ</label>
                                            <input v-model="form.address" type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="Nhập địa chỉ..." />
                                            <p v-if="form.errors.address" class="text-red-500 text-sm mt-1">{{ form.errors.address }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Công nợ</label>
                                            <input v-model="form.current_debt" type="number" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="Nhập công nợ..." />
                                            <p v-if="form.errors.current_debt" class="text-red-500 text-sm mt-1">{{ form.errors.current_debt }}</p>
                                        </div>
                                    </div>

                                    <!-- Row 5: Total Spent + Status -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Tổng chi tiêu</label>
                                            <input v-model="form.total_spent" type="number" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="Nhập tổng chi tiêu..." />
                                            <p v-if="form.errors.total_spent" class="text-red-500 text-sm mt-1">{{ form.errors.total_spent }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                                            <select v-model="form.status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                                <option value="active">Hoạt động</option>
                                                <option value="inactive">Không hoạt động</option>
                                                <option value="blocked">Bị khóa</option>
                                            </select>
                                            <p v-if="form.errors.status" class="text-red-500 text-sm mt-1">{{ form.errors.status }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Additional Info Button -->
                <div class="px-6 pb-6">
                    <button type="button" @click="toggleAdditionalInfo" class="text-indigo-600 hover:text-indigo-800 flex items-center">
                        <i :class="showAdditionalInfo ? 'fas fa-minus' : 'fas fa-plus'" class="mr-2"></i>
                        {{ showAdditionalInfo ? 'Ẩn thông tin' : 'Thêm thông tin' }}
                    </button>
                </div>

                <!-- Footer Actions -->
                <div class="flex justify-end space-x-3 p-6 bg-gray-50 border-t border-gray-200">
                    <Waiting route-name="admin.customers.index" :route-params="{}" :color="'border border-gray-300 text-gray-700 hover:bg-gray-100'">
                        Hủy bỏ
                    </Waiting>
                    <button type="button" @click="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-md" :disabled="form.processing">
                        <i class="fas fa-save mr-2"></i> Lưu khách hàng
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import Waiting from '../../components/Waiting.vue';
import { ref } from 'vue';

defineProps({
    rankTemplates: Array,
});

const showAdditionalInfo = ref(false);

const form = useForm({
    name: '',
    phone: '',
    email: '',
    password: '',
    password_confirmation: '',
    address: '',
    current_debt: 0,
    total_spent: 0,
    rank_id: '',
    avatar: null,
    status: 'active',
});

function toggleAdditionalInfo() {
    showAdditionalInfo.value = !showAdditionalInfo.value;
}

const submit = () => {
    form.post(route('admin.customers.store'), {
        onSuccess: () => {
            form.reset();
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
};
</script>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>