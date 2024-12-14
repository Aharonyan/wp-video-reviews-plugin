/**
 * On load custom function
 * @param {*} callback 
*/
if (!window.vdrv_custom_on_load) {
    window.vdrv_custom_on_load = (callback) => {
        if (window.addEventListener)
            window.addEventListener("load", callback, false);
        else if (window.attachEvent)
            window.attachEvent("onload", callback);
        else window.onload = callback;
    }
}
