document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("container");
    const registerBtn = document.getElementById("register");
    const loginBtn = document.getElementById("login");

    if (registerBtn) {
        registerBtn.addEventListener("click", () => {
            console.log("Test");
            container.classList.add("active");
        });
    } else {
        console.error("Register button not found!");
    }

    if (loginBtn) {
        loginBtn.addEventListener("click", () => {
            container.classList.remove("active");
        });
    } else {
        console.error("Login button not found!");
    }
});
