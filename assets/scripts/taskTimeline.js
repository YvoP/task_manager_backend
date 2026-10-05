import { Timeline, DataSet } from 'vis-timeline/standalone';

// DOM element where the Timeline will be attached
let container = document.getElementById("visualization");

const tasks = JSON.parse(container.dataset.tasks);

console.log(tasks);

// Create a DataSet (allows two way data-binding)
let items = new DataSet(
    tasks.map(task => ({
        id: task.id,
        content: task.title,
        start: task.start,
        end: task.end,
        profileImage: task.profileImage,
        className: task.className,
    }))
);

// Configuration for the Timeline
const options = {
    template: function (item) {
        const wrapper = document.createElement('div');

        const title = document.createElement('p');
        title.className = 'fs-4 fw-medium my-2';
        title.textContent = item.content;

        const pp = document.createElement('div');
        pp.className =
            'position-absolute top-100 start-25 translate-middle badge';

        const image = document.createElement('img');
        image.src = item.profileImage;
        image.alt = '';
        image.className = 'avatar-sm rounded-circle';

        pp.appendChild(image);
        wrapper.appendChild(title);
        wrapper.appendChild(pp);

        return wrapper;
    },

    margin: {
        item: 20
    },
};

// Create a Timeline
let timeline = new Timeline(container, items, options);
