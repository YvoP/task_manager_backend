import {Sortable} from 'sortablejs';

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

document.forms["addTeamMemberForm"].addEventListener("submit", addTeamMember);

function addTeamMember(event) {
    event.preventDefault();

    let formInput = document.forms["addTeamMemberForm"]["username"];
    let formInputError = document.forms["addTeamMemberForm"].querySelector("#usernameToAddFeedback");

    let projectId = formInput.dataset.projectId;
    let username = formInput.value;

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
            formInput.classList.remove('is-invalid')
            r.json().then(json => { formInputError.innerHTML = json.detail; });
            formInput.classList.add('is-invalid');
        }
        if (r.status === 200) {
            formInput.value = '';
        }
    });
}
