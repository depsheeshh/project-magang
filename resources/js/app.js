import './bootstrap';

function openModal(modalId) {
    document.getElementById('modal-backdrop').style.display = 'block';
    document.getElementById(modalId).style.display = 'block';
}
function closeModal(modalId) {
    document.getElementById('modal-backdrop').style.display = 'none';
    document.getElementById(modalId).style.display = 'none';
}
