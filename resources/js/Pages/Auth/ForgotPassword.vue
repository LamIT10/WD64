<template>
  <div class="forgot-password-container">
    <h1>Quên mật khẩu</h1>

    <!-- Hiển thị thông báo thành công -->
    <div v-if="$page.props.success" class="alert alert-success">
      {{ $page.props.success }}
    </div>

    <!-- Hiển thị lỗi -->
    <div v-if="form.errors.email" class="alert alert-danger">
      {{ form.errors.email }}
    </div>

    <form @submit.prevent="submit">
      <div class="form-group">
        <label for="email">Email:</label>
        <input
          id="email"
          v-model="form.email"
          type="email"
          placeholder="Nhập email của bạn"
          required
        />
      </div>
      <button type="submit" :disabled="form.processing">Gửi liên kết đặt lại mật khẩu</button>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  email: '',
})

function submit() {
  form.post('/forgot-password')
}
</script>

<style scoped>
.forgot-password-container {
  max-width: 400px;
  margin: 40px auto;
  padding: 24px;
  border: 1px solid #eee;
  border-radius: 8px;
  background: #fff;
}
.alert {
  margin-bottom: 16px;
  padding: 12px;
  border-radius: 4px;
}
.alert-success {
  background: #e6ffed;
  color: #1a7f37;
}
.alert-danger {
  background: #ffeaea;
  color: #c00;
}
.form-group {
  margin-bottom: 16px;
}
input[type="email"] {
  width: 100%;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
}
button {
  padding: 8px 16px;
  background: #1a7f37;
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
button[disabled] {
  background: #ccc;
  cursor: not-allowed;
}
</style>