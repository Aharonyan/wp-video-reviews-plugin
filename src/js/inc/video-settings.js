export default () => {
    let init = () => {
        const d_video_type = document.getElementById('d_video_type'),
            video_advanced = document.getElementById('video_advanced'),
            add_new_settings = document.getElementById('add_video_settings')


        video_settings_logic()
        showTab()
        checkVideoSettingsSelections()

        d_video_type.onchange = (event) => {
            showTab()
        }


        function video_settings_logic() {
            // Uploading files
            let file_frame,
                video_settings = document.querySelectorAll('.video-settings')

            video_settings.forEach((currentValue, currentIndex, listObj) => {
                let 
                    image_loader = currentValue.querySelector(`.video_loader`),
                    video_selected = currentValue.querySelector(`.video_selected`),
                    video_input = currentValue.querySelector(`.video_data`),
                    remove_img = currentValue.querySelector('.remove-setting'),
                    source_select = currentValue.querySelector('.video-source select')
                

                /**
                 * Remove settings
                 */
                if (remove_img) {
                    remove_img.onclick = () => {
                        currentValue.remove()
                    }
                }

                source_select.addEventListener("change", function(){
                    video_input.value = ''
                    checkVideoSettingsSelections()
                })

                /**
                 * Loading media uploader
                */
                image_loader.onclick = (event) => {
                    event.preventDefault();

                    // If the media frame already exists, reopen it.
                    if (file_frame) {
                        file_frame.open();
                        return;
                    }

                    // Create the media frame.
                    file_frame = wp.media.frames.file_frame = wp.media({
                        title: 'Add to Gallery',
                        button: {
                            text: 'Select'
                        },
                        library: {
                            type: ['video']
                        },
                        multiple: false // Set to true to allow multiple files to be selected
                    });

                    // When an image is selected, run a callback.
                    file_frame.on('select', function () {
                        // We set multiple to false so only get one image from the uploader
                        let selection = file_frame.state().get('selection').first().toJSON();
                        video_selected.innerHTML = selection.filename;
                        video_input.value = JSON.stringify(selection)

                        console.log(selection)
                    });
                    file_frame.open();

                }
            })

        }

        function checkVideoSettingsSelections(){
            const video_settings = document.querySelectorAll('.video-settings')

            video_settings.forEach((currentValue, currentIndex, listObj) => {
                const source_select = currentValue.querySelector('.video-source select'),
                    wordpress_loader = currentValue.querySelector('.wordpress-loader'),
                    source_id = currentValue.querySelector('.source-id'),
                    source_id_input = currentValue.querySelector('.source-id input');

                    if(video_advanced.querySelector('.remove-setting')){
                        const remove_button = video_advanced.querySelector('.remove-setting')
                    
                        remove_button.classList.add('hide')
                    }
                    
                    if(source_select.value !== 'local'){
                        wordpress_loader.style.display = 'none';
                        source_id.style.display = 'block';
                        source_id_input.style.display = 'block';
                    } else {
                        wordpress_loader.style.display = 'block';
                        source_id.style.display = 'none';
                    }
                
            })
        }

        /**
         * Show CAT additional settings tab
         * @returns Show tab
         */
        function showTab() {
            let allTabs = document.querySelectorAll('.video-tab')
            let val = d_video_type.value
            let tab_id = document.getElementById(`video_${val}`)
            /**
             * Remove all tabs
             */
            allTabs.forEach((currentValue, currentIndex, listObj) => {
                currentValue.classList.remove('active')
            })
            /**
             * Activate the one that we need
             */
            if (!tab_id) { return }
            tab_id.classList.add('active')
        }



        if(add_new_settings){
            add_new_settings.onclick = () => {
                let video_settings = document.querySelectorAll('#video_advanced .video-settings'),
                    last_settings_id = video_settings.length - 1
    
                video_settings_html(parseFloat(video_settings.length))
                checkVideoSettingsSelections()
            }
        }

        /**
         * Create settings html and append to video settings
         * @param {*} id 
         */
        function video_settings_html(id) {

            let video_settings = document.querySelector('#video_advanced .video-settings').cloneNode(true)
            console.log(video_settings)

            video_settings.id = `on_page_video_${id}`
            video_settings.querySelector('input.page-url-input').name = `vdrv_settings[vd_advanced][${id}][page_url]`
            video_settings.querySelector('.video-source select.video-provider').name = `vdrv_settings[vd_advanced][${id}][video_provider]`
            video_settings.querySelector('.video-source  select.video-format').name = `vdrv_settings[vd_advanced][${id}][video_format]`
            video_settings.querySelector('.source-id .video_data').name = `vdrv_settings[vd_advanced][${id}][video_data]`
            video_settings.querySelector('.remove-setting').classList.remove('hide')



            console.log(video_settings)

            video_advanced.querySelector('.settings-list').appendChild(video_settings)

            /**
             * Updating selectors
             */
            video_settings_logic()
        }


    }


    /**
     * Load after full page ready + some seconds 
     */
    window.vdrv_custom_on_load(init);
}