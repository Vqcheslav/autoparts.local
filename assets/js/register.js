import {DomElement, Server} from './classes';

document.querySelectorAll('.register-form').forEach(function (element) {
    element.addEventListener('submit', async function (e) {
        e.preventDefault();
        let messageDiv = document.getElementById('register-msg-div');

        DomElement.hide(messageDiv)

        let formData = new FormData(this);
        let response = await Server.postData('/api/user/register', formData, Server.getAcceptHeader());

        console.log(response);

        if (response.status !== 201) {
            messageDiv.classList.remove('alert-success');
            messageDiv.classList.add('alert-danger');
        } else {
            messageDiv.classList.remove('alert-danger');
            messageDiv.classList.add('alert-success');
        }

        DomElement.show(messageDiv);
        messageDiv.textContent = response.detail;
    });
});