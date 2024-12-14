import Plyr from "plyr";

export default ($) => {
  function init() {
    setTimeout(() => {
      video_widget_implement();
    }, 1000);
  }

  async function video_widget_implement() {
    const settings = window.vdrv_settings.settings;

    if (!settings) {
      return;
    }

    load_styles(settings.css_link)

    settings.cur_url = location.protocol + '//' + location.host + location.pathname

    let widget_html = false;

    try {
      const response = await fetch(settings.rest_api_url, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(settings)
      });
      widget_html = await response.json();
    } catch (error) {
      console.error("vdrv Error:", error);
      return;
    }

    if(!widget_html.success){
      return;
    }

    document.body.append(stringToHTML(widget_html.data));

    const container = document.querySelector("#vdrv-widget")
    const widget = container.querySelector("#vdrv-widget-video-wrapper")
    const close_collapse_button = document.querySelector(".vdrv-widget-close")
    const cta_button = document.querySelector("#vdrv-widget #vdrv-cta-button")

    if (!widget) {
      return;
    }

    // Add iOS-specific attributes to video element
    const videoElement = document.querySelector("#player");
    if (!videoElement) {
      console.error("Video element not found");
      return;
    }

    videoElement.setAttribute('playsinline', '');
    videoElement.setAttribute('webkit-playsinline', '');
    videoElement.setAttribute('muted', '');

    const player = new Plyr("#player", {
      controls: [],
      blankVideo: 'https://cdn.plyr.io/static/blank.mp4',
      playsinline: true,
      muted: true,
      clickToPlay: false,
      disableContextMenu: true,
      autoplay: false,
      loop: { active: true },
      keyboard: {
        global: false,
      },
      tooltips: {
        controls: true,
      },
      captions: {
        active: true,
      },
      fullscreen: {
        enabled: false,
        fallback: true,
        iosNative: false,
        container: null,
      },
      vimeo: {
        byline: false,
        portrait: false,
        title: false,
        speed: false,
        transparent: false,
        background: true,
        controls: false,
      },
      youtube: {
        noCookie: false,
        rel: 0,
        enablejsapi: 1,
        showinfo: 0,
        iv_load_policy: 3,
        modestbranding: 1,
        playsinline: 1,
        origin: window.location.origin
      },
    });

    // Safe play function that handles both Promise and non-Promise cases
    function safePlay() {
      try {
        const playPromise = player.play();
        if (playPromise !== undefined) {
          playPromise.catch(error => {
            console.error("Playback failed:", error);
            enableIOSAutoplay();
          });
        }
      } catch (error) {
        console.error("Play error:", error);
        enableIOSAutoplay();
      }
    }

    // iOS-specific play handling with passive event listeners
    function enableIOSAutoplay() {
      const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
      
      if (isIOS) {
        const attemptPlay = () => {
          player.muted = true;
          safePlay();
        };

        // Add passive event listeners for better performance
        document.addEventListener('touchstart', attemptPlay, { once: true, passive: true });
        document.addEventListener('click', attemptPlay, { once: true, passive: true });
      }
    }

    player.on("ready", () => {
      console.log('vdrv player ready');
      player.muted = true;
      safePlay();
    });

    player.once("playing", () => {
      console.log('vdrv video start playing');
      container.classList.add("show");
    });

    // Add passive event listeners for touch events
    widget.addEventListener('click', handleWidgetClick, { passive: true });
    widget.addEventListener('mouseover', handleWidgetMouseOver, { passive: true });
    widget.addEventListener('mouseout', handleWidgetMouseOut, { passive: true });

    function handleWidgetClick() {
      if (!widget.classList.contains("closed") && !widget.classList.contains("active")) {
        widget.classList.add("active");
        player.muted = !player.muted;

        if (cta_button) {
          cta_button_logic();
        }

        if (settings.s_video_from_start) {
          player.restart();
          safePlay();
        }

        widget.style.borderColor = settings.on_hover_widget_border;
      } else if (widget.classList.contains("active")) {
        player.paused ? safePlay() : player.pause();
        widget.style.borderColor = settings.default_widget_border;
      }
    }

    function handleWidgetMouseOver() {
      widget.style.borderColor = settings.on_hover_widget_border;
    }

    function handleWidgetMouseOut() {
      if (!widget.classList.contains("active")) {
        widget.style.borderColor = settings.default_widget_border;
      }
    }

    function cta_button_logic() {
      const seconds = settings.cta_time ? parseInt(settings.cta_time) * 1000 : 1000;
      setTimeout(() => {
        cta_button.classList.add("active");
      }, seconds);

      if (settings.cta_type === "link") {
        cta_button.addEventListener('click', () => {
          window.open(
            settings.cta_link.url,
            settings.cta_link.blank ? "_blank" : "_self"
          );
        }, { passive: true });
      } else if (settings.cta_type === "action") {
        cta_button.addEventListener('click', () => {
          const element = document.querySelector(settings.cta_action.selector);
          element?.click();
        }, { passive: true });
      } else if (settings.cta_type === "scroll") {
        cta_button.addEventListener('click', () => {
          const element = document.getElementById(settings.cta_scroll.id);
          element?.scrollIntoView({
            behavior: "smooth",
            block: "start",
            inline: "nearest",
          });
        }, { passive: true });
      }
    }

    function stringToHTML(str) {
      const dom = document.createElement("div");
      dom.innerHTML = str;
      return dom;
    }

    close_collapse_button.addEventListener('click', () => {
      if (widget.classList.contains("active")) {
        widget.classList.remove("active");
        widget.classList.add("closed");
        if (cta_button) {
          cta_button.classList.remove("active");
        }

        player.muted = !player.muted;
        safePlay();
        setTimeout(() => {
          widget.classList.remove("closed");
        }, 1000);
      } else if (!widget.classList.contains("active")) {
        container.remove();
      }
    }, { passive: true });
  }

  function load_styles(link_href) {
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.type = 'text/css';
    link.href = link_href;
    document.getElementsByTagName('HEAD')[0].appendChild(link);
  }

  custom_on_load(init);

  function custom_on_load(callback) {
    if (document.readyState === 'complete') {
      callback();
    } else if (window.addEventListener) {
      window.addEventListener("load", callback, { passive: true });
    } else if (window.attachEvent) {
      window.attachEvent("onload", callback);
    } else {
      window.onload = callback;
    }
  }
};