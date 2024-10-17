jQuery(document).ready(function() {  
  const tabs = document.querySelectorAll(".tab");
  const tabContent = document.querySelectorAll(".tab-content");

  tabs.forEach((tab, i) => {
    tab.addEventListener("click", function () {
      tabs.forEach(tab => tab.classList.remove("active"));
      this.classList.add("active");
      tabContent.forEach(content => content.classList.add("hidden"));
      tabContent[i].classList.remove("hidden");
    });
  });
});