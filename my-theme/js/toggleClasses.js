const toggleProduct = (labelElementId, checkboxElementId) => {
  const checkboxElement = document.getElementById(checkboxElementId);
  const labelElement = document.getElementById(labelElementId);
  if (!checkboxElement.checked) {
    labelElement.classList.add("selected_product");
  } else {
    labelElement.classList.remove("selected_product");
  }
};

function toggleNavBar(elementId) {
  const navMenu = document.getElementById(elementId);

  navMenu.classList.toggle("car_supshop_nav_menu_open");
}
