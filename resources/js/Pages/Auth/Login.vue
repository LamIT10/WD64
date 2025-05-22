<template>
  <div class="login-container">
    <h1>Đăng nhập</h1>

    <!-- Hiển thị lỗi -->
    <div v-if="Object.keys(form.errors).length" class="alert alert-danger">
      <ul>
        <li class="text-amber-700" v-for="(error, key) in form.errors" :key="key">{{ error }}</li>
      </ul>
    </div>

    <form @submit.prevent="submitForm">
      <!-- Trường nhập email -->
      <div class="form-group">
        <label for="email">Email:</label>
        <input
          id="email"
          v-model="form.email"
          type="email"
          placeholder="Nhập email của bạn"
          
        />
      </div>

      <!-- Trường nhập mật khẩu -->
      <div class="form-group">
        <label for="password">Mật khẩu:</label>
        <input
          id="password"
          v-model="form.password"
          type="password"
          placeholder="Nhập mật khẩu của bạn"
          
        />
      </div>

      <!-- reCAPTCHA -->
      <div class="form-group recaptcha-container">
        <vue-recaptcha
          ref="recaptcha"
          :sitekey="siteKey"
          @verify="onVerify"
          @expired="onExpired"
        ></vue-recaptcha>
      </div>

      <!-- Nút submit -->
      <button type="submit" class="btn-submit">Đăng nhập</button>
    </form>
  </div>
</template>

<script>
import { useForm } from "@inertiajs/vue3";
import VueRecaptcha from "vue3-recaptcha2";

export default {
  components: { VueRecaptcha },
  data() {
    return {
      siteKey: "6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI", // Thay bằng Site Key của bạn
      form: useForm({
        email: "",
        password: "",
        "g-recaptcha-response": "",
      }),
    };
  },
  methods: {
    async submitForm() {
      // if (!this.form["g-recaptcha-response"]) {
      //   alert("Vui lòng xác thực reCAPTCHA!");
      //   return;
      // } 

      // Gửi yêu cầu đến endpoint /login
      this.form.post("/login", {
        onSuccess: () => {
          alert("Đăng nhập thành công!");
        },
        onError: (errors) => {
          console.error("Lỗi đăng nhập:", errors);
          alert("Đăng nhập thất bại. Vui lòng kiểm tra thông tin.");
        },
      });
    },
    onVerify(token) {
      this.form["g-recaptcha-response"] = token;
      console.log("reCAPTCHA verified:", token);
    },
    onExpired() {
      this.form["g-recaptcha-response"] = ""; // Xóa token khi hết hạn
      console.log("reCAPTCHA expired");
    },
  },
};
</script>

<style>
/* Container chính */
.login-container {
  max-width: 400px;
  margin: 50px auto;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  background-color: #fff;
  font-family: Arial, sans-serif;
}

/* Tiêu đề */
.login-container h1 {
  text-align: center;
  margin-bottom: 20px;
  color: #333;
}

/* Form group */
.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  font-weight: bold;
  margin-bottom: 5px;
  color: #555;
}

.form-group input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 14px;
  box-sizing: border-box;
}

/* Nút submit */
.btn-submit {
  width: 100%;
  padding: 10px;
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 5px;
  font-size: 16px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.btn-submit:hover {
  background-color: #0056b3;
}

/* reCAPTCHA container */
.recaptcha-container {
  text-align: center;
  margin-bottom: 15px;
}

.recaptcha-info {
  font-size: 12px;
  color: #888;
  margin-top: 5px;
}

/* Responsive */
@media (max-width: 480px) {
  .login-container {
    padding: 15px;
  }

  .btn-submit {
    font-size: 14px;
  }
}
</style>