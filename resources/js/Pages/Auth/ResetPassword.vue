<template>
  <div class="reset-password-container">
    <h1>Đặt lại mật khẩu</h1>

    <!-- Hiển thị lỗi -->
    <div v-if="form.errors.email" class="alert alert-danger">
      {{ form.errors.email }}
    </div>
    <div v-if="form.errors.password" class="alert alert-danger">
      {{ form.errors.password }}
    </div>
    <div v-if="form.errors.token" class="alert alert-danger">
      {{ form.errors.token }}
    </div>

    <form @submit.prevent="submit">
      <input type="hidden" v-model="form.token" />
      <input type="hidden" v-model="form.email" />

      <div class="form-group">
        <label for="password">Mật khẩu mới:</label>
        <input
          id="password"
          v-model="form.password"
          type="password"
          placeholder="Nhập mật khẩu mới"
          required
        />
      </div>

      <div class="form-group">
        <label for="password_confirmation">Xác nhận mật khẩu:</label>
        <input
          id="password_confirmation"
          v-model="form.password_confirmation"
          type="password"
          placeholder="Nhập lại mật khẩu mới"
          required
        />
      </div>

      <button type="submit" :disabled="form.processing">Đặt lại mật khẩu</button>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()

const form = useForm({
  token: page.props.token,
  email: page.props.email,
  password: '',
  password_confirmation: '',
})

function submit() {
  form.post('/reset-password')
}
</script>

<style scoped>
.reset-password-container {
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
  background: #ffeaea;
  color: #c00;
}
.form-group {
  margin-bottom: 16px;
}
input[type="password"] {
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