/**
 * Theme: Approx - Bootstrap 5 Responsive Admin Dashboard
 * Author: Mannatthemes
 * Dropzone Js
 */
import Uppy from '@uppy/core';
import Dashboard from '@uppy/dashboard';
import {Tus} from "uppy";


const readFile = function (uploadedFile) {
    if (uploadedFile) {
        const reader = new FileReader();
        reader.onload = function () {
            const parent = document.querySelector('.preview-box');
            parent.innerHTML = `<img class="preview-content" src=${reader.result} />`;
        };

        reader.readAsDataURL(uploadedFile);
    }
};
const handleChange = function () {
    const fileUploader = document.querySelector('#input-file');
    const getFile = fileUploader.files
    if (getFile.length !== 0) {
        const uploadedFile = getFile[0];
        readFile(uploadedFile);
    }
}

const inputFile = document.getElementById('input-file')

if (inputFile) {
    inputFile.addEventListener('change', handleChange)
}

var uppy = new Uppy()
    .use(Dashboard, {
        inline: true,
        target: '#drag-drop-area'
    })
    .use(Tus, {endpoint: 'https://tusd.tusdemo.net/files/'})

uppy.on('complete', (result) => {
})



