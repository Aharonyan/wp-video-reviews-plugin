(()=>{var e={552:()=>{window.vdrv_custom_on_load||(window.vdrv_custom_on_load=e=>{window.addEventListener?window.addEventListener("load",e,!1):window.attachEvent?window.attachEvent("onload",e):window.onload=e})}},t={};function o(i){var n=t[i];if(void 0!==n)return n.exports;var c=t[i]={exports:{}};return e[i](c,c.exports,o),c.exports}(()=>{"use strict";var e=e||{};e.actions=e.actions||{},e.filters=e.filters||{},e.add_action=function(t,o,i){void 0===i&&(i=10),e.actions[t]=e.actions[t]||[],e.actions[t].push({priority:i,callback:o})},e.add_filter=function(t,o,i){void 0===i&&(i=10),e.filters[t]=e.filters[t]||[],e.filters[t].push({priority:i,callback:o})},e.remove_action=function(t,o){e.actions[t]=e.actions[t]||[],e.actions[t].forEach((function(i,n){i.callback===o&&e.actions[t].splice(n,1)}))},e.remove_filter=function(t,o){e.filters[t]=e.filters[t]||[],e.filters[t].forEach((function(i,n){i.callback===o&&e.filters[t].splice(n,1)}))},e.do_action=function(t,o){var i=[];void 0!==e.actions[t]&&e.actions[t].length>0&&(e.actions[t].forEach((function(e){i[e.priority]=i[e.priority]||[],i[e.priority].push(e.callback)})),i.forEach((function(e){e.forEach((function(e){e(o)}))})))},e.apply_filters=function(t,o,i){var n=[];return void 0!==e.filters[t]&&e.filters[t].length>0&&(e.filters[t].forEach((function(e){n[e.priority]=n[e.priority]||[],n[e.priority].push(e.callback)})),n.forEach((function(e){e.forEach((function(e){o=e(o,i)}))}))),o};const t=e;o(552),window.fe_hooks=t,window.vdrv_custom_on_load((()=>{const e=document.getElementById("cta_type");function t(){let t=document.querySelectorAll(".cta-tab"),o=e.value,i=document.getElementById(`cta_${o}`);t.forEach(((e,t,o)=>{e.classList.remove("active")})),i&&i.classList.add("active")}t(),e.onchange=e=>{t()}})),window.vdrv_custom_on_load((()=>{const e=document.getElementById("d_video_type"),t=document.getElementById("video_advanced"),o=document.getElementById("add_video_settings");function i(){let e;document.querySelectorAll(".video-settings").forEach(((t,o,i)=>{let c=t.querySelector(".video_loader"),r=t.querySelector(".video_selected"),l=t.querySelector(".video_data"),d=t.querySelector(".remove-setting"),a=t.querySelector(".video-source select");d&&(d.onclick=()=>{t.remove()}),a.addEventListener("change",(function(){l.value="",n()})),c.onclick=t=>{t.preventDefault(),e||(e=wp.media.frames.file_frame=wp.media({title:"Add to Gallery",button:{text:"Select"},library:{type:["video"]},multiple:!1}),e.on("select",(function(){let t=e.state().get("selection").first().toJSON();r.innerHTML=t.filename,l.value=JSON.stringify(t),console.log(t)}))),e.open()}}))}function n(){document.querySelectorAll(".video-settings").forEach(((e,o,i)=>{const n=e.querySelector(".video-source select"),c=e.querySelector(".wordpress-loader"),r=e.querySelector(".source-id"),l=e.querySelector(".source-id input");t.querySelector(".remove-setting")&&t.querySelector(".remove-setting").classList.add("hide"),"local"!==n.value?(c.style.display="none",r.style.display="block",l.style.display="block"):(c.style.display="block",r.style.display="none")}))}function c(){let t=document.querySelectorAll(".video-tab"),o=e.value,i=document.getElementById(`video_${o}`);t.forEach(((e,t,o)=>{e.classList.remove("active")})),i&&i.classList.add("active")}i(),c(),n(),e.onchange=e=>{c()},o&&(o.onclick=()=>{let e=document.querySelectorAll("#video_advanced .video-settings");e.length,function(e){let o=document.querySelector("#video_advanced .video-settings").cloneNode(!0);console.log(o),o.id=`on_page_video_${e}`,o.querySelector("input.page-url-input").name=`vdrv_settings[vd_advanced][${e}][page_url]`,o.querySelector(".video-source select.video-provider").name=`vdrv_settings[vd_advanced][${e}][video_provider]`,o.querySelector(".video-source  select.video-format").name=`vdrv_settings[vd_advanced][${e}][video_format]`,o.querySelector(".source-id .video_data").name=`vdrv_settings[vd_advanced][${e}][video_data]`,o.querySelector(".remove-setting").classList.remove("hide"),console.log(o),t.querySelector(".settings-list").appendChild(o),i()}(parseFloat(e.length)),n()})}))})()})();