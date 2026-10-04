document.addEventListener("DOMContentLoaded", () => {

  const pw       = document.getElementById("password");
  const eye      = document.getElementById("toggleEye");
  const btn      = document.getElementById("loginBtn");
  const errorMsg = document.getElementById("errorMsg");
  const email    = document.getElementById("email");
  const remember = document.getElementById("rememberMe");

  /* ===== Remember Me ===== */
  if (localStorage.getItem("savedEmail")) {
    email.value = localStorage.getItem("savedEmail");
    if (remember) remember.checked = true;
  }

  /* ===== Show / Hide Password ===== */
  if (eye) {
    eye.addEventListener("click", () => {
      pw.type = pw.type === "password" ? "text" : "password";
      eye.textContent = pw.type === "password" ? "👁" : "🙈";
    });
  }

  /* ===== Password Strength Indicator ===== */
  const strength = document.createElement("div");
  strength.className = "strength";
  pw.parentElement.appendChild(strength);

  pw.addEventListener("input", () => {
    const v = pw.value;
    if (v.length === 0) {
      strength.textContent = "";
      strength.className = "strength";
    } else if (v.length < 6) {
      strength.textContent = "Weak password";
      strength.className = "strength weak";
    } else if (/[A-Z]/.test(v) && /\d/.test(v)) {
      strength.textContent = "Strong password";
      strength.className = "strength strong";
    } else {
      strength.textContent = "Medium password";
      strength.className = "strength medium";
    }
  });

  /* ===== Caps Lock Warning ===== */
  const caps = document.createElement("div");
  caps.className = "caps-warning";
  pw.parentElement.appendChild(caps);

  pw.addEventListener("keyup", e => {
    caps.textContent = e.getModifierState("CapsLock")
      ? "Caps Lock is ON"
      : "";
  });

  /* ===== Login Handler ===== */
  btn.addEventListener("click", async () => {
    errorMsg.textContent = "";

    if (!email.value || !pw.value) {
      showError("Please fill all fields");
      return;
    }

    btn.textContent = "Logging in...";
    btn.classList.add("loading");

    try {
      const res = await fetch("../API/login.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          email:    email.value,
          password: pw.value
        })
      });

      if (!res.ok) {
        throw new Error("Login request failed");
      }

      const data = await res.json();

      if (data.success) {
        // Save session info locally
        localStorage.setItem("user", JSON.stringify({
          id: data.id || null,
          name: data.name || data.username || "",
          username: data.username || "",
          role: data.role || "",
          email:    email.value,
          loggedIn: true
        }));

        if (remember && remember.checked) {
          localStorage.setItem("savedEmail", email.value);
        } else {
          localStorage.removeItem("savedEmail");
        }

        window.location.href = "dashboard.html";
      } else {
        showError(data.message || "Invalid email or password");
        btn.textContent = "Log in";
        btn.classList.remove("loading");
      }
    } catch (err) {
      console.error("Login failed", err);
      showError("Unable to reach server. Please try again.");
      btn.textContent = "Log in";
      btn.classList.remove("loading");
    }
  });

  /* ===== Enter key to submit ===== */
  [email, pw].forEach(el => {
    el.addEventListener("keydown", e => {
      if (e.key === "Enter") btn.click();
    });
  });

  function showError(msg) {
    errorMsg.textContent = msg;
    const box = document.querySelector(".login-box");
    box.classList.add("shake");
    setTimeout(() => box.classList.remove("shake"), 400);
  }
});
