// dashboard.js — FacultyLens Dashboard Logic
document.addEventListener("DOMContentLoaded", () => {

  /* =====================================
     1. LOGIN PROTECTION
     ===================================== */
  const user = JSON.parse(localStorage.getItem("user"));

  if (!user) {
    window.location.href = "index.html";
    return;
  }

  // Welcome message
  const welcomeMsg = document.getElementById("welcomeMsg");
  if (welcomeMsg && user.email) {
    const name = user.email.split("@")[0];
    welcomeMsg.textContent = `Welcome back, ${name}! 👋`;
  }

  /* =====================================
     2. MOBILE MENU TOGGLE
     ===================================== */
  const mobileMenuBtn = document.getElementById("mobileMenuBtn");
  const navLinks = document.getElementById("navLinks");

  if (mobileMenuBtn && navLinks) {
    mobileMenuBtn.addEventListener("click", () => {
      navLinks.classList.toggle("active");
    });
  }

  /* =====================================
     3. LOGOUT BUTTON
     ===================================== */
  const logoutBtn = document.getElementById("logoutBtn");

  if (logoutBtn) {
    logoutBtn.addEventListener("click", (e) => {
      e.preventDefault();
      localStorage.removeItem("user");
      window.location.href = "index.html";
    });
  }

  /* =====================================
     4. DASHBOARD CARD CLICK EFFECTS
     ===================================== */
  const cards = document.querySelectorAll(".hover-card");

  cards.forEach(card => {
    card.addEventListener("click", () => {
      card.classList.add("ring", "ring-purple-500");

      setTimeout(() => {
        card.classList.remove("ring", "ring-purple-500");
      }, 300);
    });
  });

  /* =====================================
     5. FEATURE TAG TOOLTIPS
     ===================================== */
  const tags = document.querySelectorAll(".glass.text-center.text-sm");

  tags.forEach(tag => {
    tag.addEventListener("click", () => {
      const feature = tag.textContent.trim();
      showToast(`Feature: ${feature}`);
    });
  });

  /* =====================================
     6. ANIMATED COUNTER
     ===================================== */
  function animateCounter(el, target, suffix = "") {
    let current = 0;
    const step = Math.ceil(target / 60);
    const interval = setInterval(() => {
      current += step;
      if (current >= target) {
        current = target;
        clearInterval(interval);
      }
      el.textContent = current.toLocaleString() + suffix;
    }, 25);
  }

  const avgRating = document.getElementById("avgRating");
  const feedbackCount = document.getElementById("feedbackCount");

  // Live stats from API/rating.php — falls back to demo values offline
  async function loadLiveStats() {
    try {
      const res = await fetch("../API/rating.php");
      if (!res.ok) throw new Error("bad status");
      const data = await res.json();
      if (!data.success || !data.ratings.length) return false;

      const total = data.counts.reduce((s, c) => s + c, 0);
      const avg = data.ratings.reduce((s, r) => s + r, 0) / data.ratings.length;

      if (avgRating) animateRating(avgRating, avg);
      if (feedbackCount) {
        feedbackCount.textContent = "0";
        setTimeout(() => animateCounter(feedbackCount, total), 300);
      }
      const top = document.getElementById("topFaculty");
      if (top && data.labels.length) top.textContent = data.labels[0];
      return true;
    } catch {
      return false;
    }
  }

  function animateRating(el, target) {
    el.textContent = "0";
    setTimeout(() => {
      let val = 0;
      const step = Math.max(target / 40, 0.05);
      const interval = setInterval(() => {
        val += step;
        if (val >= target) {
          val = target;
          clearInterval(interval);
        }
        el.textContent = val.toFixed(1);
      }, 40);
    }, 300);
  }

  loadLiveStats().then(ok => {
    if (ok) return;
    // Demo fallback when the PHP backend is not running
    if (avgRating) animateRating(avgRating, 4.3);
    if (feedbackCount) {
      feedbackCount.textContent = "0";
      setTimeout(() => animateCounter(feedbackCount, 1245), 300);
    }
  });

  /* =====================================
     7. TOAST NOTIFICATION
     ===================================== */
  function showToast(message) {
    const existing = document.querySelector(".toast-notification");
    if (existing) existing.remove();

    const toast = document.createElement("div");
    toast.className = "toast-notification";
    toast.textContent = message;
    toast.style.cssText = `
      position: fixed;
      bottom: 30px;
      left: 50%;
      transform: translateX(-50%);
      background: linear-gradient(135deg, #9b6bff, #6d2cff);
      color: #fff;
      padding: 12px 24px;
      border-radius: 12px;
      font-family: "Poppins", sans-serif;
      font-size: 14px;
      font-weight: 500;
      box-shadow: 0 10px 30px rgba(140,80,255,0.5);
      z-index: 9999;
      animation: toastIn 0.4s ease;
    `;

    document.body.appendChild(toast);

    setTimeout(() => {
      toast.style.animation = "toastOut 0.3s ease forwards";
      setTimeout(() => toast.remove(), 300);
    }, 2500);
  }

  // Add toast animations
  const style = document.createElement("style");
  style.textContent = `
    @keyframes toastIn {
      from { opacity: 0; transform: translateX(-50%) translateY(20px); }
      to   { opacity: 1; transform: translateX(-50%) translateY(0); }
    }
    @keyframes toastOut {
      from { opacity: 1; transform: translateX(-50%) translateY(0); }
      to   { opacity: 0; transform: translateX(-50%) translateY(20px); }
    }
  `;
  document.head.appendChild(style);

});
