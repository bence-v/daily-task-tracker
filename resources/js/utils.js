import {toggleTaskCompletion} from "./tasks.js";

export function addGlobalEventListener(type, selector, callback, parent = document) {
    parent.addEventListener(type, e => {
        const target = e.target.closest(selector);

        if(target) {
            callback(e, target);
        }
    })
};

export function initTaskCompletionHandlers() {
    addGlobalEventListener('click', '[data-task-toggle]', async (e, button) => {
        e.preventDefault();

        const taskId = button.dataset.taskId;
        const container = button.closest('[data-task-item]');
        const removeDueTask = button.closest('div[data-task-item]');
        console.log(removeDueTask);
       const {completed} = await toggleTaskCompletion(taskId, button);

        if(removeDueTask) {
            removeDueTask?.remove();
            let overdueCounter = document.querySelector('#overdue-count');
            overdueCounter.innerHTML = parseInt(overdueCounter.innerHTML) <= 0 ? 0 : parseInt(overdueCounter.innerHTML) - 1;
        }

       if(container) {
           container.dataset.completed = completed;
           button.innerHTML = completed
               ? `<svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>`
               : `<svg class="h-6 w-6 text-gray-400 hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>`;
       }
    })
}
