(() => {
  const sections = document.querySelectorAll(".cta-box");

  sections.forEach((section) => {
    const video = section.querySelector(".cta-box__bg--video[data-src]");
    if (!video) return;

    const playVideo = () => {
      if (video.dataset.poster) {
        video.poster = video.dataset.poster;
        video.removeAttribute("data-poster");
      }

      if (video.dataset.src) {
        video.src = video.dataset.src;
        video.removeAttribute("data-src");
        video.load();
      }

      const playPromise = video.play();
      if (playPromise) {
        playPromise.catch((error) => {
          console.warn("CTA background video could not play:", error);
        });
      }
    };

    if (!("IntersectionObserver" in window)) {
      playVideo();
      return;
    }

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            playVideo();
          } else {
            video.pause();
          }
        });
      },
      { threshold: 0.1 },
    );

    observer.observe(section);
  });
})();
