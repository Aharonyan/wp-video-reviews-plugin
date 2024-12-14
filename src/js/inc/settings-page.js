export default () => {
    let init = () => {
        const cta_type = document.getElementById('cta_type');
        showTab()

        cta_type.onchange = (event) => {
            showTab()
        }

        

        /**
         * Show CAT additional settings tab
         * @returns Show tab
         */
        function showTab() {
            let allTabs = document.querySelectorAll('.cta-tab')
            let val = cta_type.value
            let tab_id = document.getElementById(`cta_${val}`)
            /**
             * Remove all tabs
             */
            allTabs.forEach((currentValue, currentIndex, listObj)=>{
                currentValue.classList.remove('active')
            })
            /**
             * Activate the one that we need
             */
            if (!tab_id) { return }
            tab_id.classList.add('active')
        }

    }


    /**
     * Load after full page ready + some seconds 
     */
    window.vdrv_custom_on_load(init);
}