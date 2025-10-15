
/* =================== SEARCH GỢI Ý =================== */
(() => {
  const searchInput = document.getElementById("search-input");
  const suggestBox = document.querySelector(".suggest-box");
  if (!searchInput || !suggestBox) return;

  const data = {
    hoodie: ["AH007", "AH006", "AH008", "AH005", "AH004", "AS004"],
    khoac: ["AK075", "AK074", "AK073", "AK064", "AK059", "AK062", "AK068"],
    len: ["AL014", "AL012", "AL013", "AL011", "AL010", "AL015"],
    polo: ["PO170", "PO169", "PO168"],
    somi: ["SM179", "SM195", "SM194", "SM193", "SM181", "SM170"],
    thun: ["AT179", "AT177", "AT176", "AT174", "AT172", "AT170"],
    vest: ["AV031", "AV032", "AG036"],
    cavat: ["CV082", "CV081", "CV080", "NO020", "NO019"],
    giay: ["GI021", "GI020", "GI019"],
    non: ["MU010", "MU009", "MU008"],
    tui: ["TX018", "TX014", "TX020"],
    vo: ["VO063", "VO061", "VO058"],
    belt: ["TL195", "TL194", "TL193"],
    wallet: ["BV060", "BV059", "BV058"],
    khuyenmai: ["QS080", "QS079", "PO170", "AT177"],
    jeans: ["QJ114", "QJ117", "QJ115"],
    kaki: ["QK031", "QK028", "QK023"],
    jogger: ["JO016", "JO015", "JO013"],
    tay: ["QT069", "QT068", "QT031"],
    short: ["QS080", "QS076", "QS072"]
  };

  const allItems = Object.entries(data).flatMap(([type, codes]) =>
    codes.map(c => `${type.toUpperCase()} ${c}`)
  );

  searchInput.addEventListener("input", () => {
    const value = searchInput.value.toLowerCase().trim();
    suggestBox.innerHTML = "";
    if (!value) return (suggestBox.style.display = "none");

    const results = allItems.filter(i => i.toLowerCase().includes(value));
    if (results.length) {
      results.forEach(r => {
        const a = document.createElement("a");
        a.href = "#";
        a.textContent = r;
        suggestBox.appendChild(a);
      });
    } else {
      suggestBox.innerHTML = "<a>Không tìm thấy kết quả</a>";
    }
    suggestBox.style.display = "flex";
  });

  document.addEventListener("click", e => {
    if (!searchInput.contains(e.target) && !suggestBox.contains(e.target)) {
      suggestBox.style.display = "none";
    }
  });
})();

/* =================== PHÂN TRANG =================== */
(() => {
  const pageBtns = document.querySelectorAll(".page-btn");
  if (pageBtns.length === 0) return;

  pageBtns.forEach(btn => {
    btn.addEventListener("click", () => {
      pageBtns.forEach(b => b.classList.remove("active"));
      btn.classList.add("active");
      // nếu bạn có API → load dữ liệu ở đây
    });
  });
})();

/* =================== SLIDER VỚI THANH TIẾN TRÌNH =================== */
(() => {
  let index = 0;
  const slides = document.querySelectorAll(".slide");
  const slideContainer = document.querySelector(".slides");
  const total = slides.length;
  const progress = document.querySelector(".progress");
  const nextBtn = document.querySelector(".next");
  const prevBtn = document.querySelector(".prev");

  if (!slideContainer || total === 0) return;

  const duration = 5000; // 5 giây mỗi ảnh
  let slideTimer;

  // ====== Hiển thị slide ======
  function showSlide(i) {
    slideContainer.style.transform = `translateX(-${i * 100}%)`;
  }

  // ====== Thanh tiến trình ======
  function startProgress() {
    progress.style.transition = "none";
    progress.style.width = "0%";
    setTimeout(() => {
      progress.style.transition = `width ${duration}ms linear`;
      progress.style.width = "100%";
    }, 50);
  }

  // ====== Reset toàn bộ chu kỳ (tiến trình + thời gian) ======
  function restartCycle() {
    clearTimeout(slideTimer);
    startProgress();
    slideTimer = setTimeout(() => {
      nextSlide();
    }, duration);
  }

  // ====== Qua / lùi slide ======
  function nextSlide() {
    index = (index + 1) % total;
    showSlide(index);
    restartCycle();
  }
  function prevSlide() {
    index = (index - 1 + total) % total;
    showSlide(index);
    restartCycle();
  }

  // ====== Event click ======
  nextBtn?.addEventListener("click", nextSlide);
  prevBtn?.addEventListener("click", prevSlide);

  // ====== Khởi động ======
  showSlide(index);
  restartCycle();
})();
  // ====== Pagination Dots ======
  const dots = document.querySelectorAll('.dot');

  function updateDots() {
    dots.forEach((dot, i) => {
      dot.classList.toggle('active', i === index);
    });
  }

  // Khi slide đổi → cập nhật dot
  function showSlide(i) {
    slideContainer.style.transform = `translateX(-${i * 100}%)`;
    updateDots();
  }

  // Cho phép click vào dot để đổi ảnh
  dots.forEach((dot, i) => {
    dot.addEventListener('click', () => {
      index = i;
      showSlide(index);
      restartCycle(); // reset thời gian + thanh tiến trình
    });
  });
