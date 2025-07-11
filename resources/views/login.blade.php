<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" />
    
    <title>Login Page | Caged coder</title>
    <style>
      @keyframes move {
        0%, 49.99% {
          opacity: 0;
          z-index: 1;
        }
        50%, 100% {
          opacity: 1;
          z-index: 5;
        }
      }
      .animate-move {
        animation: move 0.6s;
      }
    </style>
  </head>

  <body class="bg-gradient-to-r from-gray-200 to-blue-200 flex items-center justify-center flex-col min-h-screen font-montserrat">
    <div class="container bg-white rounded-[30px] shadow-lg relative overflow-hidden w-[768px] max-w-full min-h-[480px]" id="container">
      <!-- Sign Up Form -->
      <div class="form-container sign-up absolute top-0 h-full w-1/2 opacity-0 z-1 transition-all duration-600 ease-in-out">
        <form class="bg-white flex items-center justify-center flex-col px-10 h-full">
          <h1 class="text-2xl mb-5">Create Account</h1>
          <div class="social-icons flex gap-2 my-5">
            <a href="#" class="icon border border-gray-300 rounded-[20%] flex items-center justify-center w-10 h-10">
              <i class="fa-brands fa-google-plus-g"></i>
            </a>
            <a href="#" class="icon border border-gray-300 rounded-[20%] flex items-center justify-center w-10 h-10">
              <i class="fa-brands fa-facebook-f"></i>
            </a>
            <a href="#" class="icon border border-gray-300 rounded-[20%] flex items-center justify-center w-10 h-10">
              <i class="fa-brands fa-github"></i>
            </a>
            <a href="#" class="icon border border-gray-300 rounded-[20%] flex items-center justify-center w-10 h-10">
              <i class="fa-brands fa-linkedin-in"></i>
            </a>
          </div>
          <span class="text-xs">or use your email for registration</span>
          <input type="text" placeholder="Name" class="bg-gray-200 border-none my-2 p-3 text-sm rounded-lg w-full outline-none" />
          <input type="email" placeholder="Email" class="bg-gray-200 border-none my-2 p-3 text-sm rounded-lg w-full outline-none" />
          <input type="password" placeholder="Password" class="bg-gray-200 border-none my-2 p-3 text-sm rounded-lg w-full outline-none" />
          <button class="bg-teal-600 text-white text-xs py-3 px-10 border border-transparent rounded-lg font-semibold uppercase mt-3 cursor-pointer">Sign Up</button>
        </form>
      </div>
      <!-- Sign In Form -->
      <div class="form-container sign-in absolute top-0 left-0 h-full w-1/2 z-2 transition-all duration-600 ease-in-out">
        <form class="bg-white flex items-center justify-center flex-col px-10 h-full">
          <h1 class="text-2xl mb-5">Sign In</h1>
          <div class="social-icons flex gap-2 my-5">
            <a href="#" class="icon border border-gray-300 rounded-[20%] flex items-center justify-center w-10 h-10">
              <i class="fa-brands fa-google-plus-g"></i>
            </a>
            <a href="#" class="icon border border-gray-300 rounded-[20%] flex items-center justify-center w-10 h-10">
              <i class="fa-brands fa-facebook-f"></i>
            </a>
            <a href="#" class="icon border border-gray-300 rounded-[20%] flex items-center justify-center w-10 h-10">
              <i class="fa-brands fa-github"></i>
            </a>
            <a href="#" class="icon border border-gray-300 rounded-[20%] flex items-center justify-center w-10 h-10">
              <i class="fa-brands fa-linkedin-in"></i>
            </a>
          </div>
          <span class="text-xs">or use your email password</span>
          <input type="email" placeholder="Email" class="bg-gray-200 border-none my-2 p-3 text-sm rounded-lg w-full outline-none" />
          <input type="password" placeholder="Password" class="bg-gray-200 border-none my-2 p-3 text-sm rounded-lg w-full outline-none" />
          <a href="#" class="text-gray-700 text-xs my-3">Forget Your Password?</a>
          <button class="bg-teal-600 text-white text-xs py-3 px-10 border border-transparent rounded-lg font-semibold uppercase mt-3 cursor-pointer">Sign In</button>
        </form>
      </div>
      <!-- Toggle Container -->
      <div class="toggle-container absolute top-0 left-1/2 w-1/2 h-full overflow-hidden transition-all duration-600 ease-in-out rounded-[150px_0_0_100px] z-[1000]">
        <div class="toggle bg-gradient-to-r from-indigo-500 to-teal-600 text-white relative left-[-100%] h-full w-[200%] transform translate-x-0 transition-all duration-600 ease-in-out">
          <div class="toggle-panel toggle-left absolute w-1/2 h-full flex items-center justify-center flex-col px-8 text-center top-0 ">
            <h1 class="text-2xl mb-5">Welcome Back!</h1>
            <p class="text-sm leading-5 mb-5">Enter your personal details to use all of site features</p>
            <button class=" bg-transparent text-white text-xs py-3 px-10 border border-white rounded-lg font-semibold uppercase mt-3 cursor-pointer" id="login">Sign In</button>
          </div>
          <div class="toggle-panel toggle-right absolute w-1/2 h-full flex items-center justify-center flex-col px-8 text-center top-0 right-0 transform translate-x-0 transition-all duration-600 ease-in-out">
            <h1 class="text-2xl mb-5">Hello, Friend!</h1>
            <p class="text-sm leading-5 mb-5">Register with your personal details to use all of site features</p>
            <button class="    bg-transparent text-white text-xs py-3 px-10 border border-white rounded-lg font-semibold uppercase mt-3 cursor-pointer" id="register">Sign Up</button>
          </div>
        </div>
      </div>
    </div>

    @vite('resources/js/login.js')
  </body>
</html>
<style>
    *{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Montserrat', sans-serif;
}

body{
    background-color: #c9d6ff;
    background: linear-gradient(to right, #e2e2e2, #c9d6ff);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    height: 100vh;
}

.container{
    background-color: #fff;
    border-radius: 30px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
    position: relative;
    overflow: hidden;
    width: 768px;
    max-width: 100%;
    min-height: 480px;
}

.container p{
    font-size: 14px;
    line-height: 20px;
    letter-spacing: 0.3px;
    margin: 20px 0;
}

.container span{
    font-size: 12px;
}

.container a{
    color: #333;
    font-size: 13px;
    text-decoration: none;
    margin: 15px 0 10px;
}

.container button{
    background-color: #2da0a8;
    color: #fff;
    font-size: 12px;
    padding: 10px 45px;
    border: 1px solid transparent;
    border-radius: 8px;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    margin-top: 10px;
    cursor: pointer;
}

.container button.hidden{
    background-color: transparent;
    border-color: #fff;
}

.container form{
    background-color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 0 40px;
    height: 100%;
}

.container input{
    background-color: #eee;
    border: none;
    margin: 8px 0;
    padding: 10px 15px;
    font-size: 13px;
    border-radius: 8px;
    width: 100%;
    outline: none;
}

.form-container{
    position: absolute;
    top: 0;
    height: 100%;
    transition: all 0.6s ease-in-out;
}

.sign-in{
    left: 0;
    width: 50%;
    z-index: 2;
}

.container.active .sign-in{
    transform: translateX(100%);
}

.sign-up{
    left: 0;
    width: 50%;
    opacity: 0;
    z-index: 1;
}

.container.active .sign-up{
    transform: translateX(100%);
    opacity: 1;
    z-index: 5;
    animation: move 0.6s;
}

@keyframes move{
    0%, 49.99%{
        opacity: 0;
        z-index: 1;
    }
    50%, 100%{
        opacity: 1;
        z-index: 5;
    }
}

.social-icons{
    margin: 20px 0;
}

.social-icons a{
    border: 1px solid #ccc;
    border-radius: 20%;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    margin: 0 3px;
    width: 40px;
    height: 40px;
}

.toggle-container{
    position: absolute;
    top: 0;
    left: 50%;
    width: 50%;
    height: 100%;
    overflow: hidden;
    transition: all 0.6s ease-in-out;
    border-radius: 150px 0 0 100px;
    z-index: 1000;
}

.container.active .toggle-container{
    transform: translateX(-100%);
    border-radius: 0 150px 100px 0;
}

.toggle{
    background-color: #2da0a8;
    height: 100%;
    background: linear-gradient(to right, #5c6bc0, #2da0a8);
    color: #fff;
    position: relative;
    left: -100%;
    height: 100%;
    width: 200%;
    transform: translateX(0);
    transition: all 0.6s ease-in-out;
}

.container.active .toggle{
    transform: translateX(50%);
}

.toggle-panel{
    position: absolute;
    width: 50%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 0 30px;
    text-align: center;
    top: 0;
    transform: translateX(0);
    transition: all 0.6s ease-in-out;
}

.toggle-left{
    transform: translateX(-200%);
}

.container.active .toggle-left{
    transform: translateX(0);
}

.toggle-right{
    right: 0;
    transform: translateX(0);
}

.container.active .toggle-right{
    transform: translateX(200%);
}
</style>