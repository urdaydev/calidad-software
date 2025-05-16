// Modal form create
const btnAgregar = document.querySelector('.btn-add');
const modalCreate = document.querySelector('.modalCreate');
const close = document.querySelector('.modalCreate .close');
const btnCancelar = document.querySelector('.modalCreate .btn-cancel');

btnAgregar.addEventListener('click', () => {
    modalCreate.style.display = 'flex';
    modalCreate.classList.add('modal-animation');
    console.log('click');
});

close.addEventListener('click', () => {
    modalCreate.style.display = 'none';
});
btnCancelar.addEventListener('click', () => {
    modalCreate.style.display = 'none';
}
);
// Modal form update
const btnEdit = document.querySelectorAll('.btn-edit');
const modalUpdate = document.querySelector('.modalUpdate');
const closeUpdate = document.querySelector('.modalUpdate .close');
const btnCancelarUpdate = document.querySelector('.modalUpdate .btn-cancel');

btnEdit.forEach((btn) => {
    btn.addEventListener('click', () => {
        modalUpdate.style.display = 'flex';
        modalUpdate.classList.add('modal-animation');
    });


});

closeUpdate.addEventListener('click', () => {
    modalUpdate.style.display = 'none';
});

btnCancelarUpdate.addEventListener('click', () => {
    modalUpdate.style.display = 'none';
}
);