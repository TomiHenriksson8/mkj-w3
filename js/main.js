'use strict';
const modifyLinks = document.querySelectorAll('.modify-link');
const modifyModal = document.querySelector('#modify-modal');
const modifyContent = document.querySelector('#modify-content');
const closeLinks = document.querySelectorAll('.close-modal');
const successModal = document.querySelector('#success-modal');

modifyLinks.forEach((link) => {
    link.addEventListener('click', async (evt) => {
        evt.preventDefault();
        const id = link.dataset.id;
        const response = await fetch('operations/modifyForm.php?id=' + id);
        const html = await response.text();
        modifyContent.innerHTML = '';
        modifyContent.insertAdjacentHTML('afterbegin', html);
        modifyModal.showModal();
    })
})

closeLinks.forEach((link) => {
    const parent = link.closest('dialog');
    link.addEventListener('click', () => {
        parent.close();
    })
})

const urlParms = new URLSearchParams(window.location.search);
if (urlParms.has('success')){
    successModal.showModal();
}