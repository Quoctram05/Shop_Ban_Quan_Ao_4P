document.addEventListener("DOMContentLoaded", function() {
  const searchInput = document.getElementById("search");
  const suggestionBox = document.getElementById("suggestions");

  searchInput.addEventListener("keyup", function() {
    const query = this.value.trim();
    if (query.length < 1) {
      suggestionBox.style.display = "none";
      return;
    }

    fetch(`http://localhost/Shop_Ban_Quan_Ao_4P/public/search_suggestion.php?q=${encodeURIComponent(query)}`)
      .then(res => res.json())
      .then(data => {
        suggestionBox.innerHTML = "";
        if (data.length > 0) {
          data.forEach(item => {
            const div = document.createElement("div");
            div.textContent = item;
            div.onclick = () => {
              searchInput.value = item;
              suggestionBox.style.display = "none";
              document.querySelector(".search-box form").submit();
            };
            suggestionBox.appendChild(div);
          });
          suggestionBox.style.display = "block";
        } else {
          suggestionBox.style.display = "none";
        }
      })
      .catch(err => console.error(err));
  });

  // Ẩn gợi ý khi click ra ngoài
  document.addEventListener("click", function(e) {
    if (!document.querySelector(".search-box").contains(e.target)) {
      suggestionBox.style.display = "none";
    }
  });
});
