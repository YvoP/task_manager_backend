import {Sortable} from 'sortablejs';
import { Modal } from 'bootstrap';

document.querySelectorAll('.sortable-col').forEach(row => {
    Sortable.create(row, {
        group: 'shared',
        animation: 150,
        draggable: '.sortable-item',
        ghostClass: 'ghost',
        chosenClass: 'chosen',

        // Element is dropped into the list from another list
        onAdd: function (/**Event*/evt) {
            const taskId = evt.item.dataset.taskId;
            const newStatus = evt.to.dataset.status;

            console.log("aller on bouge");

            fetch('/api/tasks/' + taskId + '/revision', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/ld+json',
                    'Accept': 'application/ld+json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    status: newStatus,
                }),
            });
        },

        // Changed sorting within list
        onUpdate: function (/**Event*/evt) {
            // change task position
        },
    });
});

//Modal addProjectUser handling

const modalElement = document.getElementById('addProjectUserModal')
const addProjectUserModal = Modal.getOrCreateInstance(modalElement);

const addProjectUserFormInput = document.forms["addTeamMemberForm"]["username"];
document.forms["addTeamMemberForm"].addEventListener("submit", addTeamMember);
modalElement.addEventListener('shown.bs.modal', () => {
    addProjectUserFormInput.focus()
})

function addTeamMember(event) {
    event.preventDefault();
    const formInputError = document.forms["addTeamMemberForm"].querySelector("#usernameToAddFeedback");

    const projectId = addProjectUserFormInput.dataset.projectId;
    const username = addProjectUserFormInput.value;

    if (username === "") {
        return false;
    }

    fetch('/api/project/' + projectId + '/user', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/ld+json',
            'Accept': 'application/ld+json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({
            username: username,
        }),
    }).then(r => {
        if (r.status === 500) {
            addProjectUserFormInput.classList.remove('is-invalid')
            r.json().then(json => { formInputError.innerHTML = json.detail; });
            addProjectUserFormInput.classList.add('is-invalid');
        }
        if (r.status === 201) {
            addProjectUserFormInput.value = '';
            addProjectUserModal.hide();
        }
    });
}
