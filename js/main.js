// wait for DOM content to be fully loaded before running script
document.addEventListener("DOMContentLoaded", () => {
  // --- CART LOGIC ---
  // get cart drawer, overlay, open and close elements by id
  const cartDrawer = document.getElementById("cartDrawer");
  const cartOverlay = document.getElementById("cartOverlay");
  const openCartBtn = document.getElementById("openCart");
  const closeCartBtn = document.getElementById("closeCart");

  // function to toggle cart drawer open and closed state
  const toggleCart = () => {
    // check if cart drawer is currently closed (off-screen)
    const isClosed = cartDrawer.classList.contains("translate-x-full");

    if (isClosed) {
      // open cart drawer, show overlay, and lock background scrolling
      cartDrawer.classList.remove("translate-x-full");
      cartOverlay.classList.remove("hidden");
      document.body.style.overflow = "hidden"; // prevent scrolling
    } else {
      // close cart drawer, hide overlay, and restore scrolling
      cartDrawer.classList.add("translate-x-full");
      cartOverlay.classList.add("hidden");
      document.body.style.overflow = "auto";
    }
  };

  // attach event listeners to open, close, and overlay elements if they exist
  if (openCartBtn) openCartBtn.addEventListener("click", toggleCart);
  if (closeCartBtn) closeCartBtn.addEventListener("click", toggleCart);
  if (cartOverlay) cartOverlay.addEventListener("click", toggleCart);

  // --- SEARCH LOGIC ---
  // get search modal, open and close elements by id
  const searchModal = document.getElementById("searchModal");
  const openSearchBtn = document.getElementById("openSearch");
  const closeSearchBtn = document.getElementById("closeSearch");

  // function to toggle search modal visibility
  const toggleSearch = () => {
    // check if search modal is currently hidden
    if (searchModal.classList.contains("hidden")) {
      // show search modal and lock background scrolling
      searchModal.classList.remove("hidden");
      document.body.style.overflow = "hidden";
      // auto-focus the search input field after a short timeout
      setTimeout(() => searchModal.querySelector("input").focus(), 100);
    } else {
      // hide search modal and restore scrolling
      searchModal.classList.add("hidden");
      document.body.style.overflow = "auto";
    }
  };

  // attach event listeners to search open and close buttons if they exist
  if (openSearchBtn) openSearchBtn.addEventListener("click", toggleSearch);
  if (closeSearchBtn) closeSearchBtn.addEventListener("click", toggleSearch);
});
