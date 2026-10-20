if (!window.loadSplide) {
  window.loadSplide = (callback) => {
    if (typeof Splide !== "undefined") return callback();
    window.__splideCallbacks = window.__splideCallbacks || [];
    window.__splideCallbacks.push(callback);
    if (window.__splideLoading) return;
    window.__splideLoading = true;
    // On failure reset the loading flag and keep the pending callbacks, so a later call can retry
    const fail = (message) => {
      window.__splideLoading = false;
      console.error(message);
    };
    if (!window.splideData || !window.splideData.url) {
      fail("Splide data not available");
      return;
    }
    const script = document.createElement("script");
    script.src = splideData.url;
    script.onload = () => {
      const checkSplide = (attempts = 0) => {
        if (typeof Splide !== "undefined") {
          window.__splideCallbacks.forEach((fn) => fn());
          window.__splideCallbacks = [];
          return;
        }
        if (attempts < 10) {
          // Retry up to 10 times
          setTimeout(() => checkSplide(attempts + 1), 100); // Check every 100ms
        } else {
          fail("Splide failed to load after retries");
        }
      };
      checkSplide();
    };
    script.onerror = () => {
      script.remove();
      fail("Failed to load Splide script");
    };
    document.head.appendChild(script);
  };
}

(() => {
  const postsCarousels = document.querySelectorAll(".posts-carousel__carousel");
  if (!postsCarousels.length) return;

  const initCarousel = (carousel) => {
    const carouselType = carousel.dataset.type;
    const splideElement = carousel.querySelector(".splide");

    // Each team carousel points to its own thumbnails carousel through data-thumbnails
    const thumbnailsCarousel = carousel.dataset.thumbnails
      ? document.getElementById(carousel.dataset.thumbnails)
      : null;

    let perPageTablet =
      carouselType === "testimonial" || carouselType === "team"
        ? 1
        : carouselType === "case-result" || carouselType === "post"
          ? 2
          : 3;

    let perPageLdpi =
      carouselType === "team"
        ? 1
        : carouselType === "post"
          ? 3
          : carouselType === "testimonial" || carouselType === "case-result"
            ? 2
            : 4;

    const focusFor = (n) => (n % 2 === 1 ? "center" : false);
    const trimFor = (n) => (n % 2 === 1 ? true : false);

    if (carousel.closest(".sidebar")) {
      perPageTablet = 1;
      perPageLdpi = 1;
    }

    if (!splideElement) return;

    loadSplide(() => {
      let main = new Splide(splideElement, {
        type: "loop",
        perPage: 1,
        perMove: 1,
        arrows: true,
        pagination: false,
        updateOnMove: carouselType === "team",
        speed: 400,
        mediaQuery: "min",
        breakpoints: {
          [window.themeBreakpoints?.tablet ?? 768]: {
            perPage: perPageTablet,
            focus: focusFor(perPageTablet),
            trimSpace: trimFor(perPageTablet),
          },
          [window.themeBreakpoints?.ldpi ?? 1024]: {
            perPage: perPageLdpi,
            focus: focusFor(perPageLdpi),
            trimSpace: trimFor(perPageLdpi),
          },
        },
      });

      if (thumbnailsCarousel) {
        let thumbnails = new Splide(thumbnailsCarousel, {
          fixedWidth: 220,
          rewind: true,
          arrows: true,
          pagination: false,
          isNavigation: true,
          updateOnMove: true,
          speed: 400,
          mediaQuery: "min",
        });
        main.sync(thumbnails);
        main.mount();
        thumbnails.mount();
      } else {
        main.mount();
      }
    });
  };

  const observer = new IntersectionObserver(
    (entries, obs) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        initCarousel(entry.target);
        obs.unobserve(entry.target);
      });
    },
    { rootMargin: "200px 0px" },
  );

  postsCarousels.forEach((carousel) => observer.observe(carousel));
})();
