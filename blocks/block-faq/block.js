(function () {
  const lists = document.querySelectorAll(".faq__items");
  if (!lists.length) return;

  // Each FAQ block splits its own items into two columns on tablet and up, so several blocks on a page don't clash
  const faqLists = Array.from(lists).map((list) => {
    const cols = list.querySelectorAll(":scope > .faq__col");

    return {
      list,
      col1: cols[0],
      col2: cols[1],
      items: Array.from(list.querySelectorAll(".faq__item")),
    };
  });

  function orderItems({ list, col1, col2, items }) {
    if (!col1 || !col2) return;

    const isMobile = window.innerWidth < 768;

    if (isMobile) {
      col1.hidden = true;
      col2.hidden = true;
      items.forEach((item) => list.appendChild(item));
    } else {
      col1.hidden = false;
      col2.hidden = false;
      col1.replaceChildren();
      col2.replaceChildren();
      items.forEach((item, i) => {
        (i % 2 === 0 ? col1 : col2).appendChild(item);
      });
    }
  }

  faqLists.forEach(orderItems);

  let resizeTimer;
  window.addEventListener("resize", function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => faqLists.forEach(orderItems), 150);
  });
})();
