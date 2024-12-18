
document.addEventListener("DOMContentLoaded", function () {
    const selectElement = document.getElementById('users');

    selectElement.insertAdjacentHTML('beforebegin', '<p style="color: #6c757d;">Selecione um ou mais usuários</p>');

    selectElement.addEventListener('mousedown', function (e) {
        e.preventDefault();
        const option = e.target;
        if (option.tagName === 'OPTION') {
            option.selected = !option.selected;
        }
    });
});
