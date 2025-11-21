// INISIALISASI AOS DILAKUKAN DI FILE HTML

// ----------------------------------
// Mobile Menu Toggle
// ----------------------------------
const menuToggle = document.getElementById("menu-toggle");
if (menuToggle) {
  menuToggle.addEventListener("click", function () {
    const mobileMenu = document.getElementById("mobile-menu");
    if (mobileMenu) {
      mobileMenu.classList.toggle("hidden");
    }
  });
}

// ----------------------------------
// Hero Slider Logic (Hanya di index.php)
// ----------------------------------
const slides = document.querySelectorAll(".slide");
const dots = document.querySelectorAll(".nav-dot");
let currentSlide = 0;

if (slides.length > 0 && dots.length > 0) {
  function showSlide(index) {
    slides.forEach((slide, i) => {
      slide.classList.remove("active");
      dots[i].classList.remove("active");
      if (i === index) {
        slide.classList.add("active");
        dots[i].classList.add("active");
      }
    });
  }

  function nextSlide() {
    currentSlide = (currentSlide + 1) % slides.length;
    showSlide(currentSlide);
  }

  // Otomatis pindah slide setiap 5 detik
  setInterval(nextSlide, 5000);

  // Navigasi dengan dots
  dots.forEach((dot) => {
    dot.addEventListener("click", function () {
      const slideIndex = parseInt(this.getAttribute("data-slide"));
      currentSlide = slideIndex;
      showSlide(currentSlide);
    });
  });

  // Tampilkan slide pertama saat dimuat
  showSlide(currentSlide);
}

// ------------------------------------
// SHA-256 password hashing
// ------------------------------------
async function hashPassword(password) {
  const encoder = new TextEncoder();
  const data = encoder.encode(password);
  const hashBuffer = await crypto.subtle.digest("SHA-256", data);
  return Array.from(new Uint8Array(hashBuffer))
    .map((b) => b.toString(16).padStart(2, "0"))
    .join("");
}

// ------------------------------------
// API ENDPOINTS
// ------------------------------------
const API_ENDPOINT =
  "https://script.google.com/macros/s/AKfycbx8A2-_Ai673pomfbSOHD_JLtpCkxYobkWsDjGD4m7__m93iiGgVPUXbj93GLsFNjHBLQ/exec";
const CART_ENDPOINT = "cart.php";

// ------------------------------------
// Session Helpers
// ------------------------------------
function getCurrentUser() {
  if (window.__userLoggedIn) {
    return { name: window.__userName };
  }

  try {
    const raw = localStorage.getItem("user");
    return raw ? JSON.parse(raw) : null;
  } catch {
    return null;
  }
}

function setUserSession(user) {
  localStorage.setItem("user", JSON.stringify(user));
}

function clearUserSession() {
  localStorage.removeItem("user");
}

function requireLoginRedirect() {
  if (window.__userLoggedIn) {
    return { name: window.__userName };
  }

  const user = getCurrentUser();
  if (!user) {
    alert("Silakan login terlebih dahulu.");
    window.location.href = "login.php";
    return null;
  }
  return user;
}

// ------------------------------------
// DOM LOADED
// ------------------------------------
document.addEventListener("DOMContentLoaded", () => {
  const currencyFormatter = new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    maximumFractionDigits: 0,
  });

  const cartPanel = document.getElementById("cart-panel");
  const cartItemsContainer = document.getElementById("cart-items");
  const cartTotalEl = document.getElementById("cart-total");
  const cartCheckoutButton = document.getElementById("cart-checkout");
  const cartCloseButton = document.getElementById("cart-panel-close");
  const cartPreviewTriggers = document.querySelectorAll(".cart-preview-trigger");
  const cartCountEls = document.querySelectorAll(".cart-count");
  let latestCartData = null;

  const formatCartCurrency = (value) => currencyFormatter.format(value);

  const renderCart = (payload) => {
    latestCartData = payload;

    cartCountEls.forEach((el) => {
      el.textContent = payload.totalQty ?? 0;
    });

    if (!cartItemsContainer) return;
    const items = payload.items || [];
    if (items.length === 0) {
      cartItemsContainer.innerHTML = '<p class="text-center text-gray-500">Keranjang kosong.</p>';
    } else {
      cartItemsContainer.innerHTML = items
      .map(
        (item) => {
          const priceText = formatCartCurrency(item.price * item.quantity);
          return `
            <div class="flex items-start justify-between gap-2 border-b border-gray-100 pb-2 last:border-b-0">
              <div class="flex-1">
                <p class="font-semibold text-sm text-gray-800">${item.name}</p>
                <p class="text-xs text-gray-500">Qty ${item.quantity}</p>
                <p class="text-xs text-gray-500">${priceText}</p>
              </div>
              <div class="text-right text-xs">
                <button type="button" class="cart-remove-btn text-red-500" data-product-id="${item.product_id}">Hapus</button>
              </div>
            </div>
          `;
        }
      )
        .join("");
    }

    if (cartTotalEl) {
      cartTotalEl.textContent = formatCartCurrency(payload.totalAmount ?? 0);
    }
  };

  const fetchCart = async (showPanel = false) => {
    try {
      const res = await fetch(CART_ENDPOINT);
      const payload = await res.json();
      renderCart(payload);
      if (showPanel && cartPanel) {
        cartPanel.classList.remove("hidden");
      }
    } catch (error) {
      console.warn("Gagal memuat keranjang", error);
    }
  };

  const updateCart = async (action, productId, quantity = 1) => {
    try {
      const payload = await fetch(CART_ENDPOINT, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ action, product_id: productId, quantity }),
      }).then((res) => res.json());

      if (payload.success) {
        renderCart(payload);
      }
    } catch (error) {
      console.error("Gagal memperbarui keranjang", error);
    }
  };

  cartPreviewTriggers.forEach((trigger) => {
    trigger.addEventListener("click", () => {
      if (cartPanel) {
        cartPanel.classList.toggle("hidden");
      }
    });
  });

  if (cartCloseButton) {
    cartCloseButton.addEventListener("click", () => {
      cartPanel?.classList.add("hidden");
    });
  }

  if (cartItemsContainer) {
    cartItemsContainer.addEventListener("click", (event) => {
      const target = event.target;
      if (target instanceof HTMLElement && target.matches(".cart-remove-btn")) {
        const productId = Number(target.getAttribute("data-product-id"));
        if (productId) {
          updateCart("remove", productId);
        }
      }
    });
  }

  if (cartCheckoutButton) {
    cartCheckoutButton.addEventListener("click", () => {
      if (!latestCartData || !latestCartData.totalQty) {
        alert("Keranjang masih kosong.");
        return;
      }

      const user = requireLoginRedirect();
      if (!user) return;

      window.location.href = "checkout.php";
    });
  }

  const addCartButtons = document.querySelectorAll(".add-cart-btn");
  addCartButtons.forEach((btn) => {
    btn.addEventListener("click", async () => {
      const user = requireLoginRedirect();
      if (!user) return;

      const productId = Number(btn.getAttribute("data-product-id"));
      if (!productId) {
        return;
      }

      await updateCart("add", productId, 1);
      fetchCart(true);
    });
  });

  fetchCart();

  // REGISTER FORM
  const registerForm = document.getElementById("register-form");
  const registerFb = document.getElementById("register-feedback");
  const registerEndpoint = `${API_ENDPOINT}?action=register`;

  const showRegisterFeedback = (message, isError = false) => {
    if (!registerFb) return;
    registerFb.textContent = message;
    registerFb.classList.remove("hidden", "text-red-500", "text-green-600");
    registerFb.classList.add(isError ? "text-red-500" : "text-green-600");
  };

  if (registerForm) {
    const submitButton = registerForm.querySelector('button[type="submit"]');

    registerForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const name = document.getElementById("register-name").value.trim();
      const email = document.getElementById("register-email").value.trim();
      const passwordRaw = document.getElementById("register-password").value;

      if (!name || !email || !passwordRaw) {
        showRegisterFeedback("Semua field wajib diisi.", true);
        return;
      }

      submitButton.disabled = true;
      submitButton.textContent = "Mengirim...";
      showRegisterFeedback("Mengirim data, tunggu sebentar...", false);

      const payload = {
        id: Date.now().toString(),
        name,
        email,
        password: await hashPassword(passwordRaw),
      };

      const requestOptions = {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      };

      try {
        const response = await fetch(registerEndpoint, requestOptions);
        let data = null;
        try {
          data = await response.json();
        } catch (_) {
          // response might be opaque or empty; we'll still treat as success below
        }

        if (data && !data.success) {
          throw new Error(data.message || "Gagal memproses pendaftaran.");
        }

        showRegisterFeedback("Pendaftaran berhasil! Silakan login.", false);
        registerForm.reset();
      } catch (err) {
        console.warn("Register with CORS failed, retrying with no-cors", err);
        try {
          await fetch(registerEndpoint, { ...requestOptions, mode: "no-cors" });
          showRegisterFeedback("Pendaftaran berhasil! Silakan login.", false);
          registerForm.reset();
        } catch (nestedErr) {
          console.error(nestedErr);
          showRegisterFeedback("Terjadi kesalahan, coba lagi.", true);
        }
      } finally {
        submitButton.disabled = false;
        submitButton.textContent = "Kirim Pendaftaran";
      }
    });
  }

  // LOGIN FORM
  const loginForm = document.getElementById("login-form");
  const loginFb = document.getElementById("login-feedback");
  const loginEndpoint = `${API_ENDPOINT}?action=login`;

  const showLoginFeedback = (message, isError = false) => {
    if (!loginFb) return;
    loginFb.textContent = message;
    loginFb.classList.remove("hidden", "text-red-500", "text-green-600");
    loginFb.classList.add(isError ? "text-red-500" : "text-green-600");
  };

  const navigateAfterLogin = () => {
    setTimeout(() => {
      window.location.href = "products.php";
    }, 700);
  };

  if (loginForm) {
    const loginButton = loginForm.querySelector('button[type="submit"]');

    loginForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const emailInput = document.getElementById("login-email");
      const passwordInput = document.getElementById("login-password");
      if (!emailInput || !passwordInput) return;

      const email = emailInput.value.trim();
      const passwordRaw = passwordInput.value;

      if (!email || !passwordRaw) {
        showLoginFeedback("Email dan kata sandi wajib diisi.", true);
        return;
      }

      loginButton.disabled = true;
      loginButton.textContent = "Memproses...";
      showLoginFeedback("Memeriksa kredensial...", false);

      const payload = {
        email,
        password: await hashPassword(passwordRaw),
      };

      const requestOptions = {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      };

      const fallbackUser = { name: email, email };

      const handleSuccess = (user = fallbackUser) => {
        setUserSession(user);
        showLoginFeedback("Login berhasil. Mengalihkan...", false);
        navigateAfterLogin();
      };

      try {
        const response = await fetch(loginEndpoint, requestOptions);
        const data = await response.json();
        if (!data.success) {
          throw new Error(data.message || "Email atau kata sandi salah.");
        }
        handleSuccess(data.user ?? fallbackUser);
      } catch (err) {
        console.warn("Login API CORS failed, retrying with no-cors", err);
        try {
          await fetch(loginEndpoint, { ...requestOptions, mode: "no-cors" });
          handleSuccess();
        } catch (nestedErr) {
          console.error(nestedErr);
          showLoginFeedback("Terjadi kesalahan saat login. Coba lagi.", true);
        }
      } finally {
        loginButton.disabled = false;
        loginButton.textContent = "Login";
      }
    });
  }

  // LOGOUT
  const logoutButton = document.getElementById("logout-button");
  const logoutButtonMobile = document.getElementById("logout-button-mobile");

  const redirectToLogout = () => {
    clearUserSession();
    window.location.href = "logout.php";
  };

  if (logoutButton) {
    logoutButton.addEventListener("click", redirectToLogout);
  }

  if (logoutButtonMobile) {
    logoutButtonMobile.addEventListener("click", redirectToLogout);
  }
});
