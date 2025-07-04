<template>
    <AppLayout>
        <div class="bg-gradient-to-b from-gray-100 to-gray-50 p-6 min-h-screen">
            <!-- Notification -->
            <div v-if="flash.message" :class="`fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${flash.type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`">
                {{ flash.message }}
            </div>

            <!-- Header -->
            <div class="p-4 shadow-sm rounded-lg bg-white mb-4 flex justify-between items-center border border-gray-200">
                <h5 class="text-lg text-indigo-700 font-semibold">Thêm hạng mới</h5>
                <Waiting route-name="admin.ranks.index" :route-params="{}" :color="'bg-indigo-50 text-indigo-700'">
                    <i class="fas fa-arrow-left mr-1"></i> Quay lại
                </Waiting>
            </div>

            <!-- Main Content -->
            <div class="mx-auto bg-white rounded-xl shadow-lg overflow-hidden animate-fade-in">
                <div class="w-full p-6">
                    <form @submit.prevent="confirmSubmit" class="space-y-6">
                        <!-- Row 1: Name + Min Total Spent -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tên hạng *</label>
                                <input v-model="form.name" type="text" required maxlength="255" pattern="[A-Za-z0-9\s+\-]+"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                    placeholder="Nhập tên hạng..." />
                                <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tổng chi tiêu tối thiểu *</label>
                                <input v-model.number="form.min_total_spent" type="number" min="1" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                    placeholder="Nhập tổng chi tiêu tối thiểu..." />
                                <p v-if="form.errors.min_total_spent" class="text-red-500 text-sm mt-1">{{ form.errors.min_total_spent }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phần trăm chiết khấu *</label>
                                <input v-model.number="form.discount_percent" type="number" min="1" max="100" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                    placeholder="Nhập % chiết khấu..." />
                                <p v-if="form.errors.discount_percent" class="text-red-500 text-sm mt-1">{{ form.errors.discount_percent }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái *</label>
                                <select v-model="form.status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                    <option value="" disabled selected>Chọn trạng thái</option>
                                    <option value="active">Hoạt động</option>
                                    <option value="inactive">Không hoạt động</option>
                                </select>
                                <p v-if="form.errors.status" class="text-red-500 text-sm mt-1">{{ form.errors.status }}</p>
                            </div>
                        </div>

                        <!-- Row 2: Credit Percent + Note -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phần trăm tín dụng *</label>
                                <input v-model.number="form.credit_percent" type="number" min="1" max="100" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                    placeholder="Nhập % tín dụng..." />
                                <p v-if="form.errors.credit_percent" class="text-red-500 text-sm mt-1">{{ form.errors.credit_percent }}</p>
                            </div>
                            <div class="md:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
                                <textarea v-model="form.note"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                    placeholder="Nhập ghi chú..." rows="3" maxlength="1000"></textarea>
                                <p v-if="form.errors.note" class="text-red-500 text-sm mt-1">{{ form.errors.note }}</p>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="flex justify-end space-x-3 p-6 bg-gray-50 border-t border-gray-200">
                    <Link :href="route('admin.ranks.index')"
                        class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                        Hủy bỏ
                    </Link>
                    <button type="submit" @click="confirmSubmit"
                        class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-md"
                        :disabled="form.processing">
                        <i class="fas fa-save mr-2"></i> Lưu hạng
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import Waiting from '../../components/Waiting.vue';
import { watch } from 'vue';

const { flash } = usePage().props;

const form = useForm({
    name: '',
    min_total_spent: null,
    discount_percent: null,
    credit_percent: null,
    note: null,
    status: '',
});

watch(flash, (newFlash) => {
    if (newFlash.message) {
        setTimeout(() => {
            router.reload({ only: ['flash'], data: { flash: null } });
        }, 3000);
    }
});

function confirmSubmit() {
    form.clearErrors();
    submit();
}

const submit = () => {
    console.log('Dữ liệu form:', form.data());
    form.transform((data) => ({
        ...data,
        note: data.note || null,
    })).post(route('admin.ranks.store'), {
        preserveScroll: true,
        onError: (errors) => {
            console.error('Lỗi tạo mới:', errors);
            Object.keys(errors).forEach((key) => {
                form.errors[key] = errors[key];
            });
        },
    });
};
</script>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.5s ease-in;
}
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>