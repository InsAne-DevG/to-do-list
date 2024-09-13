class TaskManager {
    constructor(taskListId, templateId, apis) {
        this.taskListElement = document.getElementById(taskListId);
        this.template = document.getElementById(templateId);
        this.apis = apis;
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        this.init();
    }

    init() {
        this.fetchTasks();
        this.setupEventListeners();
    }

    fetchTasks(fetchAllTaskList = false) {
        this.emptyErrors();
        let allTaskListQuery = "";
        if(fetchAllTaskList) {
            allTaskListQuery = "?allTaskList=1";
        }
        fetch(this.apis.getTasks + allTaskListQuery)
            .then(response => response.json())
            .then(tasks => {
                this.taskListElement.innerHTML = '';
                tasks.tasks.forEach((task, index) => {
                    this.renderTask(task, index+1);
                });
                this.totalTasksCount = tasks.tasks.length;
            });
    }

    renderTask(task, index) {
        let taskNode = this.template.content.cloneNode(true);
        taskNode.querySelector('.s-no').textContent = index;
        taskNode.querySelector('.task-name').textContent = task.task;
        if(task.is_completed) {
            taskNode.querySelector('.task-checkbox').remove();
            taskNode.querySelector('.task-status').innerHTML = '<b>Completed</b>';
        }
        taskNode.querySelector('.task-item').dataset.id = task.id;
        this.taskListElement.appendChild(taskNode);
    }

    addTask(taskText) {
        this.emptyErrors();
        fetch(this.apis.addTask, {
            method: 'POST',
            headers: { 
                'Content-Type' : 'application/json',
                'X-CSRF-TOKEN' : this.csrfToken
            },
            body: JSON.stringify({ task: taskText })
        })
        .then(res => {
            if (!res.ok) { 
                return res.json().then(error => { 
                    this.errorHandler(error.errors);
                });
            } else {
                return res.json();
            }
        })
        .then(task => {
            if(task){
                this.renderTask(task,  ++this.totalTasksCount);
                document.getElementById('new-task').value = '';
            }
        })
    }

    markTaskCompleted(taskId, isCompleted, ele) {
        this.emptyErrors();
        fetch(`${this.apis.updateTask}/${taskId}`, {
            method: 'PATCH',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN' : this.csrfToken
             },
            body: JSON.stringify({ 
                is_completed: isCompleted 
            })
        })
        .then(() => {
            this.playAnimation(ele);
            // this.fetchTasks();
        });
    }

    playAnimation(ele) {
        ele.querySelector('.task-status').textContent = 'Completed';
        ele.classList.add('fade-out');
        setTimeout(()=>{
            this.fetchTasks();
        }, 1000)
    }

    deleteTask(taskId) {
        this.emptyErrors();
        if (confirm('Are you sure you want to delete this task?')) {
            fetch(`${this.apis.deleteTask}/${taskId}`, {
                method: 'DELETE',
                headers : {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN' : this.csrfToken
                }
            })
            .then(() => {
                this.fetchTasks();
            });
        }
    }

    errorHandler(errors) {
        errors.forEach(error => {
            let newError = document.createElement('div');
            newError.innerHTML = error;
            document.getElementById('errors').append(newError);
        });
    }

    emptyErrors() {
        document.getElementById('errors').innerHTML = '';
    }

    setupEventListeners() {
        document.getElementById('add-task').addEventListener('click', () => {
            let taskText = document.getElementById('new-task').value;
            if (taskText) this.addTask(taskText);
        });

        document.getElementById('show-all-tasks').addEventListener('click', () => {
            this.fetchTasks(true);
        });

        document.getElementById('new-task').addEventListener('keydown', (e) => {
            if(e.key === 'Enter'){
                let taskText = document.getElementById('new-task').value;
                if (taskText) this.addTask(taskText);
            }
        });

        this.taskListElement.addEventListener('change', (e) => {
            if (e.target.classList.contains('task-checkbox')) {
                let taskId = e.target.closest('.task-item').dataset.id;
                this.markTaskCompleted(taskId, e.target.checked, e.target.closest('.task-item'));
            }
        });

        this.taskListElement.addEventListener('click', (e) => {
            if (e.target.classList.contains('delete-task')) {
                let taskId = e.target.closest('.task-item').dataset.id;
                this.deleteTask(taskId);
            }
        });
    }
}

const taskManager = new TaskManager('tasks-list', 'task-template',{
        getTasks: '/tasks',
        addTask: '/tasks',
        updateTask: '/tasks',
        deleteTask: '/tasks'
    });