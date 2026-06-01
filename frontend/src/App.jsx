import { useEffect, useMemo, useState } from "react";

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || "http://localhost:8000";

function formatRupiah(value) {
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    maximumFractionDigits: 0,
  }).format(value);
}

function normalizeImageUrl(product) {
  return product?.image_url || "/images/items/1.png";
}

const CATEGORIES = [
  { name: "Aksesoris", icon: "✦" },
  { name: "Pakaian", icon: "⌂" },
  { name: "Outer", icon: "◫" },
  { name: "Kemeja", icon: "▣" },
  { name: "Rompi", icon: "◌" },
];

const REVIEWS = [
  {
    name: "Andi Saputra",
    stars: 5,
    desc: "Kualitas jahitan sangat rapi dan bahan yang digunakan benar-benar premium.",
  },
  {
    name: "Rina Wati",
    stars: 4,
    desc: "Desain sesuai dengan yang saya minta. Pengiriman juga cepat.",
  },
  {
    name: "Budi Hartono",
    stars: 5,
    desc: "Sudah 3 kali order di sini dan kualitasnya selalu konsisten.",
  },
  {
    name: "Siti Nurhaliza",
    stars: 4,
    desc: "Proses konsultasi desainnya sangat membantu. Tim-nya ramah dan responsif.",
  },
  {
    name: "Dimas Prasetyo",
    stars: 5,
    desc: "Jaket varsity custom saya hasilnya keren banget!",
  },
  {
    name: "Maya Sari",
    stars: 4,
    desc: "Bahan nyaman dipakai seharian. Bordir logonya juga detail.",
  },
];

const DEFAULT_LOGIN_FORM = { login: "", password: "", remember: false };
const DEFAULT_REGISTER_FORM = {
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
};

export default function App() {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [search, setSearch] = useState("");
  const [darkMode, setDarkMode] = useState(() => localStorage.getItem("darkMode") === "true");
  const [selectedProduct, setSelectedProduct] = useState(null);
  const [productModalOpen, setProductModalOpen] = useState(false);
  const [cartOpen, setCartOpen] = useState(false);
  const [cart, setCart] = useState(() => JSON.parse(localStorage.getItem("cart") || "{}"));
  const [authToken, setAuthToken] = useState(() => localStorage.getItem("authToken") || "");
  const [currentUser, setCurrentUser] = useState(null);
  const [authModalOpen, setAuthModalOpen] = useState(false);
  const [authMode, setAuthMode] = useState("login");
  const [authMessage, setAuthMessage] = useState("");
  const [authLoading, setAuthLoading] = useState(false);
  const [loginForm, setLoginForm] = useState(DEFAULT_LOGIN_FORM);
  const [registerForm, setRegisterForm] = useState(DEFAULT_REGISTER_FORM);
  const [contactForm, setContactForm] = useState({ email: "", username: "", komentar: "", anonim: false });
  const [profileMenuOpen, setProfileMenuOpen] = useState(false);

  useEffect(() => {
    const root = document.documentElement;
    if (darkMode) {
      root.classList.add("dark");
    } else {
      root.classList.remove("dark");
    }
    localStorage.setItem("darkMode", String(darkMode));
  }, [darkMode]);

  useEffect(() => {
    localStorage.setItem("cart", JSON.stringify(cart));
  }, [cart]);

  useEffect(() => {
    async function loadProducts() {
      const controller = new AbortController();
      try {
        setLoading(true);
        const response = await fetch(`${API_BASE_URL}/api/products`, { signal: controller.signal });
        if (!response.ok) {
          throw new Error("Gagal mengambil data produk.");
        }
        const data = await response.json();
        setProducts(Array.isArray(data.data) ? data.data : []);
      } catch (err) {
        if (err.name !== "AbortError") {
          setError(err.message || "Terjadi kesalahan.");
        }
      } finally {
        setLoading(false);
      }
    }

    loadProducts();
  }, []);

  useEffect(() => {
    if (!authToken) {
      return;
    }

    async function loadCurrentUser() {
      try {
        const response = await fetch(`${API_BASE_URL}/api/auth/me`, {
          headers: { Authorization: `Bearer ${authToken}` },
        });

        if (!response.ok) {
          throw new Error("Unauthorized");
        }

        const data = await response.json();
        setCurrentUser(data.user);
      } catch {
        localStorage.removeItem("authToken");
        setAuthToken("");
        setCurrentUser(null);
      }
    }

    loadCurrentUser();
  }, [authToken]);

  const activeProducts = useMemo(() => {
    const keyword = search.trim().toLowerCase();

    return products
      .filter((product) => product.is_active)
      .filter((product) => {
        if (!keyword) {
          return true;
        }

        return [product.name, product.description, product.sku, product.category]
          .join(" ")
          .toLowerCase()
          .includes(keyword);
      });
  }, [products, search]);

  const cartItems = useMemo(() => {
    return Object.entries(cart)
      .map(([productId, quantity]) => {
        const product = products.find((item) => String(item.id) === String(productId));
        if (!product) {
          return null;
        }

        return { product, quantity };
      })
      .filter(Boolean);
  }, [cart, products]);

  const cartCount = useMemo(() => {
    return Object.values(cart).reduce((sum, value) => sum + Number(value), 0);
  }, [cart]);

  const cartSubtotal = useMemo(() => {
    return cartItems.reduce((sum, item) => sum + Number(item.product.price || 0) * Number(item.quantity), 0);
  }, [cartItems]);

  const openProductModal = (product) => {
    setSelectedProduct(product);
    setProductModalOpen(true);
    setCartOpen(false);
  };

  const addToCart = (productId, quantity = 1) => {
    setCart((current) => {
      const next = { ...current };
      next[productId] = Number(next[productId] || 0) + Number(quantity || 1);
      return next;
    });
    setProductModalOpen(false);
  };

  const updateCartQuantity = (productId, quantity) => {
    setCart((current) => ({ ...current, [productId]: Math.max(1, Number(quantity || 1)) }));
  };

  const removeCartItem = (productId) => {
    setCart((current) => {
      const next = { ...current };
      delete next[productId];
      return next;
    });
  };

  const clearCart = () => {
    setCart({});
  };

  const submitLogin = async (event) => {
    event.preventDefault();
    setAuthLoading(true);
    setAuthMessage("");

    try {
      const response = await fetch(`${API_BASE_URL}/api/auth/login`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(loginForm),
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data?.message || "Login gagal.");
      }

      localStorage.setItem("authToken", data.token);
      setAuthToken(data.token);
      setCurrentUser(data.user);
      setAuthMessage("Login berhasil.");
      setAuthModalOpen(false);
      setLoginForm(DEFAULT_LOGIN_FORM);
    } catch (err) {
      setAuthMessage(err.message || "Login gagal.");
    } finally {
      setAuthLoading(false);
    }
  };

  const submitRegister = async (event) => {
    event.preventDefault();
    setAuthLoading(true);
    setAuthMessage("");

    try {
      const response = await fetch(`${API_BASE_URL}/api/auth/register`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(registerForm),
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data?.message || "Registrasi gagal.");
      }

      localStorage.setItem("authToken", data.token);
      setAuthToken(data.token);
      setCurrentUser(data.user);
      setAuthMessage("Registrasi berhasil.");
      setAuthModalOpen(false);
      setRegisterForm(DEFAULT_REGISTER_FORM);
    } catch (err) {
      setAuthMessage(err.message || "Registrasi gagal.");
    } finally {
      setAuthLoading(false);
    }
  };

  const submitLogout = async () => {
    try {
      await fetch(`${API_BASE_URL}/api/auth/logout`, {
        method: "POST",
        headers: { Authorization: `Bearer ${authToken}` },
      });
    } finally {
      localStorage.removeItem("authToken");
      setAuthToken("");
      setCurrentUser(null);
    }
  };

  const submitContact = (event) => {
    event.preventDefault();
    setAuthMessage("Pesan kontak disiapkan di frontend. Jika ingin, saya bisa lanjut hubungkan ke API email/DB.");
  };

  return (
    <div className="min-h-screen bg-white text-gray-800 transition-colors duration-300 dark:bg-gray-900 dark:text-gray-200">
      <nav className="border-b border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div className="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
          <a href="#" className="flex items-center gap-2">
            <img src="/images/icon.png" alt="Interco" className="h-8 w-8 object-contain" />
            <span className="text-xl font-bold text-gray-900 dark:text-white">Interco</span>
          </a>

          <div className="hidden w-full max-w-lg flex-1 px-8 md:block">
            <div className="relative">
              <input
                type="text"
                value={search}
                onChange={(event) => setSearch(event.target.value)}
                placeholder="Cari produk..."
                className="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200"
              />
              <svg className="pointer-events-none absolute right-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>

          <div className="flex items-center gap-3">
            <button
              type="button"
              onClick={() => setDarkMode((value) => !value)}
              className="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
            >
              {darkMode ? "☀" : "☾"}
            </button>
            <button
              type="button"
              onClick={() => setCartOpen(true)}
              className="relative flex h-9 w-9 items-center justify-center rounded-full bg-gray-200 text-gray-700 transition hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
              title="Keranjang"
            >
              {cartCount > 0 && (
                <span className="absolute -right-1 -top-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-purple-600 px-1 text-[10px] font-bold text-white">
                  {cartCount}
                </span>
              )}
              <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
              </svg>
            </button>

            {currentUser ? (
              <div className="relative">
                <button
                  type="button"
                  onClick={() => setProfileMenuOpen((value) => !value)}
                  className="flex h-9 w-9 items-center justify-center rounded-full bg-purple-100 text-purple-700 transition hover:bg-purple-200 dark:bg-purple-900 dark:text-purple-200 dark:hover:bg-purple-800"
                  title="Profil"
                >
                  <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                </button>

                {profileMenuOpen && (
                  <div className="absolute right-0 mt-2 w-48 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xl dark:border-gray-700 dark:bg-gray-800">
                    <div className="border-b border-gray-100 px-4 py-3 dark:border-gray-700">
                      <div className="text-sm font-semibold text-gray-900 dark:text-white">{currentUser.name}</div>
                      <div className="text-xs text-gray-500 dark:text-gray-400">{currentUser.email}</div>
                    </div>
                    <button
                      type="button"
                      onClick={() => {
                        setProfileMenuOpen(false);
                        setAuthMessage("Halaman profil bisa disambungkan ke endpoint profil React berikutnya.");
                      }}
                      className="block w-full px-4 py-3 text-left text-sm text-gray-700 transition hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                    >
                      Profil
                    </button>
                    <button
                      type="button"
                      onClick={() => {
                        setProfileMenuOpen(false);
                        submitLogout();
                      }}
                      className="block w-full px-4 py-3 text-left text-sm text-gray-700 transition hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                    >
                      Logout
                    </button>
                  </div>
                )}
              </div>
            ) : (
              <button
                type="button"
                onClick={() => setAuthModalOpen(true)}
                className="rounded-lg bg-purple-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-purple-700"
              >
                Masuk
              </button>
            )}
          </div>
        </div>
      </nav>

      <section className="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white dark:from-black dark:via-gray-900 dark:to-black">
        <div className="absolute inset-0 opacity-10">
          <div className="absolute inset-0" style={{ backgroundImage: "url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.15&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')" }}></div>
        </div>
        <div className="relative mx-auto grid max-w-7xl items-center gap-12 px-4 py-20 sm:px-6 md:grid-cols-2 md:py-28 lg:px-8">
          <div>
            <span className="mb-6 inline-block rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider">
              Custom Order
            </span>
            <h1 className="mb-4 text-4xl font-bold leading-tight md:text-5xl">
              Wujudkan <span className="text-purple-300">Pakaian Impian</span> Anda
            </h1>
            <p className="mb-8 text-lg leading-relaxed text-gray-300">
              Desain pakaian custom sesuai keinginan Anda. Dari bahan, warna,
              hingga detail jahitan — semua bisa disesuaikan.
            </p>
            <div className="flex flex-wrap gap-4">
              <a href="#produk" className="inline-flex items-center gap-2 rounded-lg bg-white px-6 py-3 font-semibold text-gray-900 transition hover:bg-gray-100">
                Lihat Katalog
              </a>
              <button
                type="button"
                onClick={() => {
                  setCartOpen(false);
                  setProductModalOpen(false);
                }}
                className="inline-flex items-center gap-2 rounded-lg border border-white/30 px-6 py-3 font-semibold text-white transition hover:bg-white/10"
              >
                Custom Order
              </button>
            </div>
          </div>
          <div className="hidden justify-center md:flex">
            <div className="relative">
              <div className="w-72 h-72 rounded-full bg-gradient-to-br from-purple-800/10 to-indigo-700/8 flex items-center justify-center">
                <svg className="w-40 h-40 text-white/80" fill="none" stroke="currentColor" strokeWidth="1" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
              </div>
              <div className="absolute -top-2 -right-2 w-16 h-16 bg-purple-800/12 rounded-full blur-xl"></div>
              <div className="absolute -bottom-4 -left-4 w-20 h-20 bg-indigo-700/10 rounded-full blur-xl"></div>
            </div>
          </div>
        </div>
      </section>

      <section className="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between mb-8">
          <div>
            <h2 className="text-2xl font-bold text-gray-800 dark:text-gray-100">Kategori</h2>
            <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">Pilih jenis pakaian yang ingin Anda custom</p>
          </div>
        </div>
        <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-5">
          {CATEGORIES.map((category) => (
            <button key={category.name} type="button" className="group flex flex-col items-center p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl hover:border-gray-900 dark:hover:border-gray-400 hover:shadow-lg transition-all duration-300">
              <div className="w-14 h-14 flex items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-700 group-hover:bg-gray-900 dark:group-hover:bg-white transition-all duration-300 mb-3">
                <span className="text-xl text-gray-700 dark:text-gray-300 group-hover:text-white dark:group-hover:text-gray-900 transition-colors duration-300">{category.icon}</span>
              </div>
              <span className="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">{category.name}</span>
            </button>
          ))}
        </div>
      </section>

      <section id="produk" className="bg-gray-50 dark:bg-gray-800/50 py-14">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-center justify-between mb-8">
            <div>
              <h2 className="text-2xl font-bold text-gray-800 dark:text-gray-100">Produk Custom Populer</h2>
              <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">Inspirasi desain dari pesanan pelanggan kami</p>
            </div>
            <span className="text-sm text-gray-500 dark:text-gray-400">{activeProducts.length} item</span>
          </div>

          {loading && <p className="rounded-xl border border-gray-200 bg-white p-4 text-sm dark:border-gray-700 dark:bg-gray-800">Memuat produk...</p>}
          {error && <p className="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-900/40 dark:bg-red-900/20 dark:text-red-300">{error}</p>}

          {!loading && !error && activeProducts.length === 0 && (
            <div className="rounded-2xl border border-dashed border-gray-300 bg-white p-8 text-center text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
              Tidak ada produk yang cocok dengan pencarian.
            </div>
          )}

          <div className="grid grid-cols-2 gap-6 sm:grid-cols-3 md:grid-cols-4">
            {activeProducts.map((product) => (
              <article key={product.id} className="group overflow-hidden rounded-2xl border border-gray-200 bg-white transition-all duration-300 hover:border-gray-400 hover:shadow-lg dark:border-gray-700 dark:bg-gray-800 dark:hover:border-gray-500">
                <div className="relative aspect-square bg-gray-100 dark:bg-gray-700">
                  {product.category && (
                    <span className="absolute left-3 top-3 rounded-full bg-gray-900 px-2.5 py-1 text-xs font-semibold text-white dark:bg-white dark:text-gray-900">
                      {product.category}
                    </span>
                  )}
                  <img
                    src={normalizeImageUrl(product)}
                    alt={product.name}
                    loading="lazy"
                    className="h-full w-full object-cover"
                    onError={(event) => {
                      event.currentTarget.src = "/images/items/1.png";
                    }}
                  />
                </div>
                <div className="p-4">
                  <h3 className="mb-1 text-sm font-semibold text-gray-800 transition group-hover:text-gray-900 dark:text-gray-200 dark:group-hover:text-white">{product.name}</h3>
                  <p className="mb-3 line-clamp-2 text-xs text-gray-500 dark:text-gray-400">{product.description || "Produk custom premium"}</p>
                  <div className="flex items-center justify-between">
                    <p className="text-base font-bold text-gray-900 dark:text-white">{formatRupiah(Number(product.price || 0))}</p>
                    <button type="button" onClick={() => openProductModal(product)} className="rounded-lg bg-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-700 transition hover:bg-gray-900 hover:text-white dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-white dark:hover:text-gray-900">
                      Detail
                    </button>
                  </div>
                </div>
              </article>
            ))}
          </div>
        </div>
      </section>

      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <h2 className="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-3 text-center">Kenapa Custom di Interco?</h2>
        <p className="text-sm text-gray-500 dark:text-gray-400 text-center mb-10 max-w-xl mx-auto">Kami menghadirkan pengalaman custom order yang mudah, cepat, dan berkualitas tinggi</p>
        <div className="grid md:grid-cols-3 gap-8">
          <div className="text-center p-6 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-500 hover:shadow-lg transition-all duration-300">
            <div className="w-14 h-14 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 rounded-xl flex items-center justify-center mx-auto mb-4">
              <svg className="w-7 h-7" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42" /></svg>
            </div>
            <h3 className="font-semibold text-lg mb-2 text-gray-800 dark:text-gray-100">Desain Bebas</h3>
            <p className="text-gray-500 dark:text-gray-400 text-sm">Upload desain sendiri atau konsultasi dengan tim desainer kami untuk hasil terbaik.</p>
          </div>
          <div className="text-center p-6 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-500 hover:shadow-lg transition-all duration-300">
            <div className="w-14 h-14 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 rounded-xl flex items-center justify-center mx-auto mb-4">
              <svg className="w-7 h-7" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" d="M11.42 15.17l-5.385 3.17a.75.75 0 01-1.088-.79l1.028-5.99-4.353-4.242a.75.75 0 01.416-1.279l6.015-.874L11.065.93a.75.75 0 011.37 0l2.692 5.455 6.015.874a.75.75 0 01.416 1.28l-4.353 4.24 1.028 5.99a.75.75 0 01-1.088.791L12 15.17l-5.385 3.17z" /></svg>
            </div>
            <h3 className="font-semibold text-lg mb-2 text-gray-800 dark:text-gray-100">Bahan Premium</h3>
            <p className="text-gray-500 dark:text-gray-400 text-sm">Hanya menggunakan bahan berkualitas tinggi yang nyaman dan tahan lama.</p>
          </div>
          <div className="text-center p-6 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-500 hover:shadow-lg transition-all duration-300">
            <div className="w-14 h-14 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 rounded-xl flex items-center justify-center mx-auto mb-4">
              <svg className="w-7 h-7" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" /></svg>
            </div>
            <h3 className="font-semibold text-lg mb-2 text-gray-800 dark:text-gray-100">Pengerjaan Cepat</h3>
            <p className="text-gray-500 dark:text-gray-400 text-sm">Proses produksi 3-7 hari kerja dengan pengiriman ke seluruh Indonesia.</p>
          </div>
        </div>
      </section>

      <section className="bg-gray-50 dark:bg-gray-800/50 py-14">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-10">
            <h2 className="text-2xl font-bold text-gray-800 dark:text-gray-100">Review Pengguna Terbaru</h2>
            <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">Apa kata pelanggan kami tentang layanan Interco</p>
          </div>
          <div className="grid md:grid-cols-3 gap-6">
            {REVIEWS.map((review) => (
              <div key={review.name} className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all duration-300">
                <div className="flex items-center gap-3 mb-4">
                  <div className="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center text-purple-600 dark:text-purple-300 font-bold text-sm">
                    {review.name[0]}
                  </div>
                  <div>
                    <h4 className="text-sm font-semibold text-gray-800 dark:text-gray-200">{review.name}</h4>
                    <div className="mt-0.5 text-yellow-400 text-xs">{"★".repeat(review.stars)}{"☆".repeat(5 - review.stars)}</div>
                  </div>
                </div>
                <p className="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">{review.desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      <section id="contact" className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div className="max-w-2xl mx-auto">
          <div className="text-center mb-10">
            <h2 className="text-2xl font-bold text-gray-800 dark:text-gray-100">Kritik dan Saran</h2>
            <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">Bantu kami meningkatkan layanan dengan masukan Anda</p>
          </div>
          <form onSubmit={submitContact} className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 md:p-8 space-y-5">
            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Pengirim</label>
              <input
                type="email"
                value={contactForm.email}
                onChange={(event) => setContactForm((current) => ({ ...current, email: event.target.value }))}
                required
                placeholder="contoh@email.com"
                className="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-4 text-sm text-gray-800 placeholder-gray-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Username</label>
              <label className="mb-2 inline-flex items-center gap-2 select-none">
                <input
                  type="checkbox"
                  checked={contactForm.anonim}
                  onChange={(event) => setContactForm((current) => ({ ...current, anonim: event.target.checked, username: event.target.checked ? "Anonim" : "" }))}
                  className="h-4 w-4 rounded border-gray-300 text-purple-600 focus:ring-purple-500 dark:border-gray-600 dark:bg-gray-700"
                />
                <span className="text-sm text-gray-600 dark:text-gray-400">Kirim sebagai Anonim</span>
              </label>
              <input
                type="text"
                value={contactForm.username}
                onChange={(event) => setContactForm((current) => ({ ...current, username: event.target.value }))}
                disabled={contactForm.anonim}
                placeholder="Nama tampilan Anda"
                className="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-4 text-sm text-gray-800 placeholder-gray-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500/20 disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Komentar</label>
              <textarea
                value={contactForm.komentar}
                onChange={(event) => setContactForm((current) => ({ ...current, komentar: event.target.value }))}
                rows="4"
                required
                placeholder="Tulis kritik atau saran Anda di sini..."
                className="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-4 text-sm text-gray-800 placeholder-gray-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200"
              />
            </div>

            <div className="text-right">
              <button type="submit" className="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-purple-700">
                Kirim
              </button>
            </div>
          </form>
        </div>
      </section>

      <footer className="bg-gray-900 text-gray-400 dark:bg-gray-950">
        <div className="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
          <div className="grid gap-8 md:grid-cols-3">
            <div>
              <h4 className="mb-3 flex items-center gap-2 text-lg font-bold text-white">
                <img src="/images/icon.png" alt="Interco" className="h-6 w-6 object-contain" />
                Interco
              </h4>
              <p className="text-sm">Belanja online terpercaya dengan produk berkualitas dan harga terjangkau.</p>
            </div>
            <div>
              <h4 className="mb-3 font-semibold text-white">Tautan</h4>
              <ul className="space-y-2 text-sm">
                <li>Tentang Kami</li>
                <li>Kebijakan Privasi</li>
                <li>Syarat & Ketentuan</li>
              </ul>
            </div>
            <div>
              <h4 className="mb-3 font-semibold text-white">Hubungi Kami</h4>
              <ul className="space-y-2 text-sm">
                <li>Email: info@interco.com</li>
                <li>Telepon: (021) 1234-5678</li>
              </ul>
            </div>
          </div>
          <div className="border-t border-gray-800 mt-8 pt-6 text-center text-sm">
            &copy; {new Date().getFullYear()} Interco. All rights reserved.
          </div>
        </div>
      </footer>

      {authModalOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
          <div className="absolute inset-0 bg-black/60" onClick={() => setAuthModalOpen(false)} />
          <div className="relative w-full max-w-4xl overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10">
            <button type="button" onClick={() => setAuthModalOpen(false)} className="absolute right-4 top-4 z-10 rounded-full bg-white/90 px-3 py-2 text-sm font-semibold text-gray-700 shadow-lg dark:bg-gray-800 dark:text-gray-200">
              Tutup
            </button>
            <div className="grid md:grid-cols-2">
              <div className="border-r border-gray-200 p-8 dark:border-gray-800">
                <a href="#" className="inline-flex items-center gap-2 text-lg font-semibold text-gray-900 dark:text-white">
                  <img src="/images/icon.png" alt="Interco" className="h-8 w-8 object-contain" />
                  Interco
                </a>
                <h2 className="mt-10 text-3xl font-bold leading-tight text-gray-900 dark:text-white">Masuk ke akun Anda</h2>
                <p className="mt-3 text-sm text-gray-600 dark:text-gray-300">Gunakan username atau email untuk melanjutkan pesanan custom Anda.</p>
                <div className="mt-8 rounded-xl border border-gray-200 bg-gray-50 p-4 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                  Belum punya akun? <button type="button" onClick={() => setAuthMode((current) => (current === "login" ? "register" : "login"))} className="font-semibold text-purple-700 hover:text-purple-800 dark:text-purple-300 dark:hover:text-purple-200">{authMode === "login" ? "Daftar sekarang" : "Masuk sekarang"}</button>
                </div>
              </div>

              <div className="p-4 md:p-6">
                {authMessage && (
                  <div className="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-900 dark:bg-green-950/40 dark:text-green-300">
                    {authMessage}
                  </div>
                )}

                {authMode === "login" ? (
                  <form onSubmit={submitLogin} className="space-y-4 p-2 md:p-4">
                    <div>
                      <label className="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Username atau Email</label>
                      <input
                        value={loginForm.login}
                        onChange={(event) => setLoginForm((current) => ({ ...current, login: event.target.value }))}
                        type="text"
                        required
                        className="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                      />
                    </div>
                    <div>
                      <label className="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Password</label>
                      <input
                        value={loginForm.password}
                        onChange={(event) => setLoginForm((current) => ({ ...current, password: event.target.value }))}
                        type="password"
                        required
                        className="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                      />
                    </div>
                    <label className="inline-flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                      <input
                        type="checkbox"
                        checked={loginForm.remember}
                        onChange={(event) => setLoginForm((current) => ({ ...current, remember: event.target.checked }))}
                        className="h-4 w-4 rounded border-gray-300 text-purple-600"
                      />
                      Ingat saya
                    </label>
                    <button type="submit" disabled={authLoading} className="w-full rounded-xl bg-gray-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-black disabled:opacity-60 dark:bg-purple-600 dark:hover:bg-purple-500">
                      {authLoading ? "Memproses..." : "Masuk"}
                    </button>
                  </form>
                ) : (
                  <form onSubmit={submitRegister} className="space-y-4 p-2 md:p-4">
                    <div>
                      <label className="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Username</label>
                      <input
                        value={registerForm.name}
                        onChange={(event) => setRegisterForm((current) => ({ ...current, name: event.target.value }))}
                        type="text"
                        required
                        className="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                      />
                    </div>
                    <div>
                      <label className="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                      <input
                        value={registerForm.email}
                        onChange={(event) => setRegisterForm((current) => ({ ...current, email: event.target.value }))}
                        type="email"
                        required
                        className="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                      />
                    </div>
                    <div>
                      <label className="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Password</label>
                      <input
                        value={registerForm.password}
                        onChange={(event) => setRegisterForm((current) => ({ ...current, password: event.target.value }))}
                        type="password"
                        required
                        className="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                      />
                    </div>
                    <div>
                      <label className="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Konfirmasi Password</label>
                      <input
                        value={registerForm.password_confirmation}
                        onChange={(event) => setRegisterForm((current) => ({ ...current, password_confirmation: event.target.value }))}
                        type="password"
                        required
                        className="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                      />
                    </div>
                    <button type="submit" disabled={authLoading} className="w-full rounded-xl bg-gray-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-black disabled:opacity-60 dark:bg-purple-600 dark:hover:bg-purple-500">
                      {authLoading ? "Memproses..." : "Daftar"}
                    </button>
                  </form>
                )}
              </div>
            </div>
          </div>
        </div>
      )}

      {productModalOpen && selectedProduct && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
          <div className="absolute inset-0 bg-black/60" onClick={() => setProductModalOpen(false)} />
          <div className="relative w-full max-w-4xl overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10">
            <button type="button" onClick={() => setProductModalOpen(false)} className="absolute right-4 top-4 z-10 rounded-full bg-white/90 px-3 py-2 text-sm font-semibold text-gray-700 shadow-lg dark:bg-gray-800 dark:text-gray-200">
              Tutup
            </button>
            <div className="grid md:grid-cols-2">
              <div className="aspect-square bg-gray-100 dark:bg-gray-800">
                <img src={normalizeImageUrl(selectedProduct)} alt={selectedProduct.name} className="h-full w-full object-cover" />
              </div>
              <div className="p-6 md:p-8">
                <div className="mb-4 flex items-center gap-2">
                  <span className="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700 dark:bg-purple-900 dark:text-purple-200">{selectedProduct.category || "Produk"}</span>
                  <span className="text-xs text-gray-500 dark:text-gray-400">{selectedProduct.stock} stok tersedia</span>
                </div>
                <h3 className="text-2xl font-bold text-gray-900 dark:text-white">{selectedProduct.name}</h3>
                <p className="mt-3 text-sm leading-relaxed text-gray-600 dark:text-gray-300">{selectedProduct.description}</p>

                <div className="mt-6 grid grid-cols-2 gap-3 text-sm">
                  <div className="rounded-2xl bg-gray-50 p-4 dark:bg-gray-800">
                    <div className="text-gray-500 dark:text-gray-400">Harga</div>
                    <div className="mt-1 font-bold text-gray-900 dark:text-white">{formatRupiah(Number(selectedProduct.price || 0))}</div>
                  </div>
                  <div className="rounded-2xl bg-gray-50 p-4 dark:bg-gray-800">
                    <div className="text-gray-500 dark:text-gray-400">Satuan</div>
                    <div className="mt-1 font-bold text-gray-900 dark:text-white">{selectedProduct.unit}</div>
                  </div>
                </div>

                <div className="mt-6 rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                  <div className="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Detail</div>
                  <p className="mt-2 text-sm text-gray-700 dark:text-gray-300">{selectedProduct.specifications || "Belum ada spesifikasi tambahan."}</p>
                </div>

                <div className="mt-6 flex flex-col gap-3 sm:flex-row sm:items-end">
                  <div className="w-full sm:w-24">
                    <label className="mb-1 block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Qty</label>
                    <input id="product-qty" type="number" min="1" defaultValue="1" className="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500/20 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200" />
                  </div>
                  <button type="button" onClick={() => addToCart(selectedProduct.id, document.getElementById("product-qty")?.value || 1)} className="inline-flex items-center justify-center gap-2 rounded-xl bg-purple-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-purple-700">
                    Masukkan ke Keranjang
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      )}

      {cartOpen && (
        <div className="fixed inset-0 z-50">
          <div className="absolute inset-0 bg-black/50" onClick={() => setCartOpen(false)} />
          <div className="absolute right-0 top-0 h-full w-full max-w-md overflow-y-auto bg-white shadow-2xl dark:bg-gray-900">
            <div className="flex items-center justify-between border-b border-gray-200 px-5 py-4 dark:border-gray-700">
              <div>
                <h3 className="text-lg font-bold text-gray-900 dark:text-white">Keranjang</h3>
                <p className="text-xs text-gray-500 dark:text-gray-400">{cartCount} item</p>
              </div>
              <button type="button" onClick={() => setCartOpen(false)} className="flex h-9 w-9 items-center justify-center rounded-full bg-gray-100 text-gray-700 transition hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                ✕
              </button>
            </div>

            <div className="space-y-4 p-5">
              {cartItems.length === 0 && (
                <div className="rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-6 text-center text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-400">
                  Keranjang masih kosong.
                </div>
              )}

              {cartItems.map(({ product, quantity }) => (
                <div key={product.id} className="flex gap-4 rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                  <img src={normalizeImageUrl(product)} alt={product.name} className="h-20 w-20 rounded-xl object-cover" />
                  <div className="min-w-0 flex-1">
                    <h4 className="truncate text-sm font-semibold text-gray-900 dark:text-white">{product.name}</h4>
                    <p className="mt-1 text-xs text-gray-500 dark:text-gray-400">{formatRupiah(Number(product.price || 0))}</p>
                    <div className="mt-3 flex items-center gap-2">
                      <input type="number" min="1" value={quantity} onChange={(event) => updateCartQuantity(product.id, event.target.value)} className="w-16 rounded-lg border border-gray-300 bg-white px-2 py-1 text-sm text-gray-800 focus:border-purple-500 focus:outline-none dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200" />
                      <button type="button" onClick={() => removeCartItem(product.id)} className="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 transition hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Hapus</button>
                    </div>
                  </div>
                </div>
              ))}

              <div className="rounded-2xl bg-gray-50 p-4 dark:bg-gray-800">
                <div className="flex items-center justify-between text-sm text-gray-600 dark:text-gray-300">
                  <span>Subtotal</span>
                  <span className="font-semibold text-gray-900 dark:text-white">{formatRupiah(cartSubtotal)}</span>
                </div>
                <div className="mt-4 flex items-center gap-3">
                  <button type="button" onClick={() => setCartOpen(false)} className="flex-1 rounded-xl border border-gray-300 px-4 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Lanjut Belanja</button>
                  <button type="button" onClick={clearCart} className="rounded-xl bg-gray-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-gray-800 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100">Kosongkan</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      )}

      <div className="hidden" aria-hidden="true">{authMessage}</div>
    </div>
  );
}
